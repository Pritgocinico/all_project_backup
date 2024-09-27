<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use View;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\GalleryPhoto;
use App\Models\SliderImage;
use App\Models\DealerCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use PDF;
use Notification;
use App\Notifications\Notifications;
class ApiController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    //settings

    public function settings(Request $req){
        $array = array();
        $array['privacy_policy_url'] = 'https://arconindia.com/privacy-policy-mobile/';
        $array['terms_and_condition_url'] = 'https://arconindia.com/terms-mobile/';
        return response()->json([
            'status' => 1,
            'settings'=>$array
        ],200);
    }

    // post all category data
    public function gallery(Request $req){
        $gallery = GalleryPhoto::orderBy('id','desc')->where('gallery_type',1)->get();
        $videos = GalleryPhoto::orderBy('id','desc')->where('gallery_type',2)->get();
        $array_push = array();
        $video_gallery = array();
        if(!blank($gallery) || !blank($videos)){
            foreach($gallery as $item){
                $array = array();
                $array['id'] = $item->id;
                $array['image'] = url('/').'/public/gallery/'.$item->photo;
                $array['name'] = $item->photo_name;
                $array['status'] = $item->status;
                $array['type'] = $item->type;
                $array['created_at'] = $item->created_at;
                array_push($array_push,$array);
            }
            foreach($videos as $item){
                $array = array();
                $array['id'] = $item->id;
                $array['image'] = url('/').'/public/gallery/'.$item->photo;
                $array['name'] = $item->photo_name;
                $array['link'] = $item->link;
                $array['status'] = $item->status;
                $array['type'] = $item->type;
                $array['created_at'] = $item->created_at;
                array_push($video_gallery,$array);
            }
            return response()->json([
                'status' => 1,
                'gallery'=>$array_push,
                'video_gallery' => $video_gallery
            ],200);
        }else{
             return response()->json(['status'=>0,'error'=> 'Gallery Images Not Found.'],404);
        }
    }

    // Banners
    public function banners(Request $req){
        $banners = SliderImage::orderBy('id','DESC')->get();
        $array_push = array();
        if(!blank($banners)){
            foreach($banners as $item){
                $array = array();
                $array['id'] = $item->id;
                $array['image'] = url('/').'/public/banners/'.$item->image;
                $array['created_at'] = $item->created_at;
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'banners'=>$array_push
            ],200);
        }else{
             return response()->json(['status'=>0,'error'=> 'Banner Images Not Found.'],404);
        }
    }
    //Agents
    public function agents(Request $req){
        $agents = User::where('role',3)->where('active',1)->where('status',1)->get();
        $array_push = array();
        if(!blank($agents)){
            foreach($agents as $item){
                $array = array();
                $array['id'] = $item->id;
                $array['name'] = $item->name;
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'agents'=>$array_push
            ],200);
        }else{
             return response()->json(['status'=>0,'error'=> 'Agents Not Found.'],404);
        }
    }

    //Products & Category


    // All categories data
    public function categories(Request $req){
        $categories = Category::where('parent',0)->where('status',1)->get();
        $array_push = array();
        $array_push1 = array();
        $banners = SliderImage::all();
        if(!blank($banners)){
            foreach($banners as $item){
                $array = array();
                $array['id'] = $item->id;
                $array['image'] = url('/').'/public/banners/'.$item->image;
                $array['created_at'] = $item->created_at;
                array_push($array_push1,$array);
            }
        }
        if(!blank($categories)){
            foreach($categories as $item){
                $cat = Category::where('id',$item->parent)->first();
                $array = array();
                $array['id'] = $item->id;
                $array['image'] = url('/').'/public/categories/'.$item->image;
                $array['name'] = $item->name;
                $array['status'] = $item->status;
                $array['parent'] = $item->parent;
                $count = Category::where('parent',$item->id)->count();
                if(!blank($count)){
                    $array['sub_cat_count'] = $count;
                }
                $array['created_at'] = $item->created_at;
                array_push($array_push,$array);
            }
        }else{
             return response()->json(['status'=>0,'error'=> 'Categories Not Found.'],404);
        }
          return response()->json([
                'status' => 1,
                'categories'=>$array_push,
                'banners' => $array_push1
            ],200);
    }

    // Get Sub Catgeories By Category ID
    public function subCategory(Request $req,$id = NULL){
       if(!blank($id)){
            $categories = Category::where('parent',$id)->where('status',1)->get();
            $array_push = array();
            if(!blank($categories)){
                foreach($categories as $item){
                    $cat = Category::where('id',$item->parent)->first();
                    $array = array();
                    $array['id'] = $item->id;
                    $array['image'] = url('/').'/public/categories/'.$item->image;
                    $array['name'] = $item->name;
                    $array['status'] = $item->status;
                    $array['parent'] = $item->parent;
                    if(!blank($cat)){
                        $array['parent_category'] = $cat->name;
                    }
                    $array['created_at'] = $item->created_at;
                    array_push($array_push,$array);
                }
                return response()->json([
                    'status' => 1,
                    'categories'=>$array_push
                ],200);
            }else{
                 return response()->json(['status'=>0,'error'=> 'Sub Categories Not Found.'],404);
            }
       }
    }

    //Get Categories By Dealer
    public function dealerCategories(Request $req,$id){
        $categories = Category::where('parent',0)->where('status',1)->get();
        $array_push = array();
        $array_push1 = array();
        $banners = SliderImage::all();
        if(!blank($banners)){
            foreach($banners as $item){
                $array = array();
                $array['id'] = $item->id;
                $array['image'] = url('/').'/public/banners/'.$item->image;
                $array['created_at'] = $item->created_at;
                array_push($array_push1,$array);
            }
        }
        $dealer_categories = DealerCategory::where('user_id',$id)->get();
        if(!blank($categories)){
            foreach($categories as $item){
                $childs = Category::where('parent',$item->id)->get();
                $cat = 0;
                if(!blank($childs)){
                    foreach($childs as $child){
                        foreach($dealer_categories as $d_cat){
                            if($d_cat->category_id == $child->id){
                                $cat++;
                            }
                        }
                    }
                    if($cat > 0){
                            $cat = Category::where('id',$item->parent)->first();
                            $array = array();
                            $array['id'] = $item->id;
                            $array['image'] = url('/').'/public/categories/'.$item->image;
                            $array['name'] = $item->name;
                            $array['status'] = $item->status;
                            $array['parent'] = $item->parent;
                            $count = Category::where('parent',$item->id)->count();
                            if(!blank($count)){
                                $array['sub_cat_count'] = $count;
                            }
                            $array['created_at'] = $item->created_at;
                            array_push($array_push,$array); 
                    }
                }else{
                    foreach($dealer_categories as $d_cat){
                        if($d_cat->category_id == $item->id){
                            $cat = Category::where('id',$item->parent)->first();
                            $array = array();
                            $array['id'] = $item->id;
                            $array['image'] = url('/').'/public/categories/'.$item->image;
                            $array['name'] = $item->name;
                            $array['status'] = $item->status;
                            $array['parent'] = $item->parent;
                            $count = Category::where('parent',$item->id)->count();
                            if(!blank($count)){
                                $array['sub_cat_count'] = $count;
                            }
                            $array['created_at'] = $item->created_at;
                            array_push($array_push,$array);
                        }
                    }
                }   
            }
        }else{
             return response()->json(['status'=>0,'error'=> 'Categories Not Found.'],404);
        }
          return response()->json([
                'status' => 1,
                'categories'=>$array_push,
                'banners' => $array_push1
            ],200);
    }
    
    //Get Sub CAtegories by dealer
    public function dealerSubCategories(Request $req,$id = NULL,$dealer = NULL){
        if(!blank($id)){
             $categories = Category::where('parent',$id)->where('status',1)->get();
             $array_push = array();
             $dealer_categories = DealerCategory::where('user_id',$dealer)->get();
             if(!blank($categories)){
                 foreach($categories as $item){
                    foreach($dealer_categories as $d_cat){
                        if($d_cat->category_id == $item->id){
                            $cat = Category::where('id',$item->parent)->first();
                            $array = array();
                            $array['id'] = $item->id;
                            $array['image'] = url('/').'/public/categories/'.$item->image;
                            $array['name'] = $item->name;
                            $array['status'] = $item->status;
                            $array['parent'] = $item->parent;
                            if(!blank($cat)){
                                $array['parent_category'] = $cat->name;
                            }
                            $array['created_at'] = $item->created_at;
                            array_push($array_push,$array);
                        }
                    }
                 }
                 return response()->json([
                     'status' => 1,
                     'categories'=>$array_push
                 ],200);
             }else{
                  return response()->json(['status'=>0,'error'=> 'Sub Categories Not Found.'],404);
             }
        }
     }

    // All products data
    public function products(Request $req){
        $products = Product::where('active',1)->where('status',1)->get();
        $array_push = array();
        if(!blank($products)){
            foreach($products as $item){
                $price_data = ProductVariant::where('product_id',$item->id)->where('selected',1)->first();
                if(blank($price_data)){
                    $price = ProductVariant::where('product_id',$item->id)->whereNotNull('price')->min('price');
                }else{
                    $price = $price_data->price;
                }
                $price = ProductVariant::where('product_id',$item->id)->whereNotNull('price')->min('price');
                $var = ProductVariant::where('product_id',$item->id)->orderBy('actual_price','desc')->first();
                $array = array();
                $array['id'] = $item->id;
                $array['image'] = url('/').'/public/products/'.$item->image;
                $images = ProductImage::where('product_id',$item->id)->get();
                if(!blank($images)){
                    foreach($images as $item1){
                        $p_img = array();
                        $p_img['id'] = $item1->id;
                        $p_img['image'] = url('/').'/public/products/'.$item1->image;
                        $array['images'][] = $p_img;
                    }
                }else{
                    $array['images'] = [];
                }
                $array['name'] = $item->name;
                $array['brand_name'] = $item->brand_name;
                $array['sku'] = $item->sku;
                $array['category'] = $item->category;
                $array['price'] = $price;
                if(!blank($var)){
                    $cap = $var->capacity;
                }else{
                    $cap = '';
                }
                $array['capacity'] = $cap;
                $array['description'] = $item->description;
                $array['status'] = $item->status;
                if($item->cgst == ''){
                    $array['cgst'] = 0;
                }else{
                    $array['cgst'] = $item->cgst;
                }
                if($item->sgst == ''){
                    $array['sgst'] = 0;
                }else{
                    $array['sgst'] = $item->sgst;
                }
                $array['created_at'] = $item->created_at;
                $variants = ProductVariant::where('product_id',$item->id)->get();
                if(!blank($variants)){
                    foreach($variants as $variant){
                        $var = array();
                        $var['id'] = $variant->id;
                        $var['capacity'] = $variant->capacity;
                        $var['sku'] = $variant->sku;
                        $var['ncr_rate'] = $variant->price;
                        $var['price'] = $variant->actual_price;
                        $var['status'] = $variant->status;
                        $var['created_at'] = $variant->created_at;
                        $array['variants'][] = $var;
                    }
                }
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'products'=>$array_push
            ],200);
        }else{
             return response()->json(['status'=>0,'error'=> 'Products Not Found.'],404);
        }
    }

     // Product By ID data
    public function product(Request $req,$id = NULL){
        if(!blank($id)){
            $item = Product::where('id',$id)->first();
            if(!blank($item)){
                // $price = ProductVariant::where('product_id',$item->id)->whereNotNull('price')->min('price');
                $price_data = ProductVariant::where('product_id',$item->id)->where('selected',1)->first();
                if(blank($price_data)){
                    $price = ProductVariant::where('product_id',$item->id)->whereNotNull('price')->min('price');
                }else{
                    $price = $price_data->price;
                }
                $var = ProductVariant::where('product_id',$item->id)->orderBy('actual_price','desc')->first();
                $array = array();
                $array['status'] = $item->status;
                $array['id'] = $item->id;
                $array['image'] = url('/').'/public/products/'.$item->image;
                $images = ProductImage::where('product_id',$item->id)->get();
                if(!blank($images)){
                    foreach($images as $item1){
                        $p_img = array();
                        $p_img['id'] = $item1->id;
                        $p_img['image'] = url('/').'/public/products/'.$item1->image;
                        $array['images'][] = $p_img;
                    }
                }else{
                    $array['images'] = [];
                }
                $array['name'] = $item->name;
                $array['brand_name'] = $item->brand_name;
                $array['sku'] = $item->sku;
                $array['category'] = $item->category;
                $array['price'] = $price;
                if(!blank($var)){
                    $cap = $var->capacity;
                }else{
                    $cap = '';
                }
                $array['capacity'] = $cap;
                $array['description'] = $item->description;
                if($item->cgst == ''){
                    $array['cgst'] = 0;
                }else{
                    $array['cgst'] = $item->cgst;
                }
                if($item->sgst == ''){
                    $array['sgst'] = 0;
                }else{
                    $array['sgst'] = $item->sgst;
                }
                $array['created_at'] = $item->created_at;
                $variants = ProductVariant::where('product_id',$item->id)->get();
                if(!blank($variants)){
                    foreach($variants as $variant){
                        $var = array();
                        $var['id'] = $variant->id;
                        $var['capacity'] = $variant->capacity;
                        $var['sku'] = $variant->sku;
                        $var['ncr_rate'] = $variant->price;
                        $var['price'] = $variant->actual_price;
                        $var['status'] = $variant->status;
                        $var['created_at'] = $variant->created_at;
                        $array['variants'][] = $var;
                    }
                }
                return response()->json($array,200);
            }else{
                return response()->json(['status'=>0,'error'=> 'Product Not Found.'],404);
            }
        }else{
            return response()->json(['status'=>0,'error'=> 'Product Not Found.'],404);
        }
    }


    // Category wise Product

    public function categoryProduct(Request $req,$id = NULL){
        if(!blank($id)){
            $items = Product::where('category',$id)->where('status',1)->where('active',1)->get();
            if(!blank($items)){
                $array_push = array();
                foreach($items as $item){
                    $price_data = ProductVariant::where('product_id',$item->id)->where('selected',1)->first();
                    if(blank($price_data)){
                        $price = ProductVariant::where('product_id',$item->id)->whereNotNull('price')->min('price');
                    }else{
                         $price = $price_data->price;
                    }
                    $var = ProductVariant::where('product_id',$item->id)->orderBy('actual_price','desc')->first();
                    $array = array();
                    $array['status'] = $item->status;
                    $array['id'] = $item->id;
                    $array['image'] = url('/').'/public/products/'.$item->image;
                    $images = ProductImage::where('product_id',$item->id)->get();
                    if(!blank($images)){
                        foreach($images as $item1){
                            $p_img = array();
                            $p_img['id'] = $item1->id;
                            $p_img['image'] = url('/').'/public/products/'.$item1->image;
                            $array['images'][] = $p_img;
                        }
                    }else{
                        $array['images'] = [];
                    }
                    $array['name'] = $item->name;
                    $array['brand_name'] = $item->brand_name;
                    $array['sku'] = $item->sku;
                    $array['category'] = $item->category;
                    $array['price'] = $price;
                    if(!blank($var)){
                        $cap = $var->capacity;
                    }else{
                        $cap = '';
                    }
                    $array['capacity'] = $cap;
                    $array['description'] = $item->description;
                    if($item->cgst == ''){
                        $array['cgst'] = 0;
                    }else{
                        $array['cgst'] = $item->cgst;
                    }
                    if($item->sgst == ''){
                        $array['sgst'] = 0;
                    }else{
                        $array['sgst'] = $item->sgst;
                    }
                    $array['created_at'] = $item->created_at;
                    $variants = ProductVariant::where('product_id',$item->id)->get();
                    if(!blank($variants)){
                        foreach($variants as $variant){
                            $var = array();
                            $var['id'] = $variant->id;
                            $var['capacity'] = $variant->capacity;
                            $var['sku'] = $variant->sku;
                            $var['ncr_rate'] = $variant->price;
                            $var['price'] = $variant->actual_price;
                            $var['status'] = $variant->status;
                            $var['created_at'] = $variant->created_at;
                            $array['variants'][] = $var;
                        }
                    }
                    array_push($array_push,$array);

                }
                return response()->json(['status'=>1,'products'=>$array_push],200);
            }else{
                return response()->json(['status'=>0,'error'=> 'Products Not Found.'],404);
            }
        }else{
            return response()->json(['status'=>0,'error'=> 'Category Not Found.'],404);
        }
    }

    // Orders
    
    public function deleteOrder(Request $request, $id = NULL){
       
                if(!blank($id)){
                $item = OrderItem::where('id', $id)->first();
                if ($item) {
                    
                    $item->delete();
                
                $log = new Log();
                $log->user_id   = 'Agent';
                $log->module    = 'Orders';
                $log->log       = 'Order item ('.$item->id.') Deleted by Agent';
                $log->save();
                
                return response()->json(['status'=>1,'message'=>'Item deleted successfully!'],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Item not Found.'],200);
                }
                }else{
                     return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
                }
    }
    
    public function placeOrder(Request $req){
        $validator = Validator::make($req->all(), [
            'customer_name'         => 'required',
            // 'email'                 => 'required|email',
            'phone'                 => 'required',
            'floor_no'              => 'required',
            'address'               => 'required',
            'locality'              => 'required',
            'city'                  => 'required',
            'state'                 => 'required',
            // 'transport'             => 'required',
            // 'country'               => 'required',
            'total'                 => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $date = Carbon::now();
            $monthName = $date->format('M');
            $or = Order::latest('id')->first();
            if(!blank($or)){
                $id = $or->id + 1;
            }else{
                $id = 1;
            }
            $number = str_pad($id,5,'0',STR_PAD_LEFT);
            $order_id = 'GST-'.$monthName.'-'.$number;
            $user = User::where('id',$req->user_id)->first();
            $agent = User::where('id',$user->agent)->first();
            try{
                $order = new Order();
                $order->order_id        = $order_id;
                $order->customer_name   = $req->customer_name;
                $order->phone           = $req->phone;
                $order->email           = $req->email;
                $order->floor_no        = $req->floor_no;
                $order->address         = $req->address;
                $order->locality        = $req->locality;
                $order->city            = $req->city;
                $order->state           = $req->state;
                $order->country         = "India";
                $order->total           = $req->total;
                $order->transport       = $req->transport;
                $order->user_id         = $req->user_id;
                $order->agent_id        = $agent->id;
                $order->deliverd_at     = $req->deliverd_at;
                $order->status          = 1;
                $order->save();
                if($order){
                    $order_items = CartItem::where('user_id',$req->user_id)->get();
                    if(!blank($order_items)){
                        foreach($order_items as $items){
                            $order_item = new OrderItem();
                            $order_item->order_id       = $order->id;
                            $order_item->product_id     = $items->product_id;
                            $order_item->variant_id     = $items->variant_id;
                            $order_item->price          = $items->price;
                            $order_item->quantity       = $items->quantity;
                            $order_item->total          = $items->price*$items->quantity;
                            $order_item->save();
                        }
                        $remove = CartItem::where('user_id',$req->user_id)->delete();
                    }
                    try{
                        //http Url to send sms.
                        $usr = User::where('id',$req->user_id)->first();
                        $agent = User::where('id',$usr->agent)->first();
                        $url="http://trans.jaldisms.com/smsstatuswithid.aspx";
                        $fields = array(
                        'mobile' => 9879982567,
                        'pass' => 'Rdv@00239',
                        'senderid' => 'AGRRCO',
                        'to' => $agent->phone,
                        'templateid' => 1407169147815542948,
                        'msg' => $req->customer_name." થકી ઑર્ડર ".$order_id." પ્રાપ્ત થયેલ છે. બિલ ".$req->total."/-",
                        'msgtype'=> 'uc',
                        );
                        //url-ify the data for the POST
                        $fields_string = '';
                        foreach($fields as $key=>$value) { $fields_string .=
                        $key.'='.$value.'&'; }
                        rtrim($fields_string, '&');
                        //open connection
                        $ch = curl_init();
                        //set the url, number of POST vars, POST data
                        curl_setopt($ch,CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch,CURLOPT_POST, count($fields));
                        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                        //execute post
                        $result = curl_exec($ch);
                        //close connection
                        curl_close($ch);

                        $setting = Setting::first();
                         //http Url to send sms.
                        $usr1 = User::where('id',$req->user_id)->first();
                        $agent1 = User::where('id',$usr1->agent)->first();
                        $url1="http://trans.jaldisms.com/smsstatuswithid.aspx";
                        $fields1 = array(
                        'mobile' => 9879982567,
                        'pass' => 'Rdv@00239',
                        'senderid' => 'AGRRCO',
                        'to' => $setting->phone,
                        'templateid' => 1407169147811147323,
                        'msg' => $req->customer_name." થકી ".$agent1->name." એજન્ટને ઑર્ડર ".$order_id." પ્રાપ્ત થયેલ છે. બિલ ".$req->total."/-",
                        'msgtype'=> 'uc',
                        );
                        //url-ify the data for the POST
                        $fields_string1 = '';
                        foreach($fields1 as $key=>$value) { $fields_string1 .=
                        $key.'='.$value.'&'; }
                        rtrim($fields_string1, '&');
                        //open connection
                        $ch1 = curl_init();
                        //set the url, number of POST vars, POST data
                        curl_setopt($ch1,CURLOPT_URL, $url1);
                        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch1,CURLOPT_POST, count($fields1));
                        curl_setopt($ch1,CURLOPT_POSTFIELDS, $fields_string1);
                        //execute post
                        $result1 = curl_exec($ch1);
                        //close connection
                        curl_close($ch1);
                    }
                    catch(Exception $e){

                    }
                    $user = User::where('id',$req->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Orders';
                    $log->log       = ' Order ('.$order_id .') Placed.';
                    $log->save();
                    return response()->json(['status'=>1,'order_id'=>$order->id],200);
                }
            }catch(\Exception $e){
                 return response()->json(['status'=>0,'error'=>'Order Not Placed.'], 404);
            }
        }
    }
    public function placeAgentOrder(Request $req){
        $validator = Validator::make($req->all(), [
            'customer_name'         => 'required',
            // 'email'                 => 'required|email',
            'phone'                 => 'required',
            'floor_no'              => 'required',
            'address'               => 'required',
            'locality'              => 'required',
            'city'                  => 'required',
            'state'                 => 'required',
            // 'transport'             => 'required',
            // 'country'               => 'required',
            'total'                 => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $date = Carbon::now();
            $monthName = $date->format('M');
            $or = Order::latest('id')->first();
            if(!blank($or)){
                $id = $or->id + 1;
            }else{
                $id = 1;
            }
            $number = str_pad($id,5,'0',STR_PAD_LEFT);
            $order_id = 'GST-'.$monthName.'-'.$number;
            $user = User::where('id',$req->dealer)->first();
            if(!blank($user)){
                $dealer = $user->id;
            }else{
                $dealer = 0;
            }
            // print_r($req->All());
            $agent = User::where('id',$req->user_id)->first();
            try{
                $order = new Order();
                $order->order_id        = $order_id;
                $order->customer_name   = $req->customer_name;
                $order->phone           = $req->phone;
                $order->email           = $req->email;
                $order->floor_no        = $req->floor_no;
                $order->address         = $req->address;
                $order->locality        = $req->locality;
                $order->city            = $req->city;
                $order->state           = $req->state;
                $order->country         = "India";
                $order->total           = $req->total;
                $order->transport       = $req->transport;
                $order->user_id         = $dealer;
                $order->agent_id        = $agent->id;
                $order->deliverd_at     = $req->deliverd_at;
                $order->status          = 1;
                $order->save();

                if($order){
                    $order_items = CartItem::where('user_id',$req->user_id)->get();
                    if(!blank($order_items)){
                        foreach($order_items as $items){
                            $order_item = new OrderItem();
                            $order_item->order_id       = $order->id;
                            $order_item->product_id     = $items->product_id;
                            $order_item->variant_id     = $items->variant_id;
                            $order_item->price          = $items->price;
                            $order_item->quantity       = $items->quantity;
                            $order_item->total          = $items->price*$items->quantity;
                            $order_item->save();
                        }
                        $remove = CartItem::where('user_id',$req->user_id)->delete();
                    }

                    try{
                        //http Url to send sms.
                        $setting = Setting::first();
                        $usr = User::where('id',$req->dealer)->first();
                        $agent = User::where('id',$req->user_id)->first();
                        $url="http://trans.jaldisms.com/smsstatuswithid.aspx";
                        $fields = array(
                        'mobile' => 9879982567,
                        'pass' => 'Rdv@00239',
                        'senderid' => 'AGRRCO',
                        'to' => $agent->phone,
                        'templateid' => 1407169147815542948,
                        'msg' => $req->customer_name." થકી ઑર્ડર ".$order_id." પ્રાપ્ત થયેલ છે. બિલ ".$req->total."/-",
                        'msgtype'=> 'uc',
                        );
                        //url-ify the data for the POST
                        $fields_string = '';
                        foreach($fields as $key=>$value) { $fields_string .=
                        $key.'='.$value.'&'; }
                        rtrim($fields_string, '&');
                        //open connection
                        $ch = curl_init();
                        //set the url, number of POST vars, POST data
                        curl_setopt($ch,CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch,CURLOPT_POST, count($fields));
                        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                        //execute post
                        $result = curl_exec($ch);
                        //close connection
                        curl_close($ch);
                         //http Url to send sms.
                        $usr1 = User::where('id',$req->dealer)->first();
                        $agent1 = User::where('id',$req->user_id)->first();
                        $url1="http://trans.jaldisms.com/smsstatuswithid.aspx";
                        $fields1 = array(
                        'mobile' => 9879982567,
                        'pass' => 'Rdv@00239',
                        'senderid' => 'AGRRCO',
                        'to' => $setting->phone,
                        'templateid' => 1407169147811147323,
                        'msg' => $req->customer_name." થકી ".$agent1->name." એજન્ટને ઑર્ડર ".$order_id." પ્રાપ્ત થયેલ છે. બિલ ".$req->total."/-",
                        'msgtype'=> 'uc',
                        );
                        //url-ify the data for the POST
                        $fields_string1 = '';
                        foreach($fields1 as $key=>$value) { $fields_string1 .=
                        $key.'='.$value.'&'; }
                        rtrim($fields_string1, '&');
                        //open connection
                        $ch1 = curl_init();
                        //set the url, number of POST vars, POST data
                        curl_setopt($ch1,CURLOPT_URL, $url1);
                        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch1,CURLOPT_POST, count($fields1));
                        curl_setopt($ch1,CURLOPT_POSTFIELDS, $fields_string1);
                        //execute post
                        $result1 = curl_exec($ch1);
                        //close connection
                        curl_close($ch1);
                    }
                    catch(Exception $e){

                    }
                    $user = User::where('id',$req->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Orders';
                    $log->log       = ' Order ('.$order_id .') Placed.';
                    $log->save();
                    return response()->json(['status'=>1,'order_id'=>$order->id],200);
                }
            }catch(\Exception $e){
                 return response()->json(['status'=>0,'error'=>'Order Not Placed.'], 404);
            }
        }
    }

    public function OrderByID(Request $req, $id = NULL){
        if(!blank($id)){
            $order = Order::where('id',$id)->first();
            if(!blank($order)){
                $array = array();
                $array['id'] = $order->id;
                $array['order_id'] = $order->order_id;
                $array['customer_name'] = $order->customer_name;
                $array['email'] = $order->email;
                $array['phone'] = $order->phone;
                $array['floor_no'] = $order->floor_no;
                $array['address'] = $order->address;
                $array['locality'] = $order->locality;
                $array['city']  = $order->city;
                $array['state']  = $order->state;
                $array['country']  = "India";
                $array['total']  = $order->total;
                $array['status']  = $order->status;
                $array['transport'] = $order->transport;
                $array['deliverd_date'] = $order->deliverd_date;
                // if(!blank($order->lr_copy)){
                //     $array['lr_copy'] = url('/').'/public/lr_copy/'.$order->lr_copy;
                // }else{
                //     $array['lr_copy'] = null;
                // }
                
                
                if (!is_null($order->lr_copy)) { // Check if $order->lr_copy is not null
                    $lrCopies = json_decode($order->lr_copy, true);
                    if (is_array($lrCopies)) { // Check if decoding was successful and it's an array
                        foreach ($lrCopies as $key => $copy) {
                            $array['lr_copy'][$key] = url('/').'/public/lr_copy/'.$copy;
                        }
                    } else {
                        $array['lr_copy'][] = url('/').'/public/lr_copy/'.$order->lr_copy;
                    }
                } else {
                    $array['lr_copy'] = [];
                }

                $array['created_at'] = $order->created_at;
                $array['completed_date'] = $order->completed_date;
                $array['invoice_url'] = route('order.invoice',$order->id);
                $order_items = OrderItem::where('order_id',$order->id)->get();
                foreach($order_items as $o_items){
                    $items = array();
                    $p_name = Product::where('id',$o_items->product_id)->first();
                    $v_name = ProductVariant::where('id',$o_items->variant_id)->first();
                    $items['var_id']            = $v_name->id;
                    $items['product_name']      = $p_name->name;
                    $items['product_image']     = url('/').'/public/products/'.$p_name->image;
                    $items['brand_name']        = $p_name->brand_name;
                    $items['varinat_sku']       = $v_name->sku;
                    $items['varinat_capacity']  = $v_name->capacity;
                    $items['price']             = $o_items->price;
                    $items['quantity']          = $o_items->quantity;
                    $items['status']            = $o_items->status;
                    $array['order_items'][]     = $items;
                }
                return response()->json(['status'=>1,'order'=>$array],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Order Not Found'],404);
            }
        }else{
           return response()->json(['status'=>0,'error'=>'Order Not Found'],404);
        }
    }

    public function userOrders(Request $req, $id = NULL){
        if(!blank($id)){
            $orders = Order::where('user_id',$id)->orderBy('id','desc')->get();
            if(!blank($orders)){
                $array_push = array();
                foreach($orders as $order){
                    $array = array();
                    $array['id'] = $order->id;
                    $array['order_id'] = $order->order_id;
                    $array['customer_name'] = $order->customer_name;
                    $array['email'] = $order->email;
                    $array['phone'] = $order->phone;
                    $array['floor_no'] = $order->floor_no;
                    $array['address'] = $order->address;
                    $array['locality'] = $order->locality;
                    $array['city']  = $order->city;
                    $array['state']  = $order->state;
                    $array['country']  = "India";
                    $array['total']  = $order->total;
                    $array['status']  = $order->status;
                    $array['transport'] = $order->transport;
                    if(!blank($order->lr_copy)){
                        $array['lr_copy'] = url('/').'/public/lr_copy/'.$order->lr_copy;
                    }else{
                        $array['lr_copy'] = null;
                    }
                    $array['created_at'] = $order->created_at;
                    $array['completed_date'] = $order->completed_date;
                    $array['invoice_url'] = route('order.invoice',$order->id);
                    $order_items = OrderItem::where('order_id',$order->id)->get();
                    foreach($order_items as $o_items){
                        $items = array();
                        $p_name = Product::where('id',$o_items->product_id)->first();
                        $v_name = ProductVariant::where('id',$o_items->variant_id)->first();
                        $items['product_name']      = $p_name->name;
                        $items['product_image']     = url('/').'/public/products/'.$p_name->image;
                        $items['brand_name']        = $p_name->brand_name;
                        $items['varinat_sku']       = $v_name->sku;
                        $items['varinat_capacity']  = $v_name->capacity;
                        $items['price']             = $o_items->price;
                        $items['quantity']          = $o_items->quantity;
                        $items['status']            = $o_items->status;
                        $array['order_items'][]     = $items;
                    }
                    array_push($array_push,$array);
                }
                return response()->json(['status'=>1,'orders'=>$array_push],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Orders Not Found'],404);
            }
        }else{
           return response()->json(['status'=>0,'error'=>'Orders Not Found'],404);
        }
    }
    public function agentDeleteOrder($id = NULL){
        if(!blank($id)){
            $order_item = OrderItem::where('order_id',$id)->delete();
           
            $order = Order::where('id',$id)->first();
            if(!blank($order)){
                $order->delete();
                $log = new Log();
                $log->user_id   = Auth::user()->name;
                $log->module    = 'Orders';
                $log->log       = 'Order ('.$order->order_id .') Deleted by '.$user1->name;
                $log->save();
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
            }
            return response()->json(['status'=>1,'message'=>'Order deleted successfully!'],200);
        }else{
           return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
        }
        return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
    }
    //Users
    public function user(Request $req,$id = NULL){
        if(!blank($id)){
            $user = User::where('id',$id)->first();
            if(!blank($user)){
                $array = array();
                $array['id'] = $user->id;
                if(!blank($user->image)){
                    $array['image'] = url('/').'/public/users/'.$user->image;
                }else{
                    $array['image'] = '';
                }
                $agent = User::where('id',$user->agent)->first();
                $array['name']          = $user->name;
                $array['email']         = $user->email;
                $array['phone']         = $user->phone;
                $array['agent']         = $agent->name;
                $array['transport']     = $user->transport;
                $array['locality']      = $user->locality;
                $array['address']       = $user->address;
                $array['floor_no']      = $user->floor_no;
                $array['city']          = $user->city;
                $array['state']         = $user->state;
                $array['country']       = $user->country;
                $array['gst_number']    = $user->gst_number;
                $array['status']        = $user->status;
                $array['created_at']    = $user->created_at;
                return response()->json($array,200);
            }else{
                return response()->json(['status'=>0,'error'=>'not_found'],404);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Not Found'],404);
        }
    }
    public function userDetails(Request $req){
        $validator = Validator::make($req->all(), [
            'name'            => 'required',
            // 'email'               => 'required|unique:users,email',
            'phone'               => 'required|unique:users,phone',
            'password'            => 'required',
            'agent'               => 'required',
            // 'transport'           => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $user = new User();
            $user->name         = $req->name;
            $user->email        = $req->email;
            $user->phone        = $req->phone;
            $user->floor_no     = $req->floor_no;
            $user->address      = $req->address;
            $user->locality     = $req->locality;
            $user->city         = $req->city;
            $user->state        = $req->state;
            $user->country      = $req->country;
            $user->agent        = $req->agent;
            $user->transport    = $req->transport;
            $user->gst_number   = $req->gst_number;
            $user->role         = 2;
            $user->password     = Hash::make($req->password);
            $user->save();
            if($req->has('categories')){
                foreach($req->categories as $category){
                    $category1 = new DealerCategory();
                    $category1->user_id      = $user->id;
                    $category1->category_id  = $category;
                    $category1->save();
                }
            }
            $agent = User::where('id',$req->agent)->first();
            try{
                $details = [
                    'name'  => 'Dealer Signup.',
                    'type'  => 'Dealer',
                    'body'  => $req->name.' '.'Signup.',
                    'url'   => route('agent.view_user',$user->id),
                ];
                Notification::send($agent, new Notifications($details));
                $userSchema = User::first();
                $details = [
                    'name'  => 'Dealer Signup Successfully.',
                    'type'  => 'Dealer',
                    'body'  => $req->name.' '.'Signup.',
                    'url'   => route('admin.view_user',$user->id),
                ];
                Notification::send($userSchema, new Notifications($details));
                if(!blank($agent->email)){
                    $details = [
                        'title'     => 'Mail from Arcon',
                        'name'      => $req->name,
                        'phone'     => $req->phone,
                        'email'     => $req->email,
                        'user_id'   => $user->id,
                        'role'      => 2,
                    ];
                    \Mail::to($agent->email)->send(new \App\Mail\DealerMail($details));
                }

                if(!blank($setting->admin_email)){
                    $details = [
                        'title'     => 'Mail from Arcon',
                        'name'      => $req->name,
                        'phone'     => $req->phone,
                        'email'     => $req->email,
                        'user_id'   => $user->id,
                        'role'      => 1,
                    ];
                    \Mail::to($setting->admin_email)->send(new \App\Mail\DealerMail($details));
                }
            }catch(\Exception $e){

            }

            $log = new Log();
            $log->user_id   = $user->name;
            $log->module    = 'User';
            $log->log       = 'User ('.$req->name .') Sign Up Successfully.';
            $log->save();
            return response()->json(['status'=>1],200);
        }
    }
    public function checkUser(Request $req){
         $input = $req->all();
        $validator = Validator::make($req->all(), [
            'email'               => 'required',
            'password'            => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error_type'=>4,'error'=>$validator->errors()], 404);
        }else{
            $fieldType = filter_var($req->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            $credentials = $req->only($fieldType, 'password');
            if (Auth::attempt(array($fieldType => $input['email'], 'password' => $input['password']))) {
                $role = Auth::user()->role;
                $user=Auth::user();
                if($user->active == 1){
                    if($user->role == 2){
                        if($user->status == 1 && $user->agent_status == 1){
                            $user->update([
                                'last_login_at' => Carbon::now()->toDateTimeString(),
                                'last_login_ip' => $req->getClientIp()
                            ]);
                            if($user->role==2 && $user->active == 1){
                                $log = new Log();
                                $log->user_id   = $user->name;
                                $log->module    = 'Login';
                                $log->log       = $user->name.' Logged in Successfully';
                                $log->save();
                                if(!blank($user)){
                                    $array = array();
                                    $array['id'] = $user->id;
                                    if(!blank($user->image)){
                                        $array['image'] = url('/').'/public/users/'.$user->image;
                                    }else{
                                        $array['image'] = '';
                                    }
                                    $agent = User::where('id',$user->agent)->first();
                                    $array['name']          = $user->name;
                                    $array['email']         = $user->email;
                                    $array['phone']         = $user->phone;
                                    $array['agent']         = $agent->name;
                                    $array['transport']     = $user->transport;
                                    $array['locality']      = $user->locality;
                                    $array['address']       = $user->address;
                                    $array['floor_no']      = $user->floor_no;
                                    $array['city']          = $user->city;
                                    $array['state']         = $user->state;
                                    $array['country']       = $user->country;
                                    $array['gst_number']    = $user->gst_number;
                                    $array['status']        = $user->status;
                                    $array['created_at']    = $user->created_at;
                                    return response()->json(['status'=>1,'role'=>'dealer','user'=>$array],200);
                                }else{
                                    return response()->json(['status'=>0,'error'=>'not_found'],404);
                                }
                            }else{
                                return response()->json(['status'=>0,'error_type'=>1,'error'=>'User Not Found.'],200);
                            }
                        }else{
                            return response()->json(['status'=>0,'error_type'=>2,'error'=>'User Not Verified.'],404);
                        }
                    }elseif($user->role == 3){
                        $user->update([
                            'last_login_at' => Carbon::now()->toDateTimeString(),
                            'last_login_ip' => $req->getClientIp()
                        ]);
                        $log = new Log();
                        $log->user_id   = $user->name;
                        $log->module    = 'Login';
                        $log->log       = $user->name.' Agent Logged in Successfully';
                        $log->save();
                        if(!blank($user)){
                            $array = array();
                            $array['id'] = $user->id;
                            if(!blank($user->image)){
                                $array['image'] = url('/').'/public/users/'.$user->image;
                            }else{
                                $array['image'] = '';
                            }
                            $array['name']          = $user->name;
                            $array['agent_name']    = $user->agent_name;
                            $array['email']         = $user->email;
                            $array['phone']         = $user->phone;
                            $array['headquarter']   = $user->headquarter;
                            // $array['locality']      = $user->locality;
                            // $array['address']       = $user->address;
                            // $array['floor_no']      = $user->floor_no;
                            // $array['city']          = $user->city;
                            // $array['state']         = $user->state;
                            // $array['country']       = $user->country;
                            // $array['gst_number']    = $user->gst_number;
                            $array['status']        = $user->status;
                            $array['created_at']    = $user->created_at;
                            return response()->json(['status'=>1,'role'=>'agent','user'=>$array],200);
                        }else{
                            return response()->json(['status'=>0,'error'=>'User Not Found'],404);
                        }
                    }
                }else{
                    return response()->json(['status'=>0,'error_type'=>1,'error'=>'User Not Found.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error_type'=>3,'error'=>'Wrong Username or Password.'],200);
            }
        }
    }
     // Update user
    public function updateUser(Request $req){
         $validator = Validator::make($req->all(), [
            'user_id'             => 'required',
            // 'email'               => 'required|email',
            'name'                => 'required',
            'phone'               => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>$validator->errors()], 404);
        }else{
            if($req->has('user_id')){
                $user = User::where('id',$req->user_id)->first();
                $user->name = $req->name;
                $user->email = $req->email;
                $user->phone = $req->phone;
                $user->floor_no     = $req->floor_no;
                $user->address      = $req->address;
                $user->locality     = $req->locality;
                $user->transport    = $req->transport;
                $user->city         = $req->city;
                $user->state        = $req->state;
                $user->country      = $req->country;
                $user->gst_number   = $req->gst_number;
                $user->save();

                if($req->has('categories')){
                    foreach($req->categories as $category){
                        $category1 = new DealerCategory();
                        $category1->user_id      = $user->id;
                        $category1->category_id  = $category;
                        $category1->save();
                    }
                }
                
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'User';
                $log->log       = 'User ('.$user->name .') Updated.';
                $log->save();

                $user_details = User::where('id',$req->user_id)->first();
                if(!blank($user_details)){
                    $array = array();
                    $array['id'] = $user_details->id;
                    if(!blank($user_details->image)){
                        $array['image'] = url('/').'/public/users/'.$user_details->image;
                    }else{
                        $array['image'] = '';
                    }
                    $agent = User::where('id',$user_details->agent)->first();
                    $array['name']          = $user_details->name;
                    $array['email']         = $user_details->email;
                    $array['phone']         = $user_details->phone;
                    $array['agent']         = $agent->name;
                    $array['locality']      = $user_details->locality;
                    $array['address']       = $user_details->address;
                    $array['floor_no']      = $user_details->floor_no;
                    $array['city']          = $user_details->city;
                    $array['state']         = $user_details->state;
                    $array['country']       = $user_details->country;
                    $array['gst_number']    = $user_details->gst_number;
                    $array['status']        = $user_details->status;
                    $array['transport']     = $user_details->transport;
                    $array['created_at']    = $user_details->created_at;
                     return response()->json(['status'=>1,'user'=>$array],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'not_found'],404);
                }
            }else{
                return response()->json(['status'=>0,'error_type'=>1,'error'=>'User Not Found.'],200);
            }
        }
        return response()->json(['status'=>0,'error_type'=>1,'error'=>'User Not Found.'],200);
    }
    public function changeOrderStatus(Request $req){
        $status = Order::where('id',$req->order_id)->first();
         if(!blank($status)){
            $status->status = $req->status;
            if($status->status == 2){
                $status->completed_date = now();
                $status->confirm_by = $req->user_id;
            }
            $status->save();
            if($req->status == 1){
                $order_status = 'Pending';
            }else{
                $order_status = 'Confirmed';
            }
            $usr = User::where('id',$req->user_id)->first();
            try{
                //send notification to Admin
                $userSchema = User::first();
                $details = [
                    'name'  => 'Order Status Changed.',
                    'type'  => 'Order',
                    'body'  => $status->order_id.' '.' status changed by '. $usr->name,
                    'url'   => route('admin.orders'),
                ];
                Notification::send($userSchema, new Notifications($details));
            }catch(\Exception $e){
    
            }
            $user = User::where('id',$req->user_id)->first();
            $log = new Log();
            $log->user_id   = $user->name;
            $log->module    = 'Orders';
            $log->log       = 'Order ('.$status->order_id.') status changed by '.$user->name;
            $log->save();
            
            return response()->json(['status'=>1,'message'=>'Order status change to '.$order_status.' successfully!'],200);
         }else{
              return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
         }
    }
    //Change Password

    public function changePassword(Request $req){
         $validator = Validator::make($req->all(), [
            'old_pswd'          => 'required',
            'new_pswd'          => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>$validator->errors()], 404);
        }else{
            if($req->has('user_id') && !blank($req->user_id)){
                $user = User::where('id',$req->user_id)->first();
                   if(!blank($user)){

                        if(!Hash::check($req->old_pswd,$user->password)){
                            return response()->json(['status'=>0,'error'=>'Old Password Doesnt match!'],200);
                        }

                        #Update the new Password
                        User::whereId($req->user_id)->update([
                            'password' => Hash::make($req->new_pswd)
                        ]);
                        $log = new Log();
                        $log->user_id   = $user->name;
                        $log->module    = 'Change Password';
                        $log->log       = $user->name.' password changed Successfully';
                        $log->save();
                        return response()->json(['status'=>1,'success'=>'Password Changed Successfully!'],200);
                   }else{
                     return response()->json(['status'=>0,'error'=>'User Not Found.'],200);
                   }
            }else{
                return response()->json(['status'=>0,'error'=>'User Not Found.'],200);
            }
        }
    }

    // forgot Password
    public function ForgetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email'               => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>$validator->errors()], 404);
        }else{

            $status = Password::sendResetLink($request->only('email'));
            if ($status == Password::RESET_LINK_SENT) {
                try{
                    \Mail::to($user->email)->send(new \App\Mail\forgotPasswordMail($details));
                }catch(\Exception $e){

                }
                 return response()->json(['status' => __($status)], 200);

            }else{
              return response()->json(['email' => __($status)], 404);
            }
        }
    }
    public function submitResetPasswordForm(Request $request){
        $request->validate([
          'email' => 'required|email|exists:users',
          'password' => 'required|string|min:6|confirmed',
          'password_confirmation' => 'required'
        ]);
        $updatePassword = DB::table('password_reset_tokens')
          ->where([
            'email' => $request->email,
            'token' => $request->token
          ])
          ->first();

        if(!$updatePassword){
            return back()->withInput()->with('message', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();
        $user = User::where('email',$request->email)->first();
        $log = new Log();
        $log->user_id   = $user->name;
        $log->module    = 'Password Reset';
        $log->log       = $user->name.' password reset Successfully';
        $log->save();
        try{
            $data = array('name'=>$user->name);
            Mail::send('mail', $data, function($message) {
                 $message->to($request->email, )->subject
                    ('Arcon Reset Password');
            });
        }catch(\Exception $e){

        }
        return redirect('/login')->with('message', 'Your password has been changed!');
    }
    public function deleteUser(Request $request, $id = NULL){
        if(!blank($id)){
            $user = User::where('id',$id)->first();
            if(!blank($user)){
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'User';
                $log->log       = ' User ('.$user->name .') Deleted.';
                $log->save();
                $user->delete();
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
            }
            return response()->json(['status'=>1,'message'=>'User deleted successfully!'],200);
        }else{
           return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
        }
    }
    public function addToCartItems(Request $req){
        foreach($req->items as $item){
             $cart = new CartItem();
             $cart->user_id         = $req->user_id;
             $cart->product_id      = $item['product_id'];
             $cart->variant_id      = $item['variant_id'];
             $cart->price           = $item['price'];
             $cart->quantity        = $item['quantity'];
             $cart->total           = $item['price']*$item['quantity'];
             $cart->save();
        }
        $user = User::where('id',$req->user_id)->first();
        $log = new Log();
        $log->user_id   = $user->name;
        $log->module    = 'Cart Item Added';
        $log->log       = 'Cart Item Added.';
        $log->save();
        return response()->json(['status'=>1],200);
    }
    public function getCartItems(Request $req, $id = NULL){
        if(!blank($id)){
            $array = array();
            $array['status'] = 1;
            $items = CartItem::where('user_id',$id)->get();
            if(!blank($items)){
                $amt = 0;
                foreach($items as $item){
                    $product = Product::where('id',$item->product_id)->first();
                    if(!blank($product)){
                        $variant = ProductVariant::where('id',$item->variant_id)->first();
                        $amt = $amt + $item->total;
                        $ary = array();
                        $ary['id'] = $item->id;
                        $ary['user_id'] = $item->user_id;
                        $ary['product_id'] = $item->product_id;
                        $ary['product_name'] = $product->name;
                        $ary['image'] = url('/').'/public/products/'.$product->image;
                        $images = ProductImage::where('product_id',$item->product_id)->get();
                        if(!blank($images)){
                            foreach($images as $item1){
                                $p_img = array();
                                $p_img['id'] = $item1->id;
                                $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                $ary['images'][] = $p_img;
                            }
                        }else{
                            $ary['images'] = [];
                        }
                        $ary['brand_name'] = $product->brand_name;
                        $ary['variant_id'] = $item->variant_id;
                        $ary['capacity'] = $variant->capacity ?? '';
                        $ary['variant_sku'] = $variant->sku ?? '';
                        $ary['variant_price'] = $variant->actual_price ?? '';
                        $ary['price'] = $item->price;
                        $ary['quantity'] = $item->quantity;
                        $ary['total'] = $item->total;
                        $array['items'][] = $ary;
                        $array['total'] = $amt;
                    }
                }
                return response()->json(['status'=>1,'cart_items'=>$array],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Cart Items Not Found.'],200);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Cart Items Not Found.'],200);
        }
    }
    public function getCartCount(Request $req, $id = NULL){
        if(!blank($id)){
            $array = array();
            $array['status'] = 1;
            $count = CartItem::where('user_id',$id)->count();
            return response()->json(['status'=>1,'count'=>$count],200);

        }else{
            return response()->json(['status'=>0,'error'=>'Cart Items Not Found.'],200);
        }
    }
    public function updateCartItems(Request $req){
            $cart = CartItem::where('id',$req->id)->first();
            if(!blank($cart)){
                //  $cart->user_id         = $req->user_id;
                //  $cart->product_id      = $item['product_id'];
                //  $cart->variant_id      = $item['variant_id'];
                //  $cart->price           = $item['price'];
                 $cart->quantity        = $req->quantity;
                 $cart->total           = $cart->price*$req->quantity;
                 $cart->save();
                $items = CartItem::where('user_id',$req->user_id)->get();
                if(!blank($items)){
                    $amt = 0;
                    foreach($items as $item){
                        $product = Product::where('id',$item->product_id)->first();
                        $variant = ProductVariant::where('id',$item->variant_id)->first();
                        $amt = $amt + $item->total;
                        $ary = array();
                        $ary['id'] = $item->id;
                        $ary['user_id'] = $item->user_id;
                        $ary['product_id'] = $item->product_id;
                        $ary['product_name'] = $product->name;
                        $ary['image'] = url('/').'/public/products/'.$product->image;
                        $images = ProductImage::where('product_id',$item->product_id)->get();
                        if(!blank($images)){
                            foreach($images as $item1){
                                $p_img = array();
                                $p_img['id'] = $item1->id;
                                $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                $ary['images'][] = $p_img;
                            }
                        }else{
                            $ary['images'] = [];
                        }
                        $ary['brand_name'] = $product->brand_name;
                        $ary['variant_id'] = $item->variant_id;
                        $ary['capacity'] = $variant->capacity;
                        $ary['variant_sku'] = $variant->sku;
                        $ary['variant_price'] = $variant->actual_price;
                        $ary['price'] = $item->price;
                        $ary['quantity'] = $item->quantity;
                        $ary['total'] = $item->total;
                        $array['items'][] = $ary;
                        $array['total'] = $amt;
                    }
                    return response()->json(['status'=>1,'cart_items'=>$array],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Cart Items Not Found.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Cart Item Not Found.'],200);
            }
    }
    public function deleteCartItem($id = NULL){
        if(!blank($id)){
            $item2 = CartItem::where('id',$id)->first();
            if(!blank($item2)){
                $user = User::where('id',$item2->user_id)->first();
                $cart = CartItem::where('id',$id)->delete();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Cart Item';
                $log->log       = 'Cart Item Deleted.';
                $log->save();
                $items = CartItem::where('user_id',$user->id)->get();
                if(!blank($items)){
                    $amt = 0;
                    foreach($items as $item){
                        $product = Product::where('id',$item->product_id)->first();
                        $variant = ProductVariant::where('id',$item->variant_id)->first();
                        $amt = $amt + $item->total;
                        $ary = array();
                        $ary['id'] = $item->id;
                        $ary['user_id'] = $item->user_id;
                        $ary['product_id'] = $item->product_id;
                        $ary['product_name'] = $product->name;
                        $ary['image'] = url('/').'/public/products/'.$product->image;
                        $images = ProductImage::where('product_id',$item->product_id)->get();
                        if(!blank($images)){
                            foreach($images as $item1){
                                $p_img = array();
                                $p_img['id'] = $item1->id;
                                $p_img['image'] = url('/').'/public/products/'.$item1->image;
                                $ary['images'][] = $p_img;
                            }
                        }else{
                            $ary['images'] = [];
                        }
                        $ary['brand_name'] = $product->brand_name;
                        $ary['variant_id'] = $item->variant_id;
                        $ary['capacity'] = $variant->capacity;
                        $ary['variant_sku'] = $variant->sku;
                        $ary['variant_price'] = $variant->actual_price;
                        $ary['price'] = $item->price;
                        $ary['quantity'] = $item->quantity;
                        $ary['total'] = $item->total;
                        $array['items'][] = $ary;
                        $array['total'] = $amt;
                    }
                    return response()->json(['status'=>1,'cart_items'=>$array],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Your Cart is successfully clear.'],200);
                }
                return response()->json(['status'=>1,'message'=>'Cart item has been Deleted.'],200);
            }else{
                return response()->json(['status'=>1,'message'=>'Cart Item Not Found.'],200);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Cart Item Not Found.'],200);
        }
    }
    public function getStatement(Request $req){
        if(isset($req->filter_type)){
            if($req->filter_type == 1){
                $orders = Order::where('status','!=',1)->whereDate('created_at',Carbon::today())->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = 'India';
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
                            $ary['country']         = 'India';
                            $ary['created_at']      = $order->created_at;
                            $ary['trasport']        = $order->transport;
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
                                        $ary_item['images'] = [];
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
                    // $view = (string)View::make('statement.statement',array('statement'=>$statement));
                    return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 2){
                $date = \Carbon\Carbon::today()->subDays(7);
                $orders = Order::where('status','!=',1)->where('created_at','>=',$date)->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = 'India';
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
                            $ary['country']         = 'India';
                            $ary['created_at']      = $order->created_at;
                            $ary['trasport']        = $order->transport;
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
                                        $ary_item['images'] = [];
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
                    return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 3){
                 $orders = Order::where('status','!=',1)->where('created_at','>=',now()->subDays(30)->endOfDay())->where('user_id',$req->user_id)->get();
                // $orders = Order::where('status',2)->whereMonth('created_at', now()->subDays(30)->endOfDay())->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = 'India';
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
                            $ary['country']         = 'India';
                            $ary['created_at']      = $order->created_at;
                            $ary['trasport']        = $order->transport;
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
                                        $ary_item['images'] = [];
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
                    return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 4){
                $orders = Order::where('status','!=',1)->whereYear('created_at', date('Y'))->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = 'India';
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
                            $ary['country']         = 'India';
                            $ary['created_at']      = $order->created_at;
                            $ary['trasport']        = $order->transport;
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
                                        $ary_item['images'] = [];
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
                    return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Please Select Filter Type.'],200);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Records Not Found.'],200);
        }
    }
    public function viewStatement(Request $req){
        if(isset($req->filter_type) && isset($req->user_id)){
            $user = User::where('id',$req->user_id)->first();
            if(!blank($user)){
                $url = url('/').'/view/statement?filter_type='.$req->filter_type.'&user_id='.$req->user_id.'';
                return response()->json(['status'=>1,'statement'=>$url],200);
            }else{
              return response()->json(['status'=>0,'error'=>'Records Not Found.'],200);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Records Not Found.'],200);
        }
    }
    public function downloadStatement(Request $req){
        if(isset($req->filter_type)){
            if($req->filter_type == 1){
                // if(!blank($req->start_date) && !blank($req->end_date)){
                    $orders = Order::where('status','!=',1)->whereDate('created_at',Carbon::today())->where('user_id',$req->user_id)->get();
                    $user = User::where('id',$req->user_id)->first();
                    if(!blank($user)){
                        $agent = User::where('id',$user->agent)->first();
                        $statement = array();
                        $statement['name']      = $user->name;
                        $statement['phone']     = $user->phone;
                        $statement['email']     = $user->email;
                        $statement['agent']     = $agent->name;
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
                                $ary['total']           = $order->total;
                                $ary['created_at']      = $order->created_at;
                                $items = OrderItem::where('id',$order->id)->get();
                                if(!blank($items) && !empty($items) && count($items) > 0){
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
                                            $ary_item['images'] = [];
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
                        $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                        // return view('invoice/order_invoice',compact('order','order_items'));
                       return $pdf->download('statement-'.now().'.pdf');
                        // return response()->json(['status'=>1,'statement'=>$statement],200);
                    }else{
                        return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                    }
                // }else{
                //     return response()->json(['status'=>0,'error'=>'Please Select Date.'],200);
                // }
            }elseif($req->filter_type == 2){
                $date = \Carbon\Carbon::today()->subDays(7);
                $orders = Order::where('status','!=',1)->where('created_at','>=',$date)->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['agent']     = $agent->name;
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
                            $ary['total']           = $order->total;
                            $ary['created_at']      = $order->created_at;
                            $items = OrderItem::where('id',$order->id)->get();
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
                                        $ary_item['images'] = [];
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
                    $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                    // return view('invoice/order_invoice',compact('order','order_items'));
                    return $pdf->download('statement-'.now().'.pdf');
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 3){
                
                $orders = Order::where('status','!=',1)->where('created_at','>=',now()->subDays(30)->endOfDay())->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['agent']     = $agent->name;
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
                            $ary['total']           = $order->total;
                            $ary['created_at']      = $order->created_at;

                            $items = OrderItem::where('id',$order->id)->get();
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
                                        $ary_item['images'] = [];
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
                    $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                    // return view('invoice/order_invoice',compact('order','order_items'));
                    return $pdf->download('statement-'.now().'.pdf');
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 4){
                $orders = Order::where('status','!=',1)->whereYear('created_at', date('Y'))->where('user_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['transport'] = $user->transport;
                    $statement['country']   = $user->country;
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
                            $ary['total']           = $order->total;
                            $ary['created_at']      = $order->created_at;

                            $items = OrderItem::where('id',$order->id)->get();
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
                                        $ary_item['images'] = [];
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
                    $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                    // return view('invoice/order_invoice',compact('order','order_items'));
                    return $pdf->download('statement-'.now().'.pdf');
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Please Select Filter Type.'],200);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Records Not Found.'],200);
        }
    }

    public function cf7_data(Request $request){
        $inquiry = new Inquiry;
        $inquiry->name              = $request->name;
        $inquiry->quantity          = $request->quantity;
        $inquiry->product_name      = $request->product_name;
        $inquiry->phone             = $request->phone;
        $inquiry->message           = $request->message;
        $inquiry->save();

        $userSchema = User::first();
        $details = [
            'name'  => 'Product Inquiry Created.',
            'type'  => 'Product Inquiry',
            'body'  => $request->name.' '.'Created.',
            'url'   => route('inquiry',$inquiry->id),
        ];
        Notification::send($userSchema, new Notifications($details));
        return 1;
    }

    // Agent Dealers
    public function agentData(Request $req, $id = NULL){
        $order_count = Order::where('agent_id',$id)->count();
        $dealer_count = User::where('agent',$id)->count();
        $orders = Order::
             select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'), DB::raw('count(*) as total'))
             ->groupByRaw(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
             ->where('agent_id',$id)
             ->get();
        $charts = [];
        $i = 0;
        foreach($orders as $order){
            $i++;
            $chart['x'] = $order->date;
            $chart['y'] = $order->total;
            $charts[] = $chart;
            $chart1_labels[] = $order->date;
        }
        foreach($orders as $order){
            $chart1_data[] = $order->total;
        }
        // $chart1_labels = json_encode($chart1_labels);
        // $chart1_data = json_encode($chart1_data);
        $visitors = OrderItem::
            select('e1.name',
                    DB::raw('count(DISTINCT o1.id) as total_orders'))
            ->join('orders as o1','order_items.order_id','=','o1.id')
            ->join('products as e1','order_items.product_id','=','e1.id')
            ->join('product_variants as e3','order_items.variant_id','=','e3.id')
            // ->whereDate('o1.created_at', Carbon::today())
            ->where('o1.agent_id',$id)
            ->groupBy('e1.name')
            ->get();

        $result = [];
        foreach ($visitors as $key => $value) {
            $ord['x'] = $value->name;
            $ord['y'] = (int)$value->total_orders;
            $result[] = $ord;
            // $result[++$key] = [$value->name, (int)$value->total_orders];
        }
        
        $revenues = OrderItem::
            select('e1.name',
                    DB::raw('sum(order_items.total) as sum'))
            ->join('orders as o1','order_items.order_id','=','o1.id')
            ->join('products as e1','order_items.product_id','=','e1.id')
            ->join('product_variants as e3','order_items.variant_id','=','e3.id')
            // ->whereDate('o1.created_at', Carbon::today())
            ->where('o1.agent_id',$id)
            ->groupBy('e1.name')
            ->get();

            $revenue = [];
            foreach ($revenues as $key => $value) {
                $rev['x'] = $value->name;
                $rev['y'] = (int)$value->sum;
                $revenue[] = $rev;
                // $revenue[++$key] = [$value->name, (int)$value->sum];
            }
            
        $array = array();
        $array['order_count'] = $order_count;
        $array['dealer_count'] = $dealer_count;
        // $array['line_chart_labels'] = $chart1_labels;
        // $array['line_chart_data'] = $chart1_data;
        $array['line_chart_data'] = $charts;
        $array['orders_by_product_chart'] = $result;
        $array['revenue_by_product_chart'] = $revenue;
        return response()->json(['status'=>1,'data'=>$array],200);
    }
    public function agentDealers(Request $req, $id = NULL){
        if(!blank($id)){
            $dealers = User::where('role',2)->where('agent',$id)->orderBy('id', 'desc')->get();
            $array_push = array();
            if(!blank($dealers)){
                foreach($dealers as $user){
                    $array = array();
                    $array['id'] = $user->id;
                    if(!blank($user->image)){
                        $array['image'] = url('/').'/public/users/'.$user->image;
                    }else{
                        $array['image'] = '';
                    }
                    $agent = User::where('id',$user->agent)->first();
                    $array['name']          = $user->name;
                    $array['email']         = $user->email;
                    $array['phone']         = $user->phone;
                    $array['agent']         = $agent->name;
                    $array['transport']     = $user->transport;
                    $array['locality']      = $user->locality;
                    $array['address']       = $user->address;
                    $array['floor_no']      = $user->floor_no;
                    $array['city']          = ($user->city == 0)?"":$user->city;
                    $array['state']         = ($user->state == 0)?"":$user->state;
                    $array['country']       = $user->country;
                    $array['gst_number']    = $user->gst_number;
                    $array['status']        = $user->status;
                    $array['agent_status']  = $user->agent_status;
                    $array['created_at']    = $user->created_at;
                    array_push($array_push,$array);
                }
                return response()->json([
                    'status' => 1,
                    'dealers'=>$array_push
                ],200);
            }else{
                return response()->json(['status'=>0,'error'=> 'Dealers Not Found.'],404);
            }
        }else{
            return response()->json(['status'=>0,'error'=> 'Something went wrong.'],404);
        }
    }
    public function agentOrders(Request $req, $id = NULL){
        if(!blank($id)){
            $orders = Order::where('agent_id',$id)->orderBy('id','desc')->get();
            if(!blank($orders)){
                $array_push = array();
                foreach($orders as $order){
                    $array = array();
                    $array['id'] = $order->id;
                    $array['order_id'] = $order->order_id;
                    $array['customer_name'] = $order->customer_name;
                    $array['email'] = $order->email;
                    $array['phone'] = $order->phone;
                    $array['floor_no'] = $order->floor_no;
                    $array['address'] = $order->address;
                    $array['locality'] = $order->locality;
                    $array['city']  = $order->city;
                    $array['state']  = $order->state;
                    $array['country']  = "India";
                    $array['total']  = $order->total;
                    $array['status']  = $order->status;
                    $array['transport'] = $order->transport;
                    $array['created_at'] = $order->created_at;
                    $array['completed_date'] = $order->completed_date;
                    if(!blank($order->lr_copy)){
                        $array['lr_copy'] = url('/').'/public/lr_copy/'.$order->lr_copy;
                    }else{
                        $array['lr_copy'] = null;
                    }
                    $array['invoice_url'] = route('order.invoice',$order->id);
                    $order_items = OrderItem::where('order_id',$order->id)->get();
                    foreach($order_items as $o_items){
                        $items = array();
                        $p_name = Product::where('id',$o_items->product_id)->first();
                        $v_name = ProductVariant::where('id',$o_items->variant_id)->first();
                        if ($v_name !== null) {
                        $items['product_name']      = $p_name->name;
                        $items['product_image']     = url('/').'/public/products/'.$p_name->image;
                        $items['brand_name']        = $p_name->brand_name;
                        $items['varinat_sku']       = $v_name->sku;
                        $items['varinat_capacity']  = $v_name->capacity;
                        $items['price']             = $o_items->price;
                        $items['quantity']          = $o_items->quantity;
                        $items['status']            = $o_items->status;
                        $array['order_items'][]     = $items;
                    
                        }    
                    }
                    array_push($array_push,$array);
                }
                return response()->json(['status'=>1,'orders'=>$array_push],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Orders Not Found'],404);
            }
        }else{
           return response()->json(['status'=>0,'error'=>'Orders Not Found'],404);
        }
    }
    public function verifyAgentDealer(Request $req){
        $status = User::where('id',$req->user_id)->first();
        if(!blank($status)){
            $status->agent_status = $req->status;
            $status->save();
            if($req->status == 1){
                $user_status = 'Verified';
            }else{
                $user_status = 'Deverified';
            }
            try{
                //send notification to Admin
                $agent = User::where('id',$status['agent'])->first();
                $userSchema = User::first();
                $details = [
                    'name'  => 'Dealer Verified.',
                    'type'  => 'Dealer',
                    'body'  => $status['name'].' '.$user_status.' by '. $agent['name'],
                    'url'   => route('admin.users'),
                ];
                Notification::send($userSchema, new Notifications($details));
            }catch(\Exception $e){
            }
            $agent = User::where('id',$status['agent'])->first();
            $log = new Log();
            $log->user_id   = $agent['name'];
            $log->module    = 'Dealer';
            $log->log       = 'User status ('.$user_status.') changed by Agent '.$agent['name'];
            $log->save();
            return response()->json(['status'=>1,'message'=>'User '.$user_status.' successfully!'],200);
        }else{
            return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
        }
    }
    public function getAgentStatement(Request $req){
        if(isset($req->filter_type)){
            if($req->filter_type == 1){
                $orders = Order::where('status','!=',1)->whereDate('created_at',Carbon::today())->where('agent_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    // $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    // $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = 'India';
                    $statement['headquarter'] = $user->headquarter;
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
                            $ary['country']         = 'India';
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
                                        $ary_item['images'] = [];
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
                    // $view = (string)View::make('statement.statement',array('statement'=>$statement));
                    return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 2){
                $date = \Carbon\Carbon::today()->subDays(7);
                $orders = Order::where('status','!=',1)->where('created_at','>=',$date)->where('agent_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    // $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    // $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = 'India';
                    $statement['headquarter'] = $user->headquarter;
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
                            $ary['country']         = 'India';
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
                                        $ary_item['images'] = [];
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
                    return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 3){
                $orders = Order::where('status','!=',1)->whereMonth('created_at',Carbon::now()->month)->where('agent_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    // $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    // $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = 'India';
                    $statement['headquarter'] = $user->headquarter;
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
                            $ary['country']         = 'India';
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
                                        $ary_item['images'] = [];
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
                    return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 4){
                $orders = Order::where('status','!=',1)->whereYear('created_at', date('Y'))->where('agent_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    // $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    // $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = 'India';
                    $statement['headquarter'] = $user->headquarter;
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
                            $ary['country']         = 'India';
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
                                        $ary_item['images'] = [];
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
                    return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Please Select Filter Type.'],200);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Records Not Found.'],200);
        }
    }
    public function viewAgentStatement(Request $req){
        if(isset($req->filter_type) && isset($req->user_id)){
            $user = User::where('id',$req->user_id)->first();
            if(!blank($user)){
                $url = url('/').'/view/agent/statement?filter_type='.$req->filter_type.'&user_id='.$req->user_id.'';
                return response()->json(['status'=>1,'statement'=>$url],200);
            }else{
              return response()->json(['status'=>0,'error'=>'Records Not Found.'],200);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Records Not Found.'],200);
        }
    }
    public function downloadAgentStatement(Request $req){
        if(isset($req->filter_type)){
            if($req->filter_type == 1){
                // if(!blank($req->start_date) && !blank($req->end_date)){
                    $orders = Order::where('status','!=',1)->whereDate('created_at',Carbon::today())->where('agent_id',$req->user_id)->get();
                    $user = User::where('id',$req->user_id)->first();
                    if(!blank($user)){
                        // $agent = User::where('id',$user->agent)->first();
                        $statement = array();
                        $statement['name']      = $user->name;
                        $statement['role']      = $user->role;
                        $statement['phone']     = $user->phone;
                        $statement['email']     = $user->email;
                        // $statement['agent']     = $agent->name;
                        $statement['floor_no']  = $user->floor_no;
                        $statement['address']   = $user->address;
                        $statement['locality']  = $user->locality;
                        $statement['city']      = $user->city;
                        $statement['state']     = $user->state;
                        $statement['country']   = $user->country;
                        $statement['headquarter'] = $user->headquarter;
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
                                $ary['country']         = 'India';
                                $ary['total']           = $order->total;
                                $ary['created_at']      = $order->created_at;
                                $items = OrderItem::where('id',$order->id)->get();
                                if(!blank($items) && !empty($items) && count($items) > 0){
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
                                            $ary_item['images'] = [];
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
                        $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                        // return view('invoice/order_invoice',compact('order','order_items'));
                       return $pdf->download('statement-'.now().'.pdf');
                        // return response()->json(['status'=>1,'statement'=>$statement],200);
                    }else{
                        return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                    }
                // }else{
                //     return response()->json(['status'=>0,'error'=>'Please Select Date.'],200);
                // }
            }elseif($req->filter_type == 2){
                $date = \Carbon\Carbon::today()->subDays(7);
                $orders = Order::where('status','!=',1)->where('created_at','>=',$date)->where('agent_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    // $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['role']      = $user->role;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    // $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['headquarter'] = $user->headquarter;
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
                            $ary['country']         = 'India';
                            $ary['total']           = $order->total;
                            $ary['created_at']      = $order->created_at;
                            $items = OrderItem::where('id',$order->id)->get();
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
                                        $ary_item['images'] = [];
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
                    $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                    // return view('invoice/order_invoice',compact('order','order_items'));
                    return $pdf->download('statement-'.now().'.pdf');
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 3){
                $orders = Order::where('status','!=',1)->whereMonth('created_at',Carbon::now()->month)->where('agent_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    // $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['role']      = $user->role;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    // $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['country']   = $user->country;
                    $statement['headquarter'] = $user->headquarter;
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
                            $ary['country']         = 'India';
                            $ary['total']           = $order->total;
                            $ary['created_at']      = $order->created_at;

                            $items = OrderItem::where('id',$order->id)->get();
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
                                        $ary_item['images'] = [];
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
                    $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                    // return view('invoice/order_invoice',compact('order','order_items'));
                    return $pdf->download('statement-'.now().'.pdf');
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }elseif($req->filter_type == 4){
                $orders = Order::where('status','!=',1)->whereYear('created_at', date('Y'))->where('agent_id',$req->user_id)->get();
                $user = User::where('id',$req->user_id)->first();
                if(!blank($user)){
                    // $agent = User::where('id',$user->agent)->first();
                    $statement = array();
                    $statement['name']      = $user->name;
                    $statement['role']      = $user->role;
                    $statement['phone']     = $user->phone;
                    $statement['email']     = $user->email;
                    // $statement['agent']     = $agent->name;
                    $statement['floor_no']  = $user->floor_no;
                    $statement['address']   = $user->address;
                    $statement['locality']  = $user->locality;
                    $statement['city']      = $user->city;
                    $statement['state']     = $user->state;
                    $statement['headquarter'] = $user->headquarter;
                    // $statement['transport'] = $user->transport;
                    $statement['country']   = 'India';
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
                            $ary['total']           = $order->total;
                            $ary['created_at']      = $order->created_at;

                            $items = OrderItem::where('id',$order->id)->get();
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
                                        $ary_item['images'] = [];
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
                    $pdf = PDF::loadView('statement/statement',array('statement'=>$statement))->setOption('defaultFont', 'Satoshi Regular');
                    // return view('invoice/order_invoice',compact('order','order_items'));
                    return $pdf->download('statement-'.now().'.pdf');
                    // return response()->json(['status'=>1,'statement'=>$statement],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Please Select Filter Type.'],200);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Records Not Found.'],200);
        }
    }
    public function updateAgent(Request $req){
         $validator = Validator::make($req->all(), [
            'user_id'             => 'required',
            'email'               => 'required|email',
            'name'                => 'required',
            'phone'               => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>$validator->errors()], 404);
        }else{
            if($req->has('user_id')){
                $user = User::where('id',$req->user_id)->first();
                $user->name             = $req->name;
                $user->email            = $req->email;
                $user->phone            = $req->phone;
                $user->headquarter      = $req->headquarter;
                $user->save();

                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'User';
                $log->log       = 'User ('.$user->name .') Updated.';
                $log->save();

                $user_details = User::where('id',$req->user_id)->first();
                if(!blank($user_details)){
                    $array = array();
                    $array['id'] = $user_details->id;
                    if(!blank($user_details->image)){
                        $array['image'] = url('/').'/public/users/'.$user_details->image;
                    }else{
                        $array['image'] = '';
                    }
                    $agent = User::where('id',$user_details->agent)->first();
                    $array['name']          = $user_details->name;
                    $array['email']         = $user_details->email;
                    $array['phone']         = $user_details->phone;
                    $array['headquarter']   = $user_details->headquarter;
                    $array['status']        = $user_details->status;
                    $array['created_at']    = $user_details->created_at;
                     return response()->json(['status'=>1,'role'=>'agent','user'=>$array],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'not_found'],404);
                }
            }else{
                return response()->json(['status'=>0,'error_type'=>1,'error'=>'User Not Found.'],200);
            }
        }
        return response()->json(['status'=>0,'error_type'=>1,'error'=>'User Not Found.'],200);
    }
}
