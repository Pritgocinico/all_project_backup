<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\Notifications;
use PDF;
use Carbon\Carbon;
class OrderController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        $notification = $user->unreadNotifications;
        view()->share('notifications', $notification);
        view()->share('setting', $setting);
    }
    public function orders(Request $req){
        $page  = 'Orders';
        $icon  = 'orders.png';
        $agents = User::where('role',3)->get();
        if(Auth::user()->role == 1){
             if($req->has('agent') || $req->has('status') || $req->has('start_date') || $req->has('end_date')){
                $query = Order::orderBy('id','Desc');
                 if($req->agent != ''){
                     $query->where('agent_id',$req->agent);
                 }
                 if($req->status != ''){
                    if($req->status == 3){
                        $query->where('status',2);
                        $query->orWhere('status',3);
                    }else{
                        $query->where('status',$req->status);
                    }
                 }
                if($req->start_date != '' || $req->end_date != ''){
                  $start = Carbon::parse($req->start_date)->toDateString();
                    $end = Carbon::parse($req->end_date)->toDateString();
                    $from = Carbon::createFromFormat('Y-m-d', $start)->format('Y-m-d');
                    $to = Carbon::createFromFormat('Y-m-d', $end)->format('Y-m-d');

                    if($req->date_filter == 1 ){
                         $query->whereDate('created_at', '>=', $from);
                         $query->whereDate('created_at', '<=', $to);
                    }elseif($req->date_filter == 2){
                        $query->whereDate('completed_date', '>=', $from);
                        $query->whereDate('completed_date', '<=', $to);
                        $query->orderBy('completed_date','DESC');
                    }elseif($req->date_filter == 3){
                        $query->whereDate('deliverd_date', '>=', $from);
                        $query->whereDate('deliverd_date', '<=', $to);
                        $query->orderBy('deliverd_date','DESC');
                    }

                }else{
                    $query->orderBy('id','Desc');
                }
                $orders = $query->get();
             }else{
                 $orders = Order::orderBy('id','Desc')->get();
             }
            return view('admin.orders.orders',compact('agents','orders','page','icon'));
        }elseif(Auth::user()->role == 3){
            $user = User::where('agent',Auth::user()->id)->orderBy('id','Desc')->get();
            // $orders = Order::
            // join('users','users.id','=','orders.user_id')
            // ->where('users.agent', Auth::user()->id)
            // ->where('users.role',2)
            // ->orderBy('orders.id','Desc')
            // ->get('orders.*');
            
            $orders = Order::where('agent_id',Auth::user()->id)->orderBy('id','Desc')->get();
            return view('agent.orders.orders',compact('orders','agents','page','icon'));
        }else{
            return redirect()->route('login');
        }
    }
    public function orderDetail($id){
        $order = Order::where('id',$id)->first();
        $order_items = OrderItem::where('order_id',$order->id)->get();
        $page  = 'Orders';
        $icon  = 'orders.png';
        if(Auth::user()->role == 1){
            return view('admin.orders.order_details',compact('order','order_items','page','icon'));
        }elseif(Auth::user()->role == 3){
            return view('agent.orders.order_details',compact('order','order_items','page','icon'));
        }else{
            return redirect()->route('login');
        }
    }
    public function addOrder(){
        $categories = Category::all();
        $parent_categories = Category::where('parent','==',0)->get();
        $products = Product::where('status',1)->orderBy('id','Desc')->get();
        $users = User::where('role',2)->where('status',1)->get();
        $agents = User::where('role',3)->where('status',1)->get();
        $page  = 'Orders';
        $icon  = 'orders.png';
        if(Auth::user()->role == 1){
            return view('admin.orders.add_order',compact('categories','parent_categories','products','users','page','icon','agents'));
        }else{
            return redirect()->route('login');
        }
    }
    public function addOrderData(Request $req){
        $req->validate([
            'customer_name'             => 'required',
            'phone_no'                  => 'required',
            'user_id'                   => 'required|not_in:0',
            'email'                     => 'required',
            'address'                   => 'required',
            'floor_no'                  => 'required',
            'locality'                  => 'required',
            'city'                      => 'required',
            'state'                     => 'required',
            'country'                   => 'required',
            // 'transport'                 => 'required',
        ]);
        if(Order::count() > 0){
            $id = Order::all()->last()->id;
        }else{
            $id = 0;
        }
        $id = $id+1;
        $result = substr($req->customer_name, 0, 2);
        $str_length = 4;
        $date = Carbon::now();
        $monthName = $date->format('M');
        // Left padding if number < $str_length
        $str = substr("0000{$id}", -$str_length);
        $number = str_pad($id,5,'0',STR_PAD_LEFT);
        $order_id = 'GST-'.$monthName.'-'.$number;

        $order = new Order();
        $order->order_id        = $order_id;
        $order->customer_name   = $req->customer_name;
        $order->phone           = $req->phone_no;
        $order->email           = $req->email;
        $order->user_id         = $req->user_id;
        $order->address         = $req->address;
        $order->floor_no        = $req->floor_no;
        $order->locality        = $req->locality;
        $order->city            = $req->city;
        $order->state           = $req->state;
        $order->country         = $req->country;
        $order->transport       = $req->transport;
        $order->agent_id        = $req->agent_id;
        $order->status          = 0;
        $order->total           = $req->amount;
        $insert_order           = $order->save();
        try{
            //send notification to Admin
            $userSchema = User::first();
            $details = [
                'name'  => 'Order Created.',
                'type'  => 'Order',
                'body'  => $order_id.' '.'created by '. Auth::user()->name,
                'url'   => route('admin.orders'),
            ];
            Notification::send($userSchema, new Notifications($details));
        }catch(\Exception $e){

        }
        if($insert_order){
            if(!empty($req->category)){
                $category  = $req->category;
                $product   = $req->products;
                $variant   = $req->variant;
                $quantity     = $req->quantity;
                $total        = $req->product_total;
                $price        = $req->pr_price;
                foreach($category as $key=>$value){
                    if($value != 0){
                        foreach($quantity as $key1=>$val1){
                            if($key==$key1){
                                $v_qty=$val1;
                            }
                        }
                        foreach($total as $key5=>$val5){
                            if($key==$key5){
                                $v_total=$val5;
                            }
                        }
                        foreach($price as $key6=>$val6){
                            if($key==$key6){
                                $v_price=$val6;
                            }
                        }
                        foreach($product as $key3=>$val3){
                            if($key==$key3){
                                $v_product=$val3;
                            }
                        }
                        foreach($variant as $key4=>$val4){
                            if($key==$key4){
                                $v_variant=$val4;
                            }
                        }
                        $item = new OrderItem;
                        $item->order_id         = $order->id;
                        // $item->category_id      = $value;
                        $item->product_id       = $v_product;
                        $item->variant_id       = $v_variant;
                        $item->quantity         = $v_qty;
                        $item->price            = $v_price;
                        $item->total            = $v_total;
                        // $item->created_by       = Auth::user()->id;
                        $item->save();
                    }
                }
            }
            $user = User::where('id',Auth::user()->id)->first();
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Orders';
            $log->log       = ' Order ('.$order_id .') created by '.$user->name;
            $log->save();

            if(Auth::user()->role == 1){
                return redirect()->route('admin.orders')->with('confirm',$order);
            }else{
                return redirect()->route('login');
            }
        }else{
            Session::flash('alert','Something Went Wrong.');
            if(Auth::user()->role == 1){
                return redirect()->route('admin.add_order');
            }else{
                return redirect()->route('login');
            }
        }
    }
    public function editOrder($id)
    {
        $categories = Category::all();
        $parent_categories = Category::where('parent','==',0)->get();
        $products = Product::where('status',1)->orderBy('id','Desc')->get();
        $order = Order::where('id',$id)->first();
        $items = OrderItem::where('order_id',$id)->get();
        $page  = 'Orders';
        $icon  = 'assets.png';
        if(Auth::user()->role == 1){
            return view('admin.orders.edit_order',compact('order','items','categories','parent_categories','products','page','icon'));
        }else{
            return redirect()->route('login');
        }
    }
    public function deleteOrder(Request $req, $id = NULL){
        $order = Order::where('id',$id)->delete();
        return 1;
    }
    public function change_status(Request $req){
        $status = Order::where('id',$req->order)->first();
        $status->status = $req->status;
        if($status->status == 2){
            $status->completed_date = now();
            $status->confirm_by = Auth::user()->id;
        }
        $status->save();
        try{
            //send notification to Admin
            $userSchema = User::first();
            $details = [
                'name'  => 'Order Status Changed.',
                'type'  => 'Order',
                'body'  => $status->order_id.' '.' status changed by '. Auth::user()->name,
                'url'   => route('admin.orders'),
            ];
            Notification::send($userSchema, new Notifications($details));
        }catch(\Exception $e){

        }
        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Orders';
        $log->log       = 'Order ('.$status->order_id.') status changed by '.$user->name;
        $log->save();

        return 1;

        // return response()->json(['success'=>1]);
    }
    public function change_order_item_status(Request $req){
        $status = OrderItem::where('id',$req->order)->first();
        $status->status = $req->status;
        if($status->status == 3){
            $status->delivered_date = now();
            $order_status = Order::where('id',$req->parent_order)->first();
            $order_status->status = 4;
            $order_status->save();
        }
        $status->save();
        
        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Orders';
        $log->log       = 'Order item ('.$status->id.') status changed by '.$user->name;
        $log->save();

        return 1;

        // return response()->json(['success'=>1]);
    }
    public function delete_order_item(Request $req){
        $item = OrderItem::where('id', $req->data_id)->first();
        if ($item) {
            $item->delete();
        }
        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Orders';
        $log->log       = 'Order item ('.$item->id.') Deleted by '.$user->name;
        $log->save();

        return 1;
        // return response()->json(['success'=>1]);
    }
    public function uploadLrcopy(Request $req){
        // $status = Order::where('id',$req->order_id)->first();
        // if($req->has('lr_copy') && $req->file('lr_copy') != null){
        //     $image = $req->file('lr_copy');
        //     $destinationPath = 'public/lr_copy/';
        //     $rand=rand(1,100);
        //     $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
        //     $image->move($destinationPath, $docImage);
        //     $img=$docImage;
        // }else{
        //     $img = '';
        // }
        // $status->lr_copy = $img;
        // // $status->status  = 3;
        // // $status->deliverd_date = now();
        // $status->save();
        
        $status = Order::where('id', $req->order_id)->first();
        
        if ($req->hasFile('lr_copy')) {
            $images = [];
            foreach ($req->file('lr_copy') as $file) {
                if ($file != null) {
                    $destinationPath = 'public/lr_copy/';
                    $rand = rand(1, 100);
                    $docImage = date('YmdHis') . $rand . "." . $file->getClientOriginalExtension();
                    $file->move($destinationPath, $docImage);
                    $images[] = $docImage;
                }
            }
            $status->lr_copy = json_encode($images); // Save as JSON string in database
        } else {
            $status->lr_copy = ''; // No files uploaded
        }
        $status->save();
        try{
            //send notification to Admin
            $userSchema = User::first();
            $details = [
                'name'  => 'LR Copy Uploaded.',
                'type'  => 'Order',
                'body'  => $status->order_id.' '.'LR copy uploaded by '. Auth::user()->name,
                'url'   => route('admin.orders'),
            ];
            Notification::send($userSchema, new Notifications($details));
        }catch(\Exception $e){

        }
        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Orders';
        $log->log       = 'Order ('.$status->order_id.') LR Copy by '.$user->name;
        $log->save();

        return redirect()->back();

        // return response()->json(['success'=>1]);
    }
    public function downloadInvoice($id = NULL){
        $order = Order::where('id',$id)->first();
        $order_items = OrderItem::where('order_id',$id)->get();
        $pdf = PDF::loadView('invoice/order_invoice',array('order'=>$order,'order_items'=>$order_items))->setOption('defaultFont', 'Satoshi Regular');
        // return view('invoice/order_invoice',compact('order','order_items'));
        return $pdf->download('Invoice-'.$id.'.pdf');
    }
    public function getStatementData(Request $req){
        if(isset($req->period)){
            if($req->period == 1){
                $orders = Order::where('status','!=',1)->whereDate('created_at',Carbon::today())->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor']     = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['transport'] = $user->transport;
                    $statement['created_at']   = $user->created_at;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    if($req->option == 'view'){
                        return view('admin.users.view_statement',compact('statement'));
                    }else{
                        $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                        // return view('invoice/order_invoice',compact('order','order_items'));
                        return $pdf->download('statement-'.now().'.pdf');
                    }
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->period == 2){
                $date = \Carbon\Carbon::today()->subDays(7);
                $orders = Order::where('status','!=',1)->where('created_at','>=',$date)->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['transport'] = $user->transport;
                    $statement['created_at']   = $user->created_at;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    if($req->option == 'view'){
                        return view('admin.users.view_statement',compact('statement'));
                    }else{
                        $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                        // return view('invoice/order_invoice',compact('order','order_items'));
                        return $pdf->download('statement-'.now().'.pdf');
                    }
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->period == 3){
                $orders = Order::where('status','!=',1)->where('created_at', now()->subDays(30)->endOfDay())->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['created_at']   = $user->created_at;
                    $statement['transport'] = $user->transport;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    if($req->option == 'view'){
                        return view('admin.users.view_statement',compact('statement'));
                    }else{
                        $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                        // return view('invoice/order_invoice',compact('order','order_items'));
                        return $pdf->download('statement-'.now().'.pdf');
                    }
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->period == 4){
                $orders = Order::where('status','!=',1)->whereYear('created_at', date('Y'))->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['transport'] = $user->transport;
                    $statement['created_at']   = $user->created_at;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    if($req->option == 'view'){
                        return view('admin.users.view_statement',compact('statement'));
                    }else{
                        $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                        // return view('invoice/order_invoice',compact('order','order_items'));
                        return $pdf->download('statement-'.now().'.pdf');
                    }

                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Please Select Filter Type.'],200);
            }
        }else{
            return 0;
        }
    }
    public function viewStatement(Request $req){
        if(isset($req->filter_type)){
            if($req->filter_type == 1){
                $orders = Order::where('status','!=',1)->whereDate('created_at',Carbon::today())->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor']     = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['transport'] = $user->transport;
                    $statement['created_at']   = $user->created_at;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    return view('statement.view_statement',compact('statement'));
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 2){
                $date = \Carbon\Carbon::today()->subDays(7);
                $orders = Order::where('status','!=',1)->where('created_at','>=',$date)->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['transport'] = $user->transport;
                    $statement['created_at']   = $user->created_at;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    return view('statement.view_statement',compact('statement'));
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 3){
                $date = \Carbon\Carbon::today()->subDays(30);
                $orders = Order::where('status','!=',1)->where('created_at','>=',$date)->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['transport'] = $user->transport;
                    $statement['created_at']   = $user->created_at;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    return view('statement.view_statement',compact('statement'));
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 4){
                $orders = Order::where('status','!=',1)->whereYear('created_at', date('Y'))->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['transport'] = $user->transport;
                    $statement['created_at']   = $user->created_at;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    return view('statement.view_statement',compact('statement'));

                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Please Select Filter Type.'],200);
            }
        }else{
            return 0;
        }
    }
    public function viewAgentStatement(Request $req){
        if(isset($req->filter_type)){
            if($req->filter_type == 1){
                $orders = Order::where('status','!=',1)->whereDate('created_at',Carbon::today())->where('agent_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['role']      = $user->role;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor']     = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['headquarter']   = $user->headquarter;
                    // $statement['transport'] = $user->transport;
                    $statement['created_at']   = $user->created_at;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    return view('statement.view_statement',compact('statement'));
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 2){
                $date = \Carbon\Carbon::today()->subDays(7);
                $orders = Order::where('status','!=',1)->where('created_at','>=',$date)->where('agent_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['role']      = $user->role;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['headquarter']   = $user->headquarter;
                    // $statement['transport'] = $user->transport;
                    $statement['created_at']   = $user->created_at;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    return view('statement.view_statement',compact('statement'));
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 3){
                $date = \Carbon\Carbon::today()->subDays(30);
                $orders = Order::where('status','!=',1)->where('created_at','>=',$date)->where('agent_id',$req->user_id)->get();
                // $orders = Order::where('status',2)->whereMonth('created_at', now()->subDays(30)->endOfDay())->where('agent_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['role']      = $user->role;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['headquarter']   = $user->headquarter;
                    // $statement['transport'] = $user->transport;
                    $statement['created_at']   = $user->created_at;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    return view('statement.view_statement',compact('statement'));
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 4){
                $orders = Order::where('status','!=',1)->whereYear('created_at', date('Y'))->where('agent_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['role']      = $user->role;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['headquarter']   = $user->headquarter;
                    // $statement['transport'] = $user->transport;
                    $statement['created_at']   = $user->created_at;
                    if(!blank($orders)){
                        foreach($orders as $order){
                            $ary = array();
                            $ary['id']              = $order->id;
                            $ary['order_id']        = $order->order_id;
                            $ary['customer_name']   = $order->customer_name;
                            $ary['phone']           = $order->phone;
                            $ary['email']           = $order->email;
                            $ary['floor_no']        = $order->floor_no;
                            $ary['address']         = $order->address;
                            $ary['locality']        = $order->locality;
                            $ary['city']            = $order->city;
                            $ary['state']           = $order->state;
                            $ary['country']         = $order->country;
                            $ary['created_at']      = $order->created_at;
                            $ary['total']           = $order->total;
                            $items = OrderItem::where('order_id',$order->id)->get();
                            if(!blank($items)){
                                foreach($items as $item){
                                    $product = Product::where('id',$item->product_id)->first();
                                    $variant = ProductVariant::where('id',$item->variant_id)->first();
                                    $ary_item = array();
                                    $ary_item['user_id'] = $req->user_id;
                                    $ary_item['product_id'] = $item->product_id;
                                    $ary_item['product_name'] = $product->name;
                                    $ary_item['image'] = url('/').'/public/products/'.$product->image;
                                    $images = ProductImage::where('product_id',$item->product_id)->get();
                                    if(!blank($images)){
                                        foreach($images as $item1){
                                            $p_img = array();
                                            $p_img['id'] = $item1->id;
                                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                            $ary_item['images'][] = $p_img;
                                        }
                                    }else{
                                        $ary_item['images'] = '';
                                    }
                                    $ary_item['brand_name'] = $product->brand_name;
                                    $ary_item['variant_id'] = $item->variant_id;
                                    $ary_item['capacity'] = $variant->capacity;
                                    $ary_item['variant_sku'] = $variant->sku;
                                    $ary_item['variant_price'] = $variant->actual_price;
                                    $ary_item['price'] = $item->price;
                                    $ary_item['quantity'] = $item->quantity;
                                    $ary_item['total'] = $item->price*$item->quantity;
                                    $ary['items'][] = $ary_item;
                                }
                            }
                            $statement['orders'][] = $ary;
                        }
                    }else{
                        $statement['orders'] = [];
                    }
                    return view('statement.view_statement',compact('statement'));

                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Please Select Filter Type.'],200);
            }
        }else{
            return 0;
        }
    }
     public function saleReport(Request $req){
        if($req->has('agent')|| $req->has('status') ||  $req->has('start_date') ||  $req->has('end_date')){
            $query = Order::orderBy('orders.id','Desc')->join('users as u1','orders.user_id','=','u1.id');
            if($req->agent != ''){
                $query->where('u1.agent',$req->agent);
            }
            if($req->status != ''){
                if($req->status == 3){
                    $query->where('orders.status',2);
                    $query->orWhere('orders.status',4);
                    $query->orWhere('orders.status',3);
                }else{
                    $query->where('orders.status',$req->status);
                }
            }
            if($req->start_date != '' || $req->end_date != ''){
                  $start = Carbon::parse($req->start_date)->toDateString();
                    $end = Carbon::parse($req->end_date)->toDateString();
                    $from = Carbon::createFromFormat('Y-m-d', $start)->format('Y-m-d');
                    $to = Carbon::createFromFormat('Y-m-d', $end)->format('Y-m-d');

                    if($req->date_filter == 1 ){
                         $query->whereDate('orders.created_at', '>=', $from);
                         $query->whereDate('orders.created_at', '<=', $to);
                    }elseif($req->date_filter == 2){
                        $query->whereDate('orders.completed_date', '>=', $from);
                        $query->whereDate('orders.completed_date', '<=', $to);
                        $query->orderBy('orders.completed_date','DESC');
                    }elseif($req->date_filter == 3){
                        $query->whereDate('orders.deliverd_date', '>=', $from);
                        $query->whereDate('orders.deliverd_date', '<=', $to);
                        $query->orderBy('orders.deliverd_date','DESC');
                    }

                }else{
                    $query->orderBy('id','Desc');
                }
            $orders = $query->get();
        }else{
             $orders = Order::orderBy('id','Desc')->get();
        }
        $page  = 'Sales Report';
        $icon  = 'assets.png';
        if(Auth::user()->role == 1){
            // return (new ExportOrder())->download('report.xlsx');
            return view('admin.reports.sales_report',compact('orders','page','icon'));
        }else{
            return redirect()->route('login');
        }
    }
}
