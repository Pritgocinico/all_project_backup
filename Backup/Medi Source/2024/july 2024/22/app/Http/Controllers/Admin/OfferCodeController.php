<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Product;
use App\Models\CouponDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OfferCodeController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();

        view()->share('setting', $setting);
    }

    public function index()
    {
        $couponList = CouponDetail::with('productDetail')->get();
        return view('admin.coupon.index', compact('couponList'));
    }

    public function create(CouponDetail $coupon)
    {
        $type = "create";
        $products = Product::all();
        return view('admin.coupon.create', compact('products','type','coupon'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|unique:coupon_details,coupon_code,except,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|min:1',
            'discount_type' => 'required|in:percentage,dollar',
        ]);
        $data = [
            'coupon_code' =>$request->coupon_code,
            'coupon_type' =>$request->discount_type,
            'product_id' =>$request->product_id,
            'quantity' =>$request->quantity,
        ];
        if($request->discount_type == "percentage"){
            $data['discount_percentage'] = $request->discount_percentage;
        }
        if($request->discount_type == "dollar"){
            $data['discount_amount'] = $request->discount_amount;
        }
        $insert = CouponDetail::create($data);
        if($insert){
            return redirect()->route('coupon.index')->with('success', 'Coupon created successfully.');
        }
        return redirect()->route('coupon.create')->with('error', 'Something Went to Wrong!.');
        
    }

    public function show(string $id)
    {
        //
    }

    public function edit(CouponDetail $coupon)
    {
        $type = "edit";
        $products = Product::all(); // Assuming you want to populate a dropdown with products
        return view('admin.coupon.create', compact('coupon', 'products','type'));
    }

    public function update(Request $request, CouponDetail $coupon)
    {
        $request->validate([
            'coupon_code' => 'required|unique:coupon_details,coupon_code,'.$coupon->id,
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|min:1',
            'discount_type' => 'required|in:percentage,dollar',
        ]);
        $data = [
            'coupon_code' =>$request->coupon_code,
            'coupon_type' =>$request->discount_type,
            'product_id' =>$request->product_id,
            'quantity' =>$request->quantity,
        ];
        if($request->discount_type == "percentage"){
            $data['discount_percentage'] = $request->discount_percentage;
        }
        if($request->discount_type == "dollar"){
            $data['discount_amount'] = $request->discount_amount;
        }
        $update = $coupon->update($data);
        if($update){
            return redirect()->route('coupon.index')->with('success', 'Coupon update successfully.');
        }
        return redirect()->route('coupon.index')->with('error', 'Something Went to Wrong!.');
    }

    public function destroy(CouponDetail $coupon)
    {
        $delete = $coupon->delete();
        if($delete){
            return redirect()->route('coupon.index')->with('success', 'Coupon Deleted successfully.');
        }
        return redirect()->route('coupon.index')->with('error', 'Something Went to Wrong!.');
    }

    public function detailByCode(Request $request){
        $code = $request->code;
        $codeDetail = CouponDetail::where('coupon_code',$code)->first();
        if($codeDetail == null){
            $codeDetail = [];
        }
        return response()->json($codeDetail);
    }
}
