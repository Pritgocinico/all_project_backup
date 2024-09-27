<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductPackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
class FedExController extends Controller
{
    private $accessToken;
    protected $fedexChargeArray =[];
    public function __construct()
    {
        $this->fedexChargeArray['1'] =[
            'length'=> 8,
            'width'=> 8,
            'height'=> 8,
            'vial_box'=> 1,
            'ice_box'=> 3,
            'lbs'=> 4,
        ];
        $this->fedexChargeArray['5'] =[
            'length'=> 7,
            'width'=> 8,
            'height'=> 8,
            'vial_box'=> 5,
            'ice_box'=> 4,
            'lbs'=> 4,
        ];
        $this->fedexChargeArray['10'] =[
            'length'=> 7,
            'width'=> 8,
            'height'=> 8,
            'vial_box'=> 10,
            'ice_box'=> 4,
            'lbs'=> 4,
        ];
        $this->fedexChargeArray['15'] =[
            'length'=> 7,
            'width'=> 8,
            'height'=> 8,
            'vial_box'=> 15,
            'ice_box'=> 4,
            'lbs'=> 4,
        ];
        $this->fedexChargeArray['20'] =[
            'length'=> 7,
            'width'=> 8,
            'height'=> 8,
            'vial_box'=> 20,
            'ice_box'=> 6,
            'lbs'=> 5,
        ];
        $this->fedexChargeArray['25'] =[
            'length'=> 7,
            'width'=> 8,
            'height'=> 8,
            'vial_box'=> 25,
            'ice_box'=> 7,
            'lbs'=> 5,
        ];
        $this->fedexChargeArray['30'] =[
            'length'=> 7,
            'width'=> 8,
            'height'=> 8,
            'vial_box'=> 30,
            'ice_box'=> 8,
            'lbs'=> 6,
        ];
        $this->getAccessToken();
    }

    private function getAccessToken()
    {
        $client = new Client([
            'base_uri' => env('FEDEX_API_URL').'/oauth/token',
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);

        $payload = [
            'grant_type' => 'client_credentials',
            'client_id' => env('FEDEX_CLIENT_ID'),
            'client_secret' => env('FEDEX_CLIENT_SECRET')
        ];

        try {
            $response = $client->post('', [
                'form_params' => $payload
            ]);
            $data = json_decode($response->getBody(), true);
            $this->accessToken = $data['access_token'];
        } catch (GuzzleException $e) {
            // Handle the exception
            $this->accessToken = null;
        }
    }

    public function getRates(Request $request)
    {
        if ($this->accessToken === null) {
            return response()->json(['error-accesstoken' => 'Failed to retrieve access token'], 500);
        }
        $vial = $request->vial;
        $lbsData = $this->getLbsData($vial);

        $client = new Client([
            'base_uri' => env('FEDEX_API_URL').'/rate/v1/rates/quotes',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'X-locale' => 'en_US',
                'Content-Type' => 'application/json'
            ]
        ]);

        $payload = [
            "accountNumber" => [
                "value" => env('FEDEX_ACCOUNT_NUMBER')
            ],
            "requestedShipment" => [
                "shipper" => [
                    "address" => [
                        "postalCode" => env('FEDEX_ADD_POSTAL'),
                        "countryCode" => env('FEDEX_ADD_COUNTRY'),
                    ]
                ],
                "recipient" => [
                    "address" => [
                        "postalCode" => $request->zip,
                        "countryCode" => "US",
                    ]
                ],
                "pickupType" => "DROPOFF_AT_FEDEX_LOCATION",
                "serviceType" => "PRIORITY_OVERNIGHT",
                "packagingType" => "FEDEX_EXTRA_LARGE_BOX",
                "rateRequestType" => [
                    "LIST",
                    "ACCOUNT"
                ],
                "requestedPackageLineItems" => [
                    [
                        "weight" => [
                            "units" => "LB",
                            "value" => $this->fedexChargeArray[$lbsData]['lbs'],
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $client->post('', ['json' => $payload]);
            $data = json_decode($response->getBody(), true);

            // Extract the relevant shipping rate information from the response
            $shippingRates = [];
            if (isset($data['output']['rateReplyDetails'])) {
                foreach ($data['output']['rateReplyDetails'] as $rateReply) {
                    if ($rateReply['serviceType'] === 'PRIORITY_OVERNIGHT' && $rateReply['packagingType'] === 'FEDEX_EXTRA_LARGE_BOX') {
                        $shippingRates[] = [
                            'serviceType' => $rateReply['serviceType'],
                            'packagingType' => $rateReply['packagingType'],
                            'totalNetChargeWithDutiesAndTaxes' => $rateReply['ratedShipmentDetails'][0]['totalNetFedExCharge']
                        ];
                    }
                }
            }

            return response()->json($shippingRates);
        } catch (GuzzleException $e) {
            return response()->json(['error-getRates' => $e->getMessage()], 500);
        }
    }
    public function generateLabel($id)
    {
        $order = Order::where('id',$id)->first();
        $orderItem = OrderItem::where('order_id',$order->id)->get();
        $totalVial = 0;
        foreach($orderItem as $item){
            $package = ProductPackage::where('varient_name',$item->package_name)->where('product_id',$item->product_id)->first();
            $totalVial = $totalVial +$package->vial_quantity;
        }
        $lbsData = $this->getLbsData($totalVial);
        if ($order->label === null) {
        if ($this->accessToken === null) {
            return response()->json(['error-accesstoken' => 'Failed to retrieve access token'], 500);
        }
    
        $client = new Client([
            'base_uri' => env('FEDEX_API_URL') . '/ship/v1/shipments',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'X-locale' => 'en_US',
                'Content-Type' => 'application/json'
            ]
        ]);
        $date = Carbon::parse($order->created_at)->toDateString();
        // Prepare the shipment data
        $shipData = [
            "labelResponseOptions" => "URL_ONLY",
            "requestedShipment" => [
                "shipper" => [
                    "contact" => [
                        "personName" => env('FEDEX_SHIPPER_NAME'),
                        "phoneNumber" => env('FEDEX_SHIPPER_PHONE'),
                        "companyName" =>  env('FEDEX_SHIPPER_NAME')
                    ],
                    "address" => [
                        "streetLines" => [
                            env('FEDEX_ADD_L1')
                        ],
                        "city" =>  env('FEDEX_ADD_CITY'),
                        "stateOrProvinceCode" =>  env('FEDEX_ADD_STATE'),
                        "postalCode" =>  env('FEDEX_ADD_POSTAL'),
                        "countryCode" =>  env('FEDEX_ADD_COUNTRY')
                    ]
                ],
                "recipients" => [
                    [
                        "contact" => [
                            "personName" => $order->first_name. ' ' . $order->last_name,
                            "phoneNumber" => $order->phone,
                            "companyName" => $order->organization_name
                        ],
                        "address" => [
                            "streetLines" => [
                                $order->address,
                                $order->address1
                            ],
                            "city" => $order->city,
                            "stateOrProvinceCode" => "CA",
                            "postalCode" => $order->zip_code,
                            "countryCode" => "US"
                        ]
                    ]
                ],
                "shipDatestamp" => $date,
                "serviceType" => "STANDARD_OVERNIGHT",
                "packagingType" => "YOUR_PACKAGING",
                "pickupType" => "USE_SCHEDULED_PICKUP",
                "blockInsightVisibility" => false,
                "shippingChargesPayment" => [
                    "paymentType" => "SENDER"
                ],
                "labelSpecification" => [
                    "imageType" => "PDF",
                    "labelStockType" => "PAPER_85X11_TOP_HALF_LABEL"
                ],
                "requestedPackageLineItems" => [
                    [
                        "declaredValue" => [
                            "amount" => intval($order->total),
                            "currency" => "USD"
                        ],
                        "weight" => [
                            "value" => $this->fedexChargeArray[$lbsData]['lbs'],
                            "units" => "LB",
                        ],
                        "itemDescriptionForClearance" => "Medical Product"
                    ]
                ]
            ],
            "accountNumber" => [
                "value" =>  env('FEDEX_ACCOUNT_NUMBER')
            ]
        ];

        try {
            $response = $client->post('', ['json' => $shipData]);
            $data = json_decode($response->getBody(), true);
        
            // Extracting tracking ID and label URL
            $trackingId = $data['output']['transactionShipments'][0]['masterTrackingNumber'];
            $labelUrl = $data['output']['transactionShipments'][0]['pieceResponses'][0]['packageDocuments'][0]['url'];
    
            $order->tracking_number     = $trackingId;
            $order->label               = $labelUrl;
            $order->update();

            // Process the response and return the created shipment information
            $fileContent = Http::get($labelUrl)->body();
            $headers = [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $order->order_id . '-FedexShippingLebel.pdf"',
                'Content-Length' => strlen($fileContent)
            ];
            return response()->streamDownload(function () use ($fileContent) {
                echo $fileContent;
            }, $order->order_id . '.pdf', $headers);
        } catch (GuzzleException $e) {
            return response()->json(['error-createShipment' => $e->getMessage()], 500);
        }
    }else{
        $fileContent = Http::get($order->label)->body();
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $order->order_id . '-FedexShippingLebel.pdf"',
            'Content-Length' => strlen($fileContent)
        ];
        return response()->streamDownload(function () use ($fileContent) {
            echo $fileContent;
        }, $order->order_id . '.pdf', $headers);
    }
    }
    public function getLbsData($vial){
        $lbsData = 1;
        if($vial == 1){
            $lbsData = 1;
        }
        if($vial > 1 && $vial <= 5){
            $lbsData = 5;
        }
        if($vial > 5 && $vial <= 10){
            $lbsData = 10;
        }
        if($vial > 10 && $vial <= 15){
            $lbsData = 15;
        }
        if($vial > 15 && $vial <= 20){
            $lbsData = 20;
        }
        if($vial > 20 && $vial <= 25){
            $lbsData = 25;
        }
        if($vial > 25){
            $lbsData = 30;
        }
        return $lbsData;
    }
}