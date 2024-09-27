<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Category;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlansController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function plans(){
        $plans = Plan::orderBy('id','Desc')->get();
        $page  = 'Health Plans';
        $icon  = 'plan.png';
        $companies = Company::all();
        // if(Auth::user()->role == 1){
            return view('admin.health_plans.plans',compact('plans','companies','page','icon'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addPlan(Request $request){
        $page = 'Add Health Plan';
        $icon = 'plan.png';
        $companies = Company::all();
        // if(Auth::user()->role == 1){
            return view('admin.health_plans.add_plan',compact('page','icon','companies'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addPlanData(Request $req){
        $req->validate([
            'name'                => 'required',
            'insurance_company'             => 'required|not_in:0',
        ]);

        $plan = new Plan();
        $plan->name             = $req->name;
        $plan->description      = $req->description;
        $plan->company          = $req->insurance_company;
        $plan->status           = 1;
        $plan->save();

        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Health Plans';
        $log->log       = 'Health Plan ('.$plan->name .') Created';
        $log->save();
        if(Auth::user()->role == 1){
            return redirect()->route('admin.plans')->with('success', 'Plan Added Successfully.');
        }else{
            return redirect()->route('login');
        }
    }
    public function editPlan(Request $request, $id = NULL){
        $page = 'Edit Health Plans';
        $icon = 'plans.png';
        $plan = Plan::where('id',$id)->first();
        $companies = Company::all();
        // if(Auth::user()->role == 1){
            return view('admin.health_plans.edit_plan',compact('plan','companies','page','icon'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function updatePlan(Request $req){
        $req->validate([
            'name'                => 'required',
            'insurance_company'   => 'required',
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        $plan = Plan::where('id',$req->id)->first();
        $plan->name         = $req->name;
        $plan->description  = $req->description;
        $plan->company      = $req->insurance_company;
        $plan->status       = $status;
        $plan->save();

        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = '';
        $log->log       = 'Health Plan ('.$plan->name .') Updated';
        $log->save();
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.plans')->with('success', 'Plan Updated Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function deletePlan($id){
        $plan = Plan::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Health Plan';
        $log->log       = 'Health Plan ('.$plan->name .') has been deleted';
        $log->save();
        $plan->delete();
        return 1;
    }
    public function getInsurancePlan(Request $req){
        $id = $req->id;
        $plans = Plan::where('company',$id)->where('status',1)->get();
        $html = '';
        // $html .= '<option value="0">Select Health Plan...</option>';
        foreach($plans as $plan)
        {
            $html .= '<option value="'.$plan->id.'">'.$plan->name.'</option>';
        }
        return $html;
    }
}
