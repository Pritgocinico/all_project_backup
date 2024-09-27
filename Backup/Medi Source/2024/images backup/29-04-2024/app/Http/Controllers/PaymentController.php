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
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
        Stripe\Charge::create ([
                "amount" => 1 * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment" 
        ]);
      
        Session::flash('success', 'Payment successful!');
              
        return view('thankyou');
    }
    public function transactionStore(Request $request)
    {
        $coupon = $request->couponCode;
        $codeDetail = CouponDetail::where('coupon_code',$coupon)->first();
        $cart_items = CartItem::where('user_id',Auth::user()->id)->get();
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
                "description" => "Test payment" 
        ]);
      
        Session::flash('success', 'Payment successful!');

        $payment = Payment::create([    
            'stripe_token' => $request->input('stripeToken'),
            'amount' => $amount,
            'order_id' => $request->input('id'),
            'status' => $request->input('status'),
        ]);
        if($payment->status !== "success"){
            return redirect('checkout')->with('error', 'Stripe Payment is Failed.');
        }
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
        // Return the created payment
        return response()->json($payment);
    }

    public function paymentDetail(Request $request){
        $paymentData = Payment::with('orderDetail')->get();
        return view('admin.payment.payment',compact('paymentData'));
    }
    public function cardDetail(Request $request){
        $id = $request->id;
        $card = CardDetail::where('id',$id)->first();
        return response()->json($card);
    }

}
