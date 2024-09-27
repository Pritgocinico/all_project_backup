<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Session;
use App\Models\User;
use App\Models\Plan;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as FacadesLog;

class PlanController extends Controller
{
    private $apiUrl;
    private $apiKey;

    public function __construct()
    {
        $setting=Setting::first();
        view()->share('setting', $setting);
        $this->apiUrl = env('MEMBER_PRESS_API_URL');
        $this->apiKey = env('MEMBER_PRESS_API_KEY');
    }

    public function index(){
        $page = "Plan Detail";
        $icon = "dashboard.pmg";
        $plans = Plan::all();
        return view('admin.plans.index',compact('page','icon','plans'));
    }
    public function storePlan(Request $request)
    {
        $randomNumber = random_int(0000000, 9999999);
        $create = [
            'plan_id' => $randomNumber,
            'plan_period_type' => $request->plan_period_type,
            'price' => $request->price,
            'plan_title' => $request->plan_title,
        ];
        $insert = Plan::create($create);
        if ($insert) {
            Session::flash('success', 'Plan Created Successfully.');
            return redirect()->back();
        }
        Session::flash('error', 'Something Went to Wrong.');
        return redirect()->back();
    }

    public function editPlan(Request $request)
    {
        $id = $request->plan_id;
        $planDetail = Plan::where('id', $id)->first();
        return $planDetail;
    }
    public function updatePlan(Request $request)
    {
        $planDetail = Plan::where('id', $request->plan_id)->first();
        $planDetail->plan_period_type = $request->plan_period_type;
        $planDetail->plan_title = $request->plan_title;
        $planDetail->price = $request->price;
        $planDetail->status = $request->status == "on" ? '1' : '0';
        $planDetail->save();
        if ($planDetail) {
            $endpoint = "{$this->apiUrl}/memberships/{$planDetail->plan_id}";
            $data = [
                'title' => $request->plan_title,
                'price' => $request->price,
                'period_type' => $request->plan_period_type,
                'status' => $request->status == "on" ? "active" : "Inactive",
            ];
            try {
                $response = Http::withHeaders([
                    'MEMBERPRESS-API-KEY' => $this->apiKey,
                ])->post($endpoint, $data);
                FacadesLog::info($response->json());
            } catch (\Throwable $th) {
                FacadesLog::info($th);
            }
            
            Session::flash('success', 'Plan Updated Successfully.');
            return redirect()->back();
        }
        Session::flash('error', 'Something Went to Wrong.');
        return redirect()->back();
    }
    public function deletePlan($id)
    {
        $plan = Plan::where('id', $id)->first();
        $plan->delete();

        $admin = User::where('id', Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Plan Deleted - ',
            'text' => 'Name: ' . $plan->plan_title,
            'url' => route('admin.business'),
        ];

        Notification::send($admin, new OffersNotification($notificationData));
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Plan';
        $log->log       = $plan->plan_title . ' Deleted Successfully';
        $log->save();
        if ($plan) {
            return response()->json(['data' => '', 'message' => 'Plan Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function updatePlanDetail(Request $request)
    {
        $planDetail = Plan::where('plan_id', $request->id)->first();
        if ($planDetail) {
            $planDetail->plan_period_type = $request->plan_period_type;
            $planDetail->plan_title = $request->plan_title;
            $planDetail->price = $request->price;
            $planDetail->save();
            FacadesLog::info('Plan Updated Successfully.');
            return 1;
        }
        FacadesLog::info('Something Went to Wrong.');
        return 0;
    }

    public function getPlan(Request $request){
        $id = $request->plan;
        $plan = Plan::where('id', $id)->first();
        return response()->json(['data' => $plan, 'message' => '', 'status' => 1], 200);
    }
}
