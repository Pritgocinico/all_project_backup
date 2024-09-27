<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Product;
use App\Models\DoctorPrice;
use App\Models\ProductPackage;
use App\Models\CartItem;

class CartController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function cart(){
        $cart_items = CartItem::where('user_id',Auth::guard('web')->user()->id)->get();
        $products = Product::all();
        return view('frontend.pages.cart',compact('cart_items','products'));
    }

    public function addToCart(Request $request,$id = NULL){
        // print_r($request->all());
        // exit;
        if(!blank($id)){
            $product_package = ProductPackage::where('id',$request->package_price)->first();
            $item = CartItem::where('product_id',$id)->where('user_id',$request->doctor_id)->where('package_name',$product_package->varient_name)->first();
            $product = Product::where('id', $id)->first();
            if(blank($item)){
                if($product_package->vial_quantity > $product->stock){
                    // dd($product_package->vial_quantity, $product->stock);
                    return redirect()->back()->withErrors(['msg' => 'Your selected order quantity is greater than our existing stock. Please expect a delay of up to 2 weeks for our stock to be replenished.']);
                }
                $priceWithCurrency = $request->price;
                $priceWithoutCurrency = (float)str_replace('$', '', $priceWithCurrency);
                $cart_item = new CartItem();
                $cart_item->user_id = Auth()->guard('web')->user()->id;
                $cart_item->product_id = $id;
                // $cart_item->quantity = $request->qty;
                $cart_item->quantity = 1;
                $cart_item->price = $product_package->vial_quantity;
                // $cart_item->total = $product_package->vial_total;
                $cart_item->total = $priceWithoutCurrency;
                $cart_item->package_name    = $product_package->varient_name;
                $cart_item->package_qty     = $product_package->vial_quantity;
                $cart_item->package_price   = $product_package->vial_price;
                // $cart_item->package_total   = $product_package->vial_total;
                $cart_item->package_total   = $priceWithoutCurrency;
                $cart_item->medical_necessity   = $request->medical_necessity;
                if($request->medical_necessity == "other"){
                    $cart_item->medical_necessity   = $request->other_medical_necessity;
                }
                $cart_item->save();
            }else{
                $cart_item = CartItem::where('product_id',$id)->where('user_id',$request->doctor_id)->where('package_name',$product_package->varient_name)->first();
                $qty = $cart_item->quantity + 1;
                $cart_item->quantity = $qty;
                $cart_item->total = $qty*$cart_item->package_total;
                $cart_item->save();
            }
            return redirect()->back()->with('success', 'Add to Cart Added successfully.');
        }else{
            return redirect()->back()->with('error', 'Something Went to Wrong!');;
        }
    }

    public function UpdateCart(Request $request){
        if($request->has('item')){
            foreach($request->item as $item){
                $cart_item = CartItem::where('id',$item['item_id'])->first();
                $cart_item->quantity = $item['quantity'];
                $cart_item->price = $item['price'];
                $cart_item->total = $item['total'];
                $cart_item->save();
            }
        }
        return redirect()->back();
    }
    public function deleteCartItem(Request $request, $id = NULL){
        $cart_item = CartItem::where('id',$id)->delete();
        return 1;
    }
}
