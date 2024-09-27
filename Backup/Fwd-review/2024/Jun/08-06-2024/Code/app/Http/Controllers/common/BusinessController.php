<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Business;
use App\Models\BusinessRequest;
use App\Models\Payment;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\OffersNotification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as FacadesLog;
use Mail;

class BusinessController extends Controller
{
    private $apiUrl;
    private $apiKey;
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
        $this->apiUrl = env('MEMBER_PRESS_API_URL');
        $this->apiKey = env('MEMBER_PRESS_API_KEY');
    }
    public function business(Request $request, $id = NULL)
    {
        $page = 'Business';
        $icon = 'add-user.png';
        $currDate = Carbon::now();
        $currentDate = $currDate->format('Y-m-d');
        if (isset($id)) {
            $businesses = Business::with('planDetail')->where('active', 1)->where('client_id', $id)->with('client')->get();
        } else {
            $businesses = Business::with('planDetail')->where('active', 1)->with('client')->get();
        }
        return view('admin.business.businesses', compact('page', 'icon', 'businesses', 'id', 'currentDate'));
    }
    public function addBusiness(Request $request)
    {
        $page = 'Business';
        $icon = 'add-user.png';
        $clients = User::where('role', 2)->get();
        $planList = Plan::where('status', 1)->get();
        return view('admin.business.add_business', compact('planList', 'page', 'icon', 'clients'));
    }
    public function addBusinessData(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'client' => 'required|not_in:0',
            'shortname' => [
                'required',
                Rule::unique('businesses')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'place_id' => [
                'required',
                Rule::unique('businesses')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'api_key' => 'required',
            'plan' => 'required',
            'payment_option' => 'required',
        ]);
        $planDetail = Plan::where('id', $request->plan)->first();
        $currentDate = Carbon::now();
        if ($planDetail->plan_period_type == "years") {
            $date = $currentDate->addYear()->format('Y-m-d');
        } else if ($planDetail->plan_period_type == "months") {
            $date = $currentDate->addMonth()->format('Y-m-d');
        } else if ($planDetail->plan_period_type == "weeks") {
            $date = $currentDate->addWeek()->format('Y-m-d');
        } else {
            $date = $currentDate->addDay()->format('Y-m-d');
        }

        $business = new Business();
        $business->name         = $request->name;
        $business->client_id    = $request->client;
        $business->shortname    = $request->shortname;
        $business->place_id     = $request->place_id;
        $business->api_key      = $request->api_key;
        $business->plan_id      = $request->plan;
        $business->payment_option      = $request->payment_option;
        $business->add_type      = "MANUAL BACKEND ONBOARDING";
        $business->sub_end_date      = $date;
        $business->status       = 1;
        $business->active       = 1;
        $business->active       = 1;
        $business->transaction_id      = $request->transaction_id;
        $business->payment_date_time      = Carbon::now();
        $insert                 = $business->save();

        $data['businessData'] = $business;
        $subject = "Add New Business - " . $business->name;
        Mail::send('emails.add_new_business', $data, function ($message) use ($business, $subject) {
            $message->from('gofwdreviews@gmail.com', 'FWD Review')
                ->to($business->client->email)
                ->subject($subject);
        });
        $adminDetail = User::where('id', 1)->first();
        Mail::send('emails.add_new_business', $data, function ($message) use ($adminDetail, $subject) {
            $message->from('gofwdreviews@gmail.com', 'FWD Review')
                ->to($adminDetail->email)
                ->subject($subject);
        });
        $clientDetail = User::where('id', $request->client)->first();
        if ($clientDetail->business == 0) {
            $clientDetail->business = $business->id;
            $clientDetail->save();
        }

        $admin = User::where('id', Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Business Added - ',
            'text' => 'Name: ' . $request->name,
            'url' => route('admin.business'),
        ];

        Notification::send($admin, new OffersNotification($notificationData));

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Business';
        $log->log       = $business->name . ' Created Successfully';
        $log->save();

        if ($insert) {
            $businessDetail = Business::with('client', 'paymentDetail')->latest()->first();
            $paymentUpdate = [
                'membership' => $planDetail->plan_id,
                'tax_amount' => $planDetail->price,
                'expiry_date' => $date,
                'transaction_number' => "Offline Payment",
                'gateway' => "Offline Payment",
                'user_id' => $request->client,
                'business_id' => $businessDetail->id,
                'plan_title' => $planDetail->plan_title,
                'subscription_text' => $planDetail->plan_text,
            ];
            Payment::create($paymentUpdate);
            Session::flash('success', 'Business Created Successfully.');
            return redirect()->route('admin.business');
        } else {
            Session::flash('error', 'Something Went Wrong.');
            return redirect()->route('admin.add.business');
        }
    }
    public function editBusiness(Request $request, $id = NULL)
    {
        $page = 'Business';
        $icon = 'add-user.png';
        $clients = User::where('role', 2)->get();
        $business = Business::where('id', $id)->first();
        if (Auth()->user()->role == 2) {
            return view('client.business.edit_business', compact('page', 'icon', 'clients', 'business'));
        }
        return view('admin.business.edit_business', compact('page', 'icon', 'clients', 'business'));
    }
    public function updateBusiness(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'client' => 'required|not_in:0',
            'shortname' => [
                'required',
                Rule::unique('businesses')->ignore($request->id)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'place_id' => [
                'required',
                Rule::unique('businesses')->ignore($request->id)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'api_key' => 'required',
        ]);
        $business = Business::where('id', $request->id)->first();
        $business->name         = $request->name;
        $business->client_id    = $request->client;
        $business->shortname    = $request->shortname;
        $business->place_id     = $request->place_id;
        $business->api_key      = $request->api_key;
        $business->status       = 1;
        $business->active       = 1;
        $insert                 = $business->save();
        
        $admin = User::where('id', Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Business Updated - ',
            'text' => 'Name: ' . $request->name,
            'url' => route('admin.business'),
        ];

        Notification::send($admin, new OffersNotification($notificationData));
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Business';
        $log->log       = $business->name . ' Updated Successfully';
        $log->save();

        if ($insert) {
            Session::flash('success', 'Business Updated Successfully.');
            return redirect()->route('admin.business');
        } else {
            Session::flash('error', 'Something Went Wrong.');
            return redirect()->route('admin.edit.business', $request->id);
        }
    }
    public function deleteBusiness($id)
    {
        $user = Business::where('id', $id)->first();
        $user->delete();
        if ($user->subscription_id !== null) {
            $endpointGet = "{$this->apiUrl}/subscriptions/{$user->subscription_id}";
            try {
                $response = Http::withHeaders([
                    'MEMBERPRESS-API-KEY' => $this->apiKey,
                ])->post($endpointGet);
                if ($response->json()) {
                    $cancelSubscription = "{$this->apiUrl}/subscriptions/{$user->subscription_id}/cancel";
                    try {
                        $response1 = Http::withHeaders([
                            'MEMBERPRESS-API-KEY' => $this->apiKey,
                        ])->post($cancelSubscription);
                        FacadesLog::info($response1->json());
                    } catch (\Throwable $th1) {
                        FacadesLog::info($th1);
                    }
                }
                FacadesLog::info($response->json());
            } catch (\Throwable $th) {
                FacadesLog::info($th);
            }
        }
        $admin = User::where('id', Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Business Deleted - ',
            'text' => 'Name: ' . $user->name,
            'url' => route('admin.business'),
        ];

        Notification::send($admin, new OffersNotification($notificationData));
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Business';
        $log->log       = $user->name . ' Deleted Successfully';
        $log->save();
        if ($user) {
            return response()->json(['data' => '', 'message' => 'Business Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function businessRequestDetail(Request $request, $id = NULL)
    {
        $businessList = Business::where('delete_request', 1)->get();
        $businessEditList = BusinessRequest::with('businessDetail', 'clientDetail', 'planDetail')->where('status', 1)->get();
        $page = 'Client Request';
        $icon = 'add-user.png';
        return view('admin.business.business_request', compact('page', 'id', 'icon', 'businessList', 'businessEditList'));
    }

    public function viewBusiness(Request $request, $id = NULL)
    {
        $page = 'Business';
        $icon = 'add-user.png';
        $clients = User::where('role', 2)->get();
        $business = Business::where('id', $id)->first();
        return view('admin.business.edit_business', compact('page', 'icon', 'clients', 'business'));
    }

    public function businessDetail($id = NULL)
    {
        $page  = "Business Detail";
        $icon = 'add-user.png';
        $business = Business::with('client', 'paymentDetail')->where('id', $id)->first();
        if (!blank($business)) {
            $paymentDetail = Payment::where('business_id', $business->id)->get();
            $client = User::where('id', $business->client_id)->first();
            if (Auth()->user()->role == "2") {
                return view('client.business.view_business', compact('page', 'icon', 'business', 'client', 'paymentDetail'));
            }
            return view('admin.business.view_business', compact('page', 'icon', 'business', 'client', 'paymentDetail'));
        }
        abort(404);
    }
    public function updateBusinessRequest(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'client' => 'required|not_in:0',
            'shortname' => [
                'required',
                Rule::unique('businesses')->ignore($request->id)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'place_id' => [
                'required',
                Rule::unique('businesses')->ignore($request->id)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'api_key' => 'required',
        ]);
        $businessData = Business::where('id', $request->id)->first();
        $originalValues = [
            'name' => $businessData->name,
            'shortname' => $businessData->shortname,
            'place_id' => $businessData->place_id,
            'api_key' => $businessData->api_key,
        ];

        // Retrieve the updated values from the form
        $updatedValues = $request->only('name', 'shortname', 'place_id', 'api_key');
        $changedFields = array_diff_assoc($originalValues, $updatedValues);
        if(empty($changedFields)){
            Session::flash('success', 'Business Update Successfully.');
            return redirect()->route('client.business');
        }
        if($businessData->name == $request->name){}
        $businessRequest = BusinessRequest::where('business_id', $request->id)->where('status', 1)->first();
        if (isset($businessRequest)) {
            $businessRequest->plan_id         = $businessData->plan_id;
            $businessRequest->name         = $request->name;
            $businessRequest->client_id    = $request->client;
            $businessRequest->shortname    = $request->shortname;
            $businessRequest->place_id     = $request->place_id;
            $businessRequest->api_key      = $request->api_key;
            $businessRequest->status       = 1;
            $insert                 = $businessRequest->save();
        } else {
            $create = new BusinessRequest();
            $create->business_id         = $request->id;
            $create->name         = $request->name;
            $create->plan_id         = $businessData->plan_id;
            $create->client_id    = $request->client;
            $create->shortname    = $request->shortname;
            $create->place_id     = $request->place_id;
            $create->api_key      = $request->api_key;
            $create->status       = 1;
            $insert                 = $create->save();
        }

        $data['businessRequestDetail'] = BusinessRequest::where('business_id', $request->id)->where('status', 1)->first();
        $data['businessData'] = $businessData;
        $data['type'] = 0;
        $subject = "Edit Request Business For " . $businessData->name;
        Mail::send('emails.edit_request', $data, function ($message) use ($businessData, $subject) {
            $message->from('gofwdreviews@gmail.com', 'FWD Review')
                ->to($businessData->client->email)
                ->subject($subject);
        });
        $adminDetail = User::where('id', 1)->first();
        Mail::send('emails.edit_request', $data, function ($message) use ($adminDetail, $subject) {
            $message->from('gofwdreviews@gmail.com', 'FWD Review')
                ->to($adminDetail->email)
                ->subject($subject);
        });
        $admin = User::where('id', Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Business Updated Request - ',
            'text' => 'Name: ' . $request->name,
            'url' => route('admin.business'),
        ];

        Notification::send($admin, new OffersNotification($notificationData));
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Business';
        $log->log       = $request->name . ' Request Sent Successfully';
        $log->save();

        if ($insert) {
            Session::flash('success', 'Business Update Request Sent Successfully.');
            return redirect()->route('client.business');
        } else {
            Session::flash('error', 'Something Went Wrong.');
            return redirect()->route('client.edit.business', $request->id);
        }
    }
    public function updateBusinessRequestStatus(Request $request)
    {
        $request_id = $request->request_id;
        $business_id = $request->business_id;
        $type = $request->type;
        $businessRequest = BusinessRequest::where('id', $request_id)->where('status', '1')->first();
        if ($businessRequest) {
            $text = "Cancel";
            if ($type == "approve") {
                $businessDetail = Business::where('id', $business_id)->first();
                $businessDetail->name = $businessRequest->name;
                $businessDetail->shortname = $businessRequest->shortname;
                $businessDetail->place_id = $businessRequest->place_id;
                $businessDetail->api_key = $businessRequest->api_key;
                $businessDetail->save();
                $text = "Approve";
                $data['businessRequestDetail'] = BusinessRequest::where('business_id', $request->id)->where('status', 1)->first();
                $data['businessData'] = $businessDetail;
                $data['type'] = 1;
                $subject = "Update Business For " . $businessDetail->name;
                Mail::send('emails.edit_request', $data, function ($message) use ($businessDetail, $subject) {
                    $message->from('gofwdreviews@gmail.com', 'FWD Review')
                        ->to($businessDetail->client->email)
                        ->subject($subject);
                });
                $adminDetail = User::where('id', 1)->first();
                Mail::send('emails.edit_request', $data, function ($message) use ($adminDetail, $subject) {
                    $message->from('gofwdreviews@gmail.com', 'FWD Review')
                        ->to($adminDetail->email)
                        ->subject($subject);
                });
                $admin = User::where('id', Auth::user()->id)->first();
                $notificationData = [
                    'type' => 'message',
                    'title' => 'Business Updated Request - ',
                    'text' => 'Name: ' . $request->name,
                    'url' => route('admin.business'),
                ];
            }
            $businessRequest->status = 0;
            $businessRequest->save();
            $admin = User::where('id', Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Business ' . $text . ' Cancel - ',
                'text' => 'Name: ' . $request->name,
                'url' => route('admin.business'),
            ];
            Notification::send($admin, new OffersNotification($notificationData));
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Business';
            $log->log       = $request->name . ' Request ' . $text . ' Successfully';
            $log->save();
            if ($type == "reject") {
                return response()->json(['data' => "", 'message' => 'Request Cancelled Successfully', 'status' => 1], 200);
            }
            return response()->json(['data' => "", 'message' => 'Request Approved Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => "", 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
}
