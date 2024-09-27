<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Business;
use App\Models\CustomerFeedback;
use App\Models\FunnelNotification;
use App\Models\Plan;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Notification;
use App\Notifications\OffersNotification;
use Illuminate\Support\Facades\Log as FacadesLog;
use PDO;
use Illuminate\Support\Str;
use Mail;

class ClientController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function dashboard(Request $req)
    {
        $page = 'Client Dashboard';
        $icon = 'dashboard.png';
        $data = [];
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->where('sub_end_date', '>=', date('Y-m-d'))->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('sub_end_date', '>=', date('Y-m-d'))->where('status', 1)->first();
        }
        if (blank($business)) {
            $business = [];
            $data = [];
            return view('client.dashboard', compact('page', 'icon', 'data', 'business'));
        } else {
            $response1 = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $business->place_id,
                'key' => $business->api_key,
            ]);
            $jsonData1 = json_decode($response1->getBody(), true);
            $message = '';
            if (array_key_exists('result', $jsonData1)) {
                $data = $jsonData1['result'];
            } else {
                $data = [];
                $message = $jsonData1['error_message'];
            }
            $notification_emails = FunnelNotification::where('user_id', $business->id)->get();
            return view('client.funnel.funnel', compact('page', 'icon', 'data', 'business', 'message', 'notification_emails'));
        }
    }
    public function funnel(Request $req)
    {
        $page = 'Client Funnel';
        $icon = 'dashboard.png';
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->where('sub_end_date', '>=', date('Y-m-d'))->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('sub_end_date', '>=', date('Y-m-d'))->where('status', 1)->first();
        }
        if ($business == null) {
            $business = [];
            $data = [];
            return view('client.dashboard', compact('page', 'icon', 'data', 'business'));
        } else {
            $response1 = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $business->place_id,
                'key' => $business->api_key,
            ]);
            $jsonData1 = json_decode($response1->getBody(), true);
            $message = '';
            if (array_key_exists('result', $jsonData1)) {
                $data = $jsonData1['result'];
            } else {
                $data = [];
                $message = $jsonData1['error_message'];
            }
            $notification_emails = FunnelNotification::where('user_id', $business->id)->get();
            return view('client.funnel.funnel', compact('page', 'icon', 'data', 'business', 'message', 'notification_emails'));
        }
    }
    public function reviews(Request $req)
    {
        $page = 'Reviews';
        $icon = 'loyalty.png';
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('status', 1)->first();
        }
        // dd($business);
        $response1 = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id' => $business->place_id,
            'key' => $business->api_key,
            'page_token' => 2,
        ]);
        $jsonData1 = json_decode($response1->getBody(), true);
        $message = '';
        if (array_key_exists('result', $jsonData1)) {
            $data = $jsonData1['result']['reviews'];
            $count = count($data);
        } else {
            $data = [];
            $message = $jsonData1['error_message'];
        }
        // dd($jsonData1, $count);
        $feedbacks = CustomerFeedback::where('user_id', Auth::user()->business)->where('status', 1)->latest()->get();
        return view('client.reviews.reviews', compact('page', 'icon', 'data', 'business', 'feedbacks', 'message'));
    }
    public function recipients(Request $req)
    {
        $page = 'Recipients';
        $icon = 'staffs.png';
        $data = [];
        return view('client.recipients.recipients', compact('page', 'icon', 'data'));
    }
    public function changeBusiness(Request $request)
    {
        $business = $request->business;
        $user = User::where('id', Auth::user()->id)->first();
        $user->business = $business;
        $user->save();
        return response()->json('success', 200);
    }
    public function viewReview($slug = NULL)
    {
        if (!blank($slug)) {
            $business = Business::where('shortname', $slug)->first();
            if (!blank($business)) {
                return view('review', compact('business'));
            } else {
                return view('404');
            }
        }
        return redirect()->route('error_404');
    }
    public function submitFunnel(Request $request)
    {
        if ($request->has('logo')) {
            if ($request->logo && $request->logo != null) {
                $image_file = $request->logo;
                $destinationPath1 = 'public/logos/';
                $rand1 = rand(1, 100);
                $docImage1 = date('YmdHis') . $rand1 . "." . $image_file->getClientOriginalExtension();
                $image_file->move($destinationPath1, $docImage1);
                $img = $docImage1;
            } else {
                $img = '';
            }
        }
        $business = Business::where('id', $request->business_id)->first();
        $business->social_media = $request->social_media;
        $business->facebook_url = $request->facebook_url;
        $business->twitter_url = $request->twitter_url;
        $business->instagram_url = $request->instagram_url;
        $business->visitor_title = $request->visitor_title;
        $business->visitor_message = $request->visitor_message;
        // $business->threshold                        = $request->threshold;
        $business->public_review_message = $request->public_review_message;
        $business->public_review_thankyou_message = $request->public_review_thankyou_message;
        $business->prompt_message = $request->prompt_message;
        $business->private_feedback = $request->private_feedback;
        // $business->form_type                        = $request->form_type;
        $business->private_feedback_thankyou = $request->private_feedback_thankyou;
        $business->thumbsup_text = $request->thumbsup_text;
        $business->thumbsdown_text = $request->thumbsdown_text;
        $business->contact_info = $request->contact_info;
        $business->business_name = $request->business_name;
        $business->shortname = $request->shortname;
        $business->show_business_name = $request->show_business_name;
        $business->brand_color = $request->brand_color;
        if ($request->has('logo')) {
            $business->logo = $img;
        }
        $business->save();
        if ($request->has('notification_email')) {
            foreach ($request->notification_email as $notification_e) {
                if (array_key_exists('id', $notification_e)) {
                    $notification1 = FunnelNotification::where('id', $notification_e['id'])->first();
                    $notification1->user_id = $request->business_id;
                    $notification1->email = $notification_e['email'];
                    $notification1->status = 1;
                    $notification1->save();
                } else {
                    if (!blank($notification_e['email'])) {
                        $notification1 = new FunnelNotification();
                        $notification1->user_id = $request->business_id;
                        $notification1->email = $notification_e['email'];
                        $notification1->status = 1;
                        $notification1->save();
                    }
                }
            }
        }
        return response()->json('success', 200);
    }
    public function customerFeedback(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        $feedback = new CustomerFeedback();
        $feedback->user_id = Auth::user()->business;
        $feedback->name = $request->name;
        $feedback->action_taken = $request->action;
        $feedback->phone = $request->phone;
        $feedback->email = $request->email;
        $feedback->message = $request->message;
        $feedback->status = 1;
        $feedback->save();

        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('status', 1)->first();
        }
        $request->session()->flash('success', $business->private_feedback_thankyou);
        $user = User::where('id', Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Feedback Added by - ',
            'text' => 'Name: ' . $feedback->name,
            'url' => route('client.reviews'),
        ];
        Notification::send($user, new OffersNotification($notificationData));
        // return redirect()->back();
        return response()->json('success', 200);
    }
    public function deleteFeedback(Request $request, $id = NULL)
    {
        $feedback = CustomerFeedback::where('id', $id)->first();
        $feedback->status = 0;
        $feedback->save();

        $user = User::where('id', Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Feedback Deleted - ',
            'text' => 'Name: ' . $feedback->name,
            'url' => route('client.reviews'),
        ];
        Notification::send($user, new OffersNotification($notificationData));
        if ($user) {
            return response()->json(['data' => '', 'message' => 'Bisuness Delete Resuest Sent Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
    public function widgets(Request $request)
    {
        $page = 'Widgets';
        $icon = 'widgets.png';
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('status', 1)->first();
        }
        if (blank($business)) {
            $business = [];
            $data = [];
            return view('client.dashboard', compact('page', 'icon', 'data', 'business'));
        } else {
            return view('client.widgets.widgets', compact('page', 'icon', 'business'));
        }
    }
    public function errorPage(Request $request)
    {
        return view('404');
    }

    public function businessList(Request $request)
    {
        $page = 'Business List';
        $icon = 'business.png';
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('status', 1)->first();
        }
        $businessList = Business::where('client_id', Auth::user()->id)->where('status', 1)->get();
        return view('client.business.index', compact('page', 'icon', 'businessList', 'business'));
    }

    public function businessRequest($id)
    {
        $business = Business::where('id', $id)->first();
        $business->delete_request = 1;
        $insert = $business->save();

        $admin = User::where('id', 1)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Business Deleted Request Sent - ',
            'text' => 'Name: ' . $business->name,
            'url' => route('admin.business'),
        ];
        Notification::send($admin, new OffersNotification($notificationData));

        $data['businessData'] = $business;
        $data['type'] = 2;
        $subject = "Stop Subscription For " . $business->name;
        Mail::send('emails.edit_request', $data, function ($message) use ($business, $subject) {
            $message->from('gofwdreviews@gmail.com', 'FWD Review')
                ->to($business->client->email)
                ->subject($subject);
        });
        Mail::send('emails.edit_request', $data, function ($message) use ($admin, $subject) {
            $message->from('gofwdreviews@gmail.com', 'FWD Review')
                ->to($admin->email)
                ->subject($subject);
        });
        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Business';
        $log->log = $business->name . ' Deleted Request Sent Successfully';
        $log->save();
        return 1;
    }

    public function webhook(Request $request)
    {
        $dataSet = $request->all();
        FacadesLog::info($dataSet);
        $setting = Setting::first();
        $date = date('Y-m-d');
        if ($dataSet['event'] == "subscription-created") {
            $data = $dataSet['data'];
            $businessName = $data['business_name'];
            $placeId = $data['place_id'];
            $memberShipType = $data['membership']['period_type'];
            $memberShipId = $data['membership']['id'];
            $planDetailData = Plan::where('plan_id', $memberShipId)->first();
            if ($planDetailData  == null) {
                $createPlan = [
                    'plan_id' => $data['membership']['id'],
                    'price' => $data['membership']['price'],
                    'plan_period_type' => $data['membership']['period_type'],
                    'plan_text' => $data['membership']['content'],
                    'plan_title' => $data['membership']['title'],
                ];
                $planData = Plan::create($createPlan);
            } else {
                $planDetailData->price = $data['membership']['price'];
                $planDetailData->plan_period_type = $data['membership']['period_type'];
                $planDetailData->plan_title = $data['membership']['title'];
                $planDetailData->plan_text = $data['membership']['content'];
                $planDetailData->save();
                $planData = Plan::where('plan_id', $memberShipId)->first();
            }
            $createdDate = $data['created_at'];
            if ($memberShipType == "years") {
                $date = date('Y-m-d', strtotime($createdDate . ' + 1 year'));
            } else if ($memberShipType == "months") {
                $date = date('Y-m-d', strtotime($createdDate . ' + 1 month'));
            } else if ($memberShipType == "Days") {
                $date = date('Y-m-d', strtotime($createdDate . ' + 1 day'));
            } else if ($memberShipType == "weeks") {
                $date = date('Y-m-d', strtotime($createdDate . ' + 7 day'));
            }
            $email = $data['member']['email'];
            $user = User::where('email', $email)->first();
            if ($user) {
                $businessDetail = Business::where('client_id', $user->id)->where('place_id', $placeId)->first();
                if (isset($businessDetail)) {
                    $businessDetail->sub_end_date = $date;
                    $businessDetail->name = $businessName;
                    $businessDetail->status = 1;
                    $businessDetail->plan_id = $planData->id;
                    $businessDetail->api_key = $setting->api_key;
                    $businessDetail->payment_option = "online";
                    $businessDetail->subscription_id = $data['id'];
                    $businessDetail->save();
                    $businessID = $businessDetail->id;
                } else {
                    $create = [
                        'name' => $businessName,
                        'client_id' => $user->id,
                        'plan_id' => $planData->id,
                        'api_key' => $setting->api_key,
                        'sub_end_date' => $date,
                        'payment_option' => "online",
                        'status' => 1,
                        'active' => 1,
                        'add_type' => 'WEBSITE ONBOARDING',
                        'transaction_id' => $data['subscr_id'],
                        'subscription_id' => $data['id'],
                        'place_id' => $placeId,
                        'payment_date_time' => Carbon::now(),
                    ];
                    $businessCreate = Business::create($create);
                    $businessID = $businessCreate->id;
                }
                $paymentUpdate = [
                    'membership' => $data['membership']['id'],
                    'expiry_date' => $date,
                    'tax_amount' => $data['membership']['price'],
                    'transaction_number' => $data['subscr_id'],
                    'gateway' => $data['gateway'],
                    'user_id' => $user->id,
                    'subscription_text' => $data['membership']['content'],
                    'business_id' => $businessID,
                    'plan_title' => $data['membership']['title'],
                ];
                Payment::create($paymentUpdate);
                $clientDetail = User::where('id', $user->id)->first();
                if ($clientDetail->business == 0) {
                    $clientDetail->business = $businessID;
                    $clientDetail->save();
                }
            } else {
                $randomString = Str::random(9);
                $member = $data['member'];
                $dataUser = [
                    'email' => $member['email'],
                    'name' => $member['display_name'],
                    'password' => Hash::make($randomString),
                    'role' => 2,
                    'status' => 1,
                    'sub_end_date' => $date,
                ];
                $userDetail = User::create($dataUser);
                if ($userDetail) {
                    $role = [
                        'user_id' => $userDetail->id,
                        'role_id' => 2,
                    ];
                    RoleUser::create($role);
                    $businessDetail = Business::where('client_id', $userDetail->id)->where('place_id', $placeId)->first();
                    if (isset($businessDetail)) {
                        $businessDetail->sub_end_date = $date;
                        $businessDetail->name = $businessName;
                        $businessDetail->subscription_id = $data['id'];
                        $businessDetail->plan_id = $planData->id;
                        $businessDetail->api_key = $setting->api_key;
                        $businessDetail->payment_option = "online";
                        $businessDetail->save();
                        $businessID = $businessDetail->id;
                    } else {
                        $create = [
                            'name' => $businessName,
                            'client_id' => $userDetail->id,
                            'api_key' => $setting->api_key,
                            'payment_option' => "online",
                            'sub_end_date' => $date,
                            'plan_id' => $planData->id,
                            'status' => 1,
                            'active' => 1,
                            'add_type' => 'WEBSITE ONBOARDING',
                            'transaction_id' => $data['subscr_id'],
                            'subscription_id' => $data['id'],
                            'place_id' => $placeId,
                            'payment_date_time' => Carbon::now(),
                        ];
                        $userBusinessCreate = Business::create($create);
                        $businessID = $userBusinessCreate->id;
                    }
                    $paymentUpdate = [
                        'membership' => $data['membership']['id'],
                        'expiry_date' => $date,
                        'tax_amount' => $data['membership']['price'],
                        'transaction_number' => $data['subscr_id'],
                        'gateway' => $data['gateway'],
                        'user_id' => $userDetail->id,
                        'subscription_text' => $data['membership']['content'],
                        'business_id' => $businessID,
                        'plan_title' => $data['membership']['title'],
                    ];
                    Payment::create($paymentUpdate);
                    
                    $data = [
                        'name' => $userDetail->first_name . " " . $userDetail->last_name,
                        'password' => $randomString,
                        'emailText' => $userDetail->email,
                    ];
                    $email = $userDetail->email;
                    Mail::send('emails.register', $data, function ($message) use ($email) {
                        $message->from('reviews@reviewmgr.com', 'FWD Reviews')
                            ->to($email)
                            ->subject('Welcome to FWD Reviews');
                    });
                }
                $clientDetail = User::where('id', $userDetail->id)->first();
                if ($clientDetail->business == 0) {
                    $clientDetail->business = $businessID;
                    $clientDetail->save();
                }
            }
        }
        return 1;
    }
    public function editClient(Request $request)
    {
        $page = 'Clients';
        $icon = 'clients.png';
        $client = User::where('id', Auth()->user()->id)->first();
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->where('sub_end_date', '>=', date('Y-m-d'))->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('sub_end_date', '>=', date('Y-m-d'))->where('status', 1)->first();
        }
        return view('client.profile.edit', compact('business', 'page', 'icon', 'client'));
    }

    public function viewClient(Request $request)
    {
        $page = 'Clients';
        $icon = 'clients.png';
        $client = User::where('id', Auth()->user()->id)->first();
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->where('sub_end_date', '>=', date('Y-m-d'))->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('status', 1)->where('sub_end_date', '>=', date('Y-m-d'))->first();
        }
        return view('client.profile.view', compact('business', 'page', 'icon', 'client'));
    }
    public function updateClient(Request $req)
    {
        $req->validate([
            'name'                => 'required',
            'email'               => 'required',
            'phone'               => 'required|unique:users,phone,' . $req->user_id,
        ]);
        if ($req->status == "on") {
            $status = 1;
        } else {
            $status = 0;
        }
        $user = User::where('id', $req->user_id)->first();
        $user->name     = $req->name;
        $user->phone    = $req->phone;
        $user->email    = $req->email;
        if ($req->has('password')) {
            
            $user->password = Hash::make($req->password);
        }
        $user->status   = $status;
        $insert         = $user->save();

        $admin = User::where('id', Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Client';
        $log->log       = $user->name . ' Updated Successfully';
        $log->save();
        if ($user) {
            Session::flash('success', 'Profile Updated Successfully.');
            return redirect()->route('client.view');
        }
        Session::flash('error', 'Something Went to Wrong.');
        return redirect()->back();
    }
    public function transactionDetail(Request $request)
    {
        $page = "Transaction Detail";
        $icon = 'clients.png';
        $client = User::where('id', Auth()->user()->id)->first();
        if (Auth::user()->business != 0) {
            $business = Business::where('id', $client->business)->first();
        } else {
            $business = Business::where('client_id', $client->id)->where('status', 1)->first();
        }
        $paymentList = Payment::where('user_id', $client->id)->where('business_id', $business->id)->get();
        return view('client.payment.payment_detail', compact('page', 'icon', 'business', 'paymentList'));
    }

    public function reviewsExport(Request $request)
    {
        $search = $request->search;
        $date = "";
        $type = 0;
        if (strtotime($search) !== false && strtotime($search) !== -1) {
            $type = 1;
            $date = strtotime($search);
        }
        $feedbackList = CustomerFeedback::when($date == null && $type == 0 && $search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('action_taken', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('message', 'like', '%' . $search . '%');
        })->when($date && $type == 1, function ($query) use ($date) {
            $query->whereDate('created_at', date('Y-m-d', $date));
        })->where('user_id', Auth::user()->business)->where('status', 1)->latest()->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Customer Feedback.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $columns = array('Date', 'Customer Name', 'Customer Action', 'Customer Phone', 'Customer Email', 'Message');
        $callback = function () use ($feedbackList, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($feedbackList as $feedback) {
                fputcsv($file, array($feedback->created_at, $feedback->name, $feedback->action_taken, $feedback->phone, $feedback->email, $feedback->message));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function removeLogoFunnel(Request $request)
    {
        $business = Business::where('id', $request->business_id)->first();
        if (isset($business)) {
            $business->logo = "";
            $business->save();
            return response()->json(['data' => '', 'message' => 'Logo removed successfully.', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something went to wrong.', 'status' => 0], 500);
    }
}
