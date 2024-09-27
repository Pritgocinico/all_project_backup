<?php

namespace App\Http\Controllers;

use App\Helpers\UserLogHelper;
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
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Nnjeim\World\World;
use Illuminate\Support\Facades\Crypt;
use Mpdf\Mpdf;
use App\Models\CardDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }
    public function checkout()
    {
        $usaCities = World::cities([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaCities->success) {
            $cities = $usaCities->data;
        }
        $usaStates = World::states([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaStates->success) {
            $states = $usaStates->data;
        }
        $cart_items = CartItem::where('user_id', Auth::guard('web')->user()->id)->get();
        $products = Product::all();
        $cardDetail = CardDetail::where('user_id', Auth()->guard('web')->user()->id)->latest()->get();
        return view('frontend.pages.checkout', compact('cart_items', 'products', 'cities', 'states', 'cardDetail'));
    }
    public function placeOrder(Request $request)
    {

        $request->validate([
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|email',
            'phone'             => 'required',
            'address'           => 'required',
            'city'              => 'required|not_in:0',
            'state'             => 'required|not_in:0',
            'zip_code'          => 'required|size:5',
            'total'             => 'required|not_in:0',
            'shipping'          => 'required|not_in:0',
            'ship_method'       => 'required',
        ]);
        if (Order::count() > 0) {
            $id = Order::all()->last()->id;
        } else {
            $id = 0;
        }
        $id = $id + 1;
        $str_length = 5;
        $date = Carbon::now();
        $monthName = $date->format('M');
        $str = substr("00000{$id}", -$str_length);
        $number = str_pad($id, 5, '0', STR_PAD_LEFT);
        $order_id = 'MR-' . $monthName . '-' . $number;
        if ($request->has('save_info') && $request) {
            $save_info = 1;
        } else {
            $save_info = 0;
        }
        $order = new Order();
        $order->order_id            = $order_id;
        $order->user_id             = Auth::guard('web')->user()->id;
        $order->organization_name   = Auth::guard('web')->user()->organization_name;
        $order->email               = $request->email;
        $order->phone               = $request->phone;
        $order->first_name          = $request->first_name;
        $order->last_name           = $request->last_name;
        $order->address             = $request->address;
        $order->address1            = $request->address1;
        $order->city                = $request->city;
        $order->state               = $request->state;
        $order->zip_code            = $request->zip_code;
        if ($request->address_checkbox == "on") {
            $order->billing_address     = $request->address;
            $order->billing_address1    = $request->address1;
            $order->billing_city        = $request->city;
            $order->billing_state       = $request->state;
            $order->billing_zip_code    = $request->zip_code;
        } else {
            $order->billing_address     = $request->billing_address;
            $order->billing_address1    = $request->billing_address1;
            $order->billing_city        = $request->billing_city;
            $order->billing_state       = $request->billing_state;
            $order->billing_zip_code    = $request->billing_zip_code;
        }
        $order->total               = $request->total;
        $order->shipping_charge     = $request->shipping;
        $order->shipping_method     = $request->ship_method;
        $order->save_info           = $save_info;
        $order->save();

        $payment = Payment::where('amount', $request->total)->first();

        if ($payment) {
        $payment->update([
        'order_id' => $order->id,
        ]);
        } 
        
        if (!$order) {
            return redirect('checkout')->with('error', 'Stripe Payment is Failed.');
        } else {


            $cart_items = Cartitem::where('user_id', Auth::guard('web')->user()->id)->get();
            if (!blank($cart_items)) {
                foreach ($cart_items as $item) {
                    $product = Product::where('id', $item->product_id)->first();
                    $order_item = new OrderItem();
                    $order_item->order_id = $order->id;
                    $order_item->product_id = $item->product_id;
                    $order_item->quantity = $item->quantity*$item->package_qty;
                    $order_item->price =  $item->package_price;
                    $order_item->total = $item->total;
                    $order_item->product_name = $product->productname;
                    $order_item->product_image = $product->singleimage;
                    $order_item->package_name = $item->package_name;
                    $order_item->product_price = $product->price;
                    $order_item->medical_necessity = $item->medical_necessity ?? null;
                    $order_item->save();
                    $product->stock = $product->stock - $item->quantity;
                    $product->update();
                }
            }
            $cart_items = Cartitem::where('user_id', Auth::guard('web')->user()->id)->delete();
            // try {
            //     $this->addOrderInQuickBook($order->id);
            // } catch (\Throwable $th) {
            // }
            $orders = Order::with('orderItemDetail', 'orderItemDetail.productDetail', 'user')->findOrFail($order->id);
            $email = $order->email;
            $mpdf = new Mpdf();
            $invoiceHtml = view('admin.pdf.invoice_pdf', compact('orders'))->render();
            $mpdf->WriteHTML($invoiceHtml);
            $pdfPath = storage_path('app/' . time() . '.pdf');
            $mpdf->Output($pdfPath, 'F');
            $orderId =  "MedisourceRX - New Order" .$order->order_id;
            Mail::send('admin.emails.invoice', ['orders' => $orders], function ($message) use ($email, $orderId) {
                $message->from('info@medisourcerx.com', 'MedisourceRX')
                        ->to($email)
                        ->subject($orderId);
            });
            if (env('ADMIN_USERNAME') !== null) {
                Mail::send('admin.emails.invoice', ['orders' => $orders], function ($message) use ($pdfPath, $orderId) {
                    $message->from('info@medisourcerx.com', 'MedisourceRX')
                        ->to(env('ADMIN_USERNAME'))
                        ->subject($orderId);
                });
            }
            $name = Auth::guard('web')->user()->first_name . " " . Auth::guard('web')->user()->last_name;
            $log = $order->order_id." Order Created by ". $name;
            UserLogHelper::storeWebLog('Order', $log);
            $request->session()->flash('order_placed', 'Order Placed Successfully!');
            return view('thankyou');
        }
    }
    public function addOrderInQuickBook($id)
    {
        $orders = Order::with('orderItemDetail', 'orderItemDetail.productDetail')->findOrFail($id);
        $setting = Setting::first();
        $config = config('quickbooks');
        $refreshToken = $setting->refresh_token;
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' => $config['client_secret'],
            'RedirectURI' => $config['redirect_uri'],
            'QBORealmID' => $config['realm_id'],
            'refreshTokenKey' => $refreshToken,
            'scope' => '    com.intuit.quickbooks.accounting',
            'baseUrl' => $config['base_url'],
        ]);
        if ($dataService->getOAuth2LoginHelper()) {
            try {
                $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
                $refreshedToken = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
                $dataService->updateOAuth2Token($refreshedToken);

                $quickBookCustomerID = Auth()->guard('web')->user()->quick_book_cus_id;
                $totalAmount = $orders->total;
                $salesOrderLines = [];
                foreach ($orders->orderItemDetail as $item) {
                    $product = Product::where('id', $item->product_id)->first();
                    $line = [
                        "Description" => $item['package_name'], 
                        "Amount" => $item->total,
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                            "ItemRef" => [
                                "name" => $product->sku,
                                "value" => $product->qb_prod_id,
                            ],
                            "Qty" => $item['quantity'],
                        ]
                    ];
                    $salesOrderLines[] = $line;
                }
                // Add Shipping Charge
                $line = [
                    "Description" => 'Fedex Shipping Charge', 
                    "Amount" => $orders->shipping_charge,
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                        "ItemRef" => [
                            "name" => 'Shipping Charge',
                            "value" => '4',
                        ],
                        "Qty" => '1',
                    ]
                ];
                $salesOrderLines[] = $line;
                $salesOrder = Invoice::create([
                    "DocNumber" => $orders->order_id.rand(1, 999),
                    "Line" => $salesOrderLines,
                    "CustomerRef" => isset($quickBookCustomerID)?$quickBookCustomerID:1,
                    "BillEmail" => [ "Address" => $orders->email ],
                    "SalesTermRef" => [ "value" => '1' ],
                    "ShipAddr" => [
                        "City" => $orders->city,
                        "Line1" => $orders->first_name.' '.$orders->last_name,
                        "Line2" => $orders->address,
                        "PostalCode" => $orders->zip_code,
                        "CountrySubDivisionCode" => $orders->state,
                    ],
                    "ShipMethodRef" => 'Fedex Overnight Standard',
                    "ShipDate" => date("Y-m-d"),
                    "Deposit" => '0',
                    "Balance" => $totalAmount,
                    "TotalAmt" => $totalAmount,
                ]);
                $salesOrder = $dataService->Add($salesOrder);
                 // Mark the invoice as paid
                $payment = Payment::create([
                    "CustomerRef" => isset($quickBookCustomerID) ? $quickBookCustomerID : 1,
                    "TotalAmt" => $totalAmount,
                    "UnappliedAmt" => 0,
                    "Line" => [
                        "Amount" => $totalAmount,
                        "LinkedTxn" => [
                            [
                                "TxnId" => $salesOrder->Id,
                                "TxnType" => "Invoice",
                            ],
                        ],
                    ],
                ]);
                $payment = $dataService->Add($payment);
        
                if (isset($salesOrder)) {
                    Order::where('id', $id)->update(['quickbooks_invoice_id' => $salesOrder->Id]);
                }
                return $salesOrder;
                
            } catch (\Throwable $th) {
                return true;
            }
            
        }
        // to get all products
        // $query = "select * from Item maxresults 6";
        // $product = $dataService->Query($query);
        // dd($product);
       
    }
    public function refreshQuickbookToken()
    {
        $setting = Setting::first();
        $config = config('quickbooks');
        $refreshToken = $setting->refresh_token;
        $client_id = $config['client_id'];
        $client_secret = $config['client_secret'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
            ]),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $this->error("cURL Error #:" . $err);
        } else {
            $responseData = json_decode($response, true);
            $update['access_token'] = $responseData['access_token'];
            $update['refresh_token'] = $responseData['refresh_token'];
            Setting::where('id', 1)->update($update);
            Log::info('Refresh token successfully updated');
        }
        return 1;
    }
}
