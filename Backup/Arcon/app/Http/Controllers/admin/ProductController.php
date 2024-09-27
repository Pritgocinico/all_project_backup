<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\Notifications;

class ProductController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        $notification = $user->unreadNotifications;
        view()->share('notifications', $notification);
        view()->share('setting', $setting);
    }
    public function products(){
        $products = Product::orderBy('id','Desc')->where('active',1)->get();
        $categories = Category::all();
        $parent_categories = Category::where('parent','==',0)->orderBy('id','Desc')->get();
        $page  = 'Products';
        $icon  = 'products.png';
        if(Auth::user()->role == 1){
            return view('admin.products.products',compact('products','categories','parent_categories','page','icon'));
        }elseif(Auth::user()->role == 3){
            return view('agent.products.products',compact('products','categories','parent_categories','page','icon'));
        }else{
            return redirect()->route('login');
        }
    }
    public function addProduct(){
        $categories = Category::all();
        $parent_categories = Category::where('parent','==',0)->get();
        $page  = 'Products';
        $icon  = 'products.png';
        if(Auth::user()->role == 1){
            return view('admin.products.add_product',compact('categories','parent_categories','page','icon'));
        }else{
            return redirect()->route('login');
        }
    }
    public function addProductData(Request $req){
        // echo '<pre>';
        // print_r($req->all());
        // exit;
        $req->validate([
            'name'                => 'required',
            'sku'                 => 'required',
            'brand_name'          => 'required',
            'category'            => 'required|not_in:0'
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        if($req->has('image') && $req->file('image') != null){
            $image = $req->file('image');
            $destinationPath = 'public/products/';
            $rand=rand(1,100);
            $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $docImage);
            $img=$docImage;
        }else{
            $img=$req->hidden_image;
        }

        $product = new Product();
        $product->name          = $req->name;
        $product->sku           = $req->sku;
        $product->brand_name    = $req->brand_name;
        $product->image         = $img;
        $product->category      = $req->category;
        $product->description   = $req->description;
        $product->cgst          = $req->cgst;
        $product->sgst          = $req->sgst;
        $product->status        = 1;
        $insert_product         = $product->save();

        try{
            //send notification to Admin
            $userSchema = User::first();
            $details = [
                'name'  => 'Product Created.',
                'type'  => 'Product',
                'body'  => $req->name.' '.'created.',
                'url'   => route('admin.products'),
            ];
            // Notification::send($userSchema, new Notifications($details));
        }catch(\Exception $e){

        }
        if($insert_product){
            if($req->has('slider')){
                foreach($req->slider as $slider){
                    $s_image = $slider;
                    $destinationPath = 'public/products/';
                    $rand=rand(1,100);
                    $docImage = date('YmdHis').$rand. "." . $s_image->getClientOriginalExtension();
                    $s_image->move($destinationPath, $docImage);
                    $img=$docImage;
                    $slider = new ProductImage();
                    $slider->product_id = $product->id;
                    $slider->image = $img;
                    $slider->save();
                }
            }
            if(!empty($req->v_sku)){
                $sku        = $req->v_sku;
                $capacity   = $req->capacity;
                $price      = $req->price;
                $qty        = $req->qty;
                // $stock      = $req->stock;
                $actual_price   = $req->actual_price;
                foreach($sku as $key=>$value){
                    foreach($capacity as $key1=>$val1){
                        if($key==$key1){
                            $v_capacity=$val1;
                        }
                    }
                    foreach($price as $key3=>$val3){
                        if($key==$key3){
                            $v_price=$val3;
                        }
                    }
                    foreach($qty as $key8=>$val8){
                        if($key==$key8){
                            $v_qty=$val8;
                        }
                    }
                    // foreach($stock as $key4=>$val4){
                    //     if($key==$key4){
                    //         $v_stock=$val4;
                    //     }
                    // }
                    foreach($actual_price as $key12=>$val12){
                        if($key==$key12){
                            $v_actual_price=$val12;
                        }
                    }
                   
                    $variant = new ProductVariant;
                    $variant->product_id    = $product->id;
                    $variant->sku           = $value;
                    $variant->capacity      = $v_capacity;
                    $variant->price         = $v_price;
                    $variant->quantity      = $v_qty;
                    // $variant->stock         = $v_stock;
                    $variant->actual_price  = $v_actual_price;
                     if(isset($req->selected)){
                        foreach($req->selected as $key102=>$val123){
                            if($key==$key102){
                                $variant->selected      = 1;
                            }
                        }
                    }
                    $variant->created_by    = Auth::user()->id;
                    $variant->save();
                }
            }
            $user = User::where('id',Auth::user()->id)->first();
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Products';
            $log->log       = ' Product ('.$req->name .') created by '.$user->name;
            $log->save();
            if(Auth::user()->role == 1){
                return redirect()->route('admin.products');
            }elseif(Auth::user()->role == 3){
                return redirect()->route('agent.products');
            }else{
                return redirect()->route('login');
            }
        }else{
            Session::flash('alert','Something Went Wrong.');
            if(Auth::user()->role == 1){
                return redirect()->route('admin.add_product');
            }else{
                return redirect()->route('login');
            }
        }
    }
    public function editProduct($id)
    {
        $categories         = Category::all();
        $parent_categories  = Category::where('parent','==',0)->get();
        $product            = Product::where('id',$id)->first();
        $variants           = ProductVariant::where('product_id',$id)->get();
        $images             = ProductImage::where('product_id',$id)->get();
        $page               = 'Products';
        $icon               = 'products.png';
        if(Auth::user()->role == 1){
            return view('admin/products/edit_product',compact('page','icon','product','categories','parent_categories','variants','images'));
        }else{
            return redirect()->route('login');
        }
    }
    public function updateProduct(Request $req){
        // echo '<pre>';
        // print_r($req->all());
        // exit;
        $req->validate([
            'name'                => 'required',
            'sku'                 => 'required',
            'brand_name'          => 'required',
            'category'            => 'required|not_in:0'
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        if($req->has('image') && $req->file('image') != null){
            $image = $req->file('image');
            $destinationPath = 'public/products/';
            $rand=rand(1,100);
            $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $docImage);
            $img=$docImage;
        }else{
            $img=$req->hidden_image;
        }
        $product = Product::where('id',$req->id)->first();
        $product->name          = $req->name;
        $product->sku           = $req->sku;
        $product->brand_name    = $req->brand_name;
        $product->image         = $img;
        $product->category      = $req->category;
        $product->description   = $req->description;
        $product->cgst          = $req->cgst;
        $product->sgst          = $req->sgst;
        $product->status        = $status;
        $insert_product         = $product->save();

        if($insert_product){
            if($req->has('slider')){
                foreach($req->slider as $slider){
                    $s_image = $slider;
                    $destinationPath = 'public/products/';
                    $rand=rand(1,100);
                    $docImage = date('YmdHis').$rand. "." . $s_image->getClientOriginalExtension();
                    $s_image->move($destinationPath, $docImage);
                    $img=$docImage;
                    $slider = new ProductImage();
                    $slider->product_id = $product->id;
                    $slider->image = $img;
                    $slider->save();
                }
            }
            if(!empty($req->v_sku)){
                $p_variant = ProductVariant::where('product_id',$req->id)->get();
                $sku            = $req->v_sku;
                $capacity       = $req->capacity;
                $qty            = $req->qty;
                $price          = $req->price;
                $actual_price   = $req->actual_price;
                // $stock          = $req->stock;
                $array = array();
                foreach($sku as $key=>$value){
                    $ar = array();
                    $ar['sku']=$value;
                    foreach($capacity as $key1=>$val1){
                        if($key==$key1){
                            $ar['capacity']=$val1;
                        }
                    }
                    foreach($qty as $key8=>$val8){
                        if($key==$key8){
                            $ar['quantity']=$val8;
                        }
                    }
                    foreach($price as $key3=>$val3){
                        if($key==$key3){
                            $ar['price']=$val3;
                        }
                    }
                    // foreach($stock as $key4=>$val4){
                    //     if($key==$key4){
                    //         $ar['stock']=$val4;
                    //     }
                    // }
                    foreach($actual_price as $key12=>$val12){
                        if($key==$key12){
                            $ar['actual_price']=$val12;
                        }
                    }
                    if(isset($req->variant_id)){
                        foreach($req->variant_id as $key101=>$val11){
                            if($key==$key101){
                                $ar['variant_id']=$val11;
                            }
                        }
                    }
                    $ar['selected'] = 0;
                    if(isset($req->selected)){
                        foreach($req->selected as $key102=>$val12){
                            if($key==$key102){
                                $ar['selected']= 1;
                            }else{
                                $ar['selected'] = 0;
                            }
                        }
                    }
                    array_push($array,$ar);
                }
                // echo '<pre>';
                // print_r($array);
                foreach($array as $key_array=>$value_array){
                    $var = 0;
                    if (array_key_exists('variant_id',$value_array)){
                        if(!blank($p_variant)){
                            foreach($p_variant as $variant){
                                if($variant->id == $value_array['variant_id']){
                                    $variant_id1 = $value_array['variant_id'];
                                    $product_id = $product->id;
                                    $sku1 = $value_array['sku'];
                                    $price1 = $value_array['price'];
                                    $qty1 = $value_array['quantity'];
                                    $capacity1 = $value_array['capacity'];
                                    // $stock1 = $value_array['stock'];
                                    $actual_price1 = $value_array['actual_price'];

                                    $variant1 = ProductVariant::where('id',$variant_id1)->first();
                                    $variant1->product_id    = $product->id;
                                    $variant1->sku           = $sku1;
                                    $variant1->capacity      = $capacity1;
                                    $variant1->price         = $price1;
                                    $variant1->quantity      = $qty1;
                                    // $variant1->stock         = $stock1;
                                    $variant1->actual_price  = $actual_price1;
                                    $variant1->selected      = $value_array['selected'];
                                    $variant1->save();
                                    $var++;
                                }
                            }
                        }
                    }else{
                        $product_id = $product->id;
                        $sku1 = $value_array['sku'];
                        $price1 = $value_array['price'];
                        $qty1 = $value_array['quantity'];
                        $capacity1 = $value_array['capacity'];
                        // $stock1 = $value_array['stock'];
                        $actual_price1 = $value_array['actual_price'];
                        $variant1 = new ProductVariant();
                        $variant1->product_id    = $product->id;
                        $variant1->sku           = $sku1;
                        $variant1->capacity      = $capacity1;
                        $variant1->price         = $price1;
                        $variant1->quantity      = $qty1;
                        // $variant1->stock         = $stock1;
                        $variant1->actual_price  = $actual_price1;
                        $variant1->selected      = $value_array['selected'];
                        $variant1->save();
                    }
                }
            }
            $user = User::where('id',Auth::user()->id)->first();
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Products';
            $log->log       = ' Product ('.$req->name .') Updated by '.$user->name;
            $log->save();
        }
        if(Auth::user()->role == 1){
            return redirect()->route('admin.products');
        }else{
            return redirect()->route('login');
        }
    }

    public function deleteProduct($id){
        $product = Product::where('id',$id)->first();
        $product->active = 0;
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Products';
        $log->log       = 'Product ('.$product->name .') Deleted by '.$user1->name;
        $log->save();
        // $variant = ProductVariant::where('product_id',$product->id)->delete();
        // $images = ProductImage::where('product_id',$product->id)->delete();
        $product->save();
        return 1;
    }
    public function deleteProductVariant($id){
        $variant = ProductVariant::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Product Variant';
        $log->log       = 'Product Variant('.$variant->sku.') Deleted by '.$user1->name;
        $log->save();
        $variant->delete();
        return 1;
    }
    public function deleteProductImage($id){
        $image = ProductImage::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Product Image';
        $log->log       = 'Product Image Deleted by '.$user1->name;
        $log->save();
        $image->delete();
        return redirect()->back();
    }
    public function getCatProducts(Request $req){
        $id = $req->id;
        $products = Product::where('category',$id)->where('status',1)->get();
        $html = '';
        $html .= '<option value="0">Select Product...</option>';
        foreach($products as $product)
        {
            $html .= '<option value="'.$product->id.'">['.$product->sku.'] '.$product->name.'</option>';
        }
        return $html;
    }
    public function getProductVariants(Request $req){
        $id = $req->id;
        $variants = ProductVariant::where('product_id',$id)->get();
        $html = '';
        $html .= '<option value="0">Select Variant...</option>';
        foreach($variants as $variant)
        {
            $html .= '<option value="'.$variant->id.'"> ['.$variant->sku.'] '.$variant->capacity.'</option>';
        }
        return $html;
    }
    public function getVariant(Request $req){
        $id = $req->id;
        $variants = ProductVariant::where('id',$id)->first();
        return $variants;
    }
    public function inStock(Request $req){
        $categories         = Category::all();
        $parent_categories  = Category::where('parent','==',0)->get();
        $product            = Product::all();
        $variants = ProductVariant::where('stock','>',0)->get();
        $page  = 'In Stock';
        $icon  = 'products.png';
        if(Auth::user()->role == 1){
            return view('admin/stocks/stocks',compact('page','icon','product','categories','parent_categories','variants'));
        }else{
            return redirect()->route('login');
        }
    }

}
