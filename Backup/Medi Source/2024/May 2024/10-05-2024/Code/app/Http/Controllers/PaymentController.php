<?php

namespace App\Http\Controllers;

use App\Models\CardDetail;
use Illuminate\Http\Request;
use Session;
use Stripe;
use Illuminate\Support\Facades\Crypt;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\CouponDetail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct() {
        $setting=Setting::first();

        view()->share('setting', $setting);
    }
    public function stripe(Request $request, $order_id)
    {
        $orderId = Crypt::decrypt($order_id);
        $order = Order::where('id',$orderId)->first();
        $cardDetail = CardDetail::where('user_id', Auth()->user()->id)->latest()->get();
        return view('stripe',compact('order','cardDetail'));
    }
    
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        if (Order::count() > 0) {
            $id = Order::all()->last()->id;
        } else {
            $id = 0;
        }
        $id = $id + 1;
        $date = Carbon::now();
        $monthName = $date->format('M');
        $number = str_pad($id, 5, '0', STR_PAD_LEFT);
        $order_id = 'MR-' . $monthName . '-' . $number;
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
        Stripe\Charge::create ([
                "amount" => 1 * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => $order_id
        ]);
      
        Session::flash('success', 'Payment successful!');
              
        return view('thankyou');
    }
    public function transactionStore(Request $request)
    {
        if (Order::count() > 0) {
            $orderId = Order::all()->last()->id;
        } else {
            $orderId = 0;
        }
        $orderId = $orderId + 1;
        $date = Carbon::now();
        $monthName = $date->format('M');
        $number = str_pad($orderId, 5, '0', STR_PAD_LEFT);
        $order_id = 'MR-' . $monthName . '-' . $number;
        $coupon = $request->couponCode;
        $codeDetail = CouponDetail::where('coupon_code',$coupon)->first();
        $cart_items = CartItem::where('user_id',Auth::guard('web')->user()->id)->get();
        $charge = $request->charge;
        $total = 0;
        foreach ($cart_items as $cart){
            if(isset($codeDetail) && $codeDetail->product_id == $cart->product_id){
                if($codeDetail->coupon_type == "percentage"){
                    $discount = ($cart->total * $codeDetail->discount_percentage) /100;
                    $cart->total = $cart->total - $discount;
                }
            }
            $total += $cart->total;
        }
        $amount = $total + $charge;
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
        Stripe\Charge::create ([
                "amount" => $amount * 100,
                "currency" => "usd",
                "source" => $request->input('stripeToken'),
                "description" => $order_id, 
        ]);
        
        
        $payment = Payment::create([    
            'stripe_token' => $request->input('stripeToken'),
            'amount' => $amount,
            'order_id' => $orderId,
            'status' => $request->input('status'),
        ]);
        if($request->card_id === null){
            $insert = [
                'user_id'=>Auth()->user()->id,
                'card_name'=> $request->card_name,
                'card_number'=> $request->number,
                'cvv_number'=> $request->cvc,
                'expire_month'=> $request->exp_month,
                'expire_year'=> $request->exp_year,
            ];
            CardDetail::create($insert);
        }
        Session::flash('success', 'Payment successful!');
        // Return the created payment
        return response()->json($payment);
    }

    public function paymentDetail(Request $request){
        $paymentData = Payment::with('orderDetail')->orderBy('id','desc')->get();
        return view('admin.payment.payment',compact('paymentData'));
    }
    public function cardDetail(Request $request){
        $id = $request->id;
        $card = CardDetail::where('id',$id)->first();
        return response()->json($card);
    }

}
