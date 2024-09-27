<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\Customer;
use App\Models\User;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
class QuickbooksController extends Controller
{
    public function addOrderInQuickBooks($id)
    {
        $orders = Order::with('orderItemDetail', 'orderItemDetail.productDetail')->findOrFail($id);
        // dd($orders->user_id);
        $user = User::where('id', $orders->user_id)->first();
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
      
            try {  
                if ($dataService->getOAuth2LoginHelper()) {
                $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
                $refreshedToken = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
                $dataService->updateOAuth2Token($refreshedToken);

                $quickBookCustomerID = $user->quick_book_cus_id;
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
                    
                }
                return redirect()->back()->with('success', 'Quickbooks Order Synchronize Successful');

            } catch (\Throwable $th) {
                return redirect()->back()->with('error', $th->getMessage());
            }
 
    }

    public function addCustomerToQuickBooks($id)
    {
        $user = User::where('id', $id)->first();
        $setting=Setting::first();
        $config = config('quickbooks');
        $refreshToken = $setting->refresh_token;
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' => $config['client_secret'],
            'RedirectURI' => $config['redirect_uri'],
            'refreshTokenKey' => $refreshToken,
            'QBORealmID' => $config['realm_id'],
            'scope' => 'com.intuit.quickbooks.accounting',  
            'baseUrl' => $config['base_url'],
        ]);
        try {  
            if ($dataService->getOAuth2LoginHelper()) {
                $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
                $refreshedToken = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
                $dataService->updateOAuth2Token($refreshedToken);
            }
            $displayname = $user->first_name . ' ' . $user->last_name;
            $query = "SELECT * FROM Customer WHERE DisplayName = '$displayname'";
            $customerData = $dataService->Query($query);
            if (isset($customerData) && !empty($customerData) && count($customerData) > 0) {
                $customer = $customerData[0];
                $customer->Id = $customer->Id;
                $customer->GivenName = $displayname;
                $customer->DisplayName = $displayname;
                $customer->CompanyName = $user->organization_name;
                $customer->BusinessNumber = $user->phone;
                $customer->Mobile = $user->phone;
                if(isset($customer->PrimaryEmailAddr)){
                    $customer->PrimaryEmailAddr->Address = $user->email;
                }
                if(isset($customer->PrimaryPhone)){
                    $customer->PrimaryPhone->FreeFormNumber = $user->phone;
                }
                try {
                    $result = $dataService->Update($customer);
                } catch (ServiceException $ex) {
                    Log::error("Error updating customer in QuickBooks: " . $ex->getMessage());
                    return redirect()->back()->with('error', 'Error updating customer in QuickBooks: '.$ex->getMessage());
                }
            } else {
                $organization = $user->organization_name;
                $customer = Customer::create([
                    "GivenName" => $displayname,
                    "DisplayName" => $displayname,
                    "CompanyName" => $organization,
                    "PrimaryEmailAddr" => [
                        "Address" => $user->email
                    ],
                    "BillAddr" => [
                        "Line1" => $user->practice_address_street,
                        "City" => $user->city,
                        "CountrySubDivisionCode" => "CA",
                        "PostalCode" => $user->zip_code,
                        "Country" => "USA",
                    ],
                    "PrimaryPhone" => [
                        "FreeFormNumber" => $user->phone
                    ]
                ]);
                try {
                    $result = $dataService->Add($customer);
                    if (isset($result) && isset($result->Id)) {
                        User::where('id', $user->id)->update(['quick_book_cus_id' => $result->Id]);
                    }
                    return redirect()->back()->with('success', 'Customer added successfully');
                } catch (ServiceException $ex) {
                    Log::error("Error adding customer to QuickBooks: " . $ex->getMessage());
                    return redirect()->back()->with('error', $th->getMessage());
                }
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function refreshQuickbookToken()
    {
        try {
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
                return "cURL Error: " . $err;
            } else {
                $responseData = json_decode($response, true);
                if (isset($responseData['access_token']) && isset($responseData['refresh_token'])) {
                    $update['access_token'] = $responseData['access_token'];
                    $update['refresh_token'] = $responseData['refresh_token'];
                    Setting::where('id', 1)->update($update);
                    Log::info('Refresh token successfully updated');
                    return 1;
                } else {
                    return "Access token or refresh token not found in response.";
                }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 0;
        }
    }
    
}