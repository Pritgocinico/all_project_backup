<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Business;
use App\Models\Setting;
use App\Models\UserRecipient;
use App\Models\UserEmailSetting;
use Mail;
use App\Mail\SendMailable;
use Carbon\Carbon;
use App\Models\CustomerFeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    
    public function analyticReport(Request $request){
        $page = 'Analytics';
        $icon = 'dashboard.png';
        if(Auth::user()->business != 0){
            $business = Business::where('id',Auth::user()->business)->first();
        }else{
            $business = Business::where('client_id',Auth::user()->id)->where('status',1)->first();
        }

        $monthWiseReviews = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthWiseReviews[$month] = 0;
            }

        $response1 = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get('https://maps.googleapis.com/maps/api/place/details/json',[
            'place_id'  => $business->place_id,
            'key'       => $business->api_key,
        ]);
        $jsonData1 = json_decode($response1->getBody(), true);
        $message = '';
        if(array_key_exists('result',$jsonData1)){
            $data = $jsonData1['result']['reviews'];
        }else{
            $data = [];
            $message = $jsonData1['error_message'];
        }

        $feedbacks = CustomerFeedback::where('user_id',Auth::user()->business)->where('status',1)->get();
        
        $recentTimeFrame = strtotime('-4 months');
        $recentReviewCount = 0;
        foreach ($data as $review) {
            $createdAt = strtotime($review['time']);
            if ($createdAt >= $recentTimeFrame) {
                $recentReviewCount++;
            }
        }
        
        return view('client.analytic_report.analytic', compact('page', 'icon', 'business', 'feedbacks', 'data', 'recentReviewCount'));
    }

    public function generatedReport(Request $request){
        $page = 'Generated Reports';
        $icon = 'dashboard.png';
        if(Auth::user()->business != 0){
            $business = Business::where('id',Auth::user()->business)->first();
        }else{
            $business = Business::where('client_id',Auth::user()->id)->where('status',1)->first();
        }

        return view('client.generated_report.generated', compact('page', 'icon', 'business'));
    }
}
