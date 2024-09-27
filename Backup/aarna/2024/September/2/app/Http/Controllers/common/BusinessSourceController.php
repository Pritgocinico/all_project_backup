<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Company;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\BusinessSource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BusinessSourceController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function businessSource(){
        $business_source = BusinessSource::orderBy('id','Desc')->get();
        $page  = 'Business Source';
        $icon  = 'business-source.png';
        $categories = Category::where('parent','==',0)->get();
        $sub_categories = Category::where('parent','!=',0)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        if(Auth::user()->role == 1){
            return view('admin.business_source.business_source',compact('business_source','page','icon','categories','sub_categories','companies','customers','plans'));
        }else{
            return redirect()->route('login');
        }
    }
    public function addBusinessSource(Request $request){
        $page = 'Add Business Source';
        $icon = 'business-source.png';
        $categories = Category::where('insurance_type',1)->where('parent','==',0)->get();
        $healthCategory = Category::where('insurance_type',2)->where('parent','==',0)->get();
        $id = $categories[0]['id'];
        $sub_categories = Category::where('parent',$id)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        if(Auth::user()->role == 1){
            return view('admin.business_source.add_business_source',compact('healthCategory','page','icon','categories','sub_categories','companies','customers','plans'));
        }else{
            return redirect()->route('login');
        }
    }
    public function addBusinessSourceData(Request $request){
        if($request->has('insurance_type') && $request->insurance_type == 1){
            $request->validate([
                'customer'                      => 'required|not_in:0',
                'vehicle_chassis_no'            => 'required',
                'gross_premium_amount'          => 'required',
                'net_premium_amount'            => 'required',
                'risk_start_date'               => 'required',
                'motor_pyp_no'                  => 'required_if:business_type,2|required_if:business_type,3',
                'motor_pyp_insurance_company'   => 'required_if:business_type,2|required_if:business_type,3',
                'motor_pyp_expiry_date'         => 'required_if:business_type,2|required_if:business_type,3',
            ]);
        }else{
            $request->validate([
                'health_customer'               => 'required|not_in:0',
                'health_plan'                   => 'required|not_in:0',
                'health_gross_premium_amount'   => 'required',
                'health_net_premium_amount'     => 'required',
                'health_risk_start_date'        => 'required',
                'pyp_no'                        => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_insurance_type'            => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_expiry_date'               => 'required_if:business_type,2|required_if:business_type,3',
            ]);
        }
        if($request->insurance_type == 1){
            $category               = $request->motor_category;
            $sub_category           = $request->motor_subcategory;
            $insurance_company      = $request->insurance_company;
            $customer               = $request->customer;
            $business_type          = $request->business_type;
            $risk_start_date        = $request->risk_start_date;
            $gross_premium_amount   = $request->gross_premium_amount;
            $net_premium_amount     = $request->net_premium_amount;
            $pyp_no                 = $request->motor_pyp_no;
            $pyp_insurance_company  = $request->motor_pyp_insurance_company;
            $pyp_expiry_date        = $request->motor_pyp_expiry_date;
        }else{
            $category               = $request->health_category;
            $sub_category           = 0;
            $insurance_company      = $request->health_insurance_company;
            $customer               = $request->health_customer;
            $business_type          = $request->health_business_type;
            $risk_start_date        = $request->health_risk_start_date;
            $gross_premium_amount   = $request->health_gross_premium_amount;
            $net_premium_amount     = $request->health_net_premium_amount;
            $pyp_no                 = $request->pyp_no;
            $pyp_insurance_company  = $request->pyp_insurance_company;
            $pyp_expiry_date        = $request->pyp_expiry_date;
        }
        $source = new BusinessSource();
        $source->insurance_type         = $request->insurance_type;
        $source->category               = $category;
        $source->business_type          = $business_type;
        $source->customer               = $customer;
        $source->sub_category           = $sub_category;
        $source->company                = $insurance_company;
        $source->risk_start_date        = $risk_start_date;
        $source->vehicle_chassic_no     = $request->vehicle_chassis_no;
        $source->gross_premium_amount   = $gross_premium_amount;
        $source->net_premium_amount     = $net_premium_amount;
        $source->health_plan            = $request->health_plan;
        $source->pyp_no                 = $pyp_no;
        $source->pyp_insurance_company  = $pyp_insurance_company;
        $source->pyp_expiry_date        = $pyp_expiry_date;
        $source->status                 = 1;
        $source->save();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Business Source';
        $log->log       = 'Business Source Created';
        $log->save();
        if(Auth::user()->role == 1){
            return redirect()->route('admin.business.source')->with('success', 'Business Source Created Successfully.');
        }else{
            return redirect()->route('login');
        }
    }
    public function editBusinessSource(Request $request, $id = NULL){
        $page = 'Edit Business Source';
        $icon = 'business-source.png';
        $categories = Category::where('insurance_type',1)->where('parent','==',0)->get();
        $healthCategory = Category::where('insurance_type',2)->where('parent','==',0)->get();
        $cat_id = $categories[0]['id'];
        $sub_categories = Category::where('parent',$cat_id)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        $sources = BusinessSource::where('id',$id)->first();
        if(Auth::user()->role == 1){
            return view('admin.business_source.edit_business_source',compact('healthCategory','page','icon','categories','sub_categories','companies','customers','plans','sources'));
        }else{
            return redirect()->route('login');
        }
    }
    public function updateBusinessSource(Request $request){
        // print_r($request->all());
        if($request->has('insurance_type') && $request->insurance_type == 1){
            $request->validate([
                'customer'                      => 'required|not_in:0',
                'vehicle_chassis_no'            => 'required',
                'gross_premium_amount'          => 'required',
                'net_premium_amount'            => 'required',
                'risk_start_date'               => 'required',
                'motor_pyp_no'                  => 'required_if:business_type,2|required_if:business_type,3',
                'motor_pyp_insurance_company'   => 'required_if:business_type,2|required_if:business_type,3',
                'motor_pyp_expiry_date'         => 'required_if:business_type,2|required_if:business_type,3',
            ]);
        }else{
            $request->validate([
                'health_customer'               => 'required|not_in:0',
                'health_plan'                   => 'required|not_in:0',
                'health_gross_premium_amount'   => 'required',
                'health_net_premium_amount'     => 'required',
                'health_risk_start_date'        => 'required',
                'pyp_no'                        => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_insurance_type'            => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_expiry_date'               => 'required_if:business_type,2|required_if:business_type,3',
            ]);
        }
        if($request->insurance_type == 1){
            $category               = $request->motor_category;
            $sub_category           = $request->motor_subcategory;
            $insurance_company      = $request->insurance_company;
            $customer               = $request->customer;
            $business_type          = $request->business_type;
            $risk_start_date        = $request->risk_start_date;
            $gross_premium_amount   = $request->gross_premium_amount;
            $net_premium_amount     = $request->net_premium_amount;
            $pyp_no                 = $request->motor_pyp_no;
            $pyp_insurance_company  = $request->motor_pyp_insurance_company;
            $pyp_expiry_date        = $request->motor_pyp_expiry_date;
        }else{
            $category               = $request->health_category;
            $sub_category           = 0;
            $insurance_company      = $request->health_insurance_company;
            $customer               = $request->health_customer;
            $business_type          = $request->health_business_type;
            $risk_start_date        = $request->health_risk_start_date;
            $gross_premium_amount   = $request->health_gross_premium_amount;
            $net_premium_amount     = $request->health_net_premium_amount;
            $pyp_no                 = $request->pyp_no;
            $pyp_insurance_company  = $request->pyp_insurance_company;
            $pyp_expiry_date        = $request->pyp_expiry_date;
        }
        $source = BusinessSource::where('id',$request->id)->first();
        $source->insurance_type         = $request->insurance_type;
        $source->category               = $category;
        $source->business_type          = $business_type;
        $source->customer               = $customer;
        $source->sub_category           = $sub_category;
        $source->company                = $insurance_company;
        $source->risk_start_date        = $risk_start_date;
        $source->vehicle_chassic_no     = $request->vehicle_chassis_no;
        $source->gross_premium_amount   = $gross_premium_amount;
        $source->net_premium_amount     = $net_premium_amount;
        $source->health_plan            = $request->health_plan;
        $source->pyp_no                 = $pyp_no;
        $source->pyp_insurance_company  = $pyp_insurance_company;
        $source->pyp_expiry_date        = $pyp_expiry_date;
        $source->status                 = 1;
        $source->save();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Business Source';
        $log->log       = 'Business Source Updated';
        $log->save();
        if(Auth::user()->role == 1){
            return redirect()->route('admin.business.source')->with('success', 'Business Source Updated Successfully.');
        }else{
            return redirect()->route('login');
        }
    }
    public function deleteBusinessSource($id){
        $business_source = BusinessSource::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Business Source';
        $log->log       = 'Business Source has been Deleted';
        $log->save();
        $business_source->delete();
        return 1;
    }
}
