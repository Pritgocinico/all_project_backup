<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Company;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\BusinessSource;
use App\Models\SourcingAgent;
use App\Models\Covernote;
use App\Models\Policy;
use App\Models\PolicyParameter;
use App\Models\PolicyAttachment;
use App\Models\PolicyDocument;
use App\Models\PolicyPayment;
use App\Models\Endorsement;
use App\Models\Claim;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use URL;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BusinessSourceReport;
use App\Exports\endorsementCSVExport;
use App\Exports\claimCSVExport;
use App\Exports\policyCSVExport;
use App\Exports\policyHealthCSVExport;
use PDF;
class ReportController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function Reports(Request $request){
        $page = 'Reports';
        $icon = '';
        $categories = Category::where('parent','==',0)->get();
        $id = $categories[0]['id'];
        $sub_categories = Category::where('parent',$id)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        $team_leads = User::where('team_lead',1)->orderBy('id','Desc')->get();
        $agents = SourcingAgent::orderBy('id','Desc')->get();
        return view('admin.reports.reports',compact('page','icon','categories','sub_categories','companies','customers','plans','agents'));
    }
    public function businessSourceReports(Request $request){
        // echo '<pre>';
        // print_r($request->all());
        // exit;
        $type = $request->business_report_type;
        $business_source = BusinessSource::where('insurance_type',$request->insurance_type);
        if($request->motor_category != 0 && $request->insurance_type == 1){
            $business_source->where('category',$request->motor_category);
        }
        if($request->motor_subcategory != 0 && $request->insurance_type == 1){
            $business_source->where('sub_category',$request->motor_subcategory);
        }
        if($request->insurance_company != 0){
            $business_source->where('company',$request->insurance_company);
        }
        if($request->customer != 0){
            $business_source->where('customer',$request->customer);
        }
        if($request->business_type != 0 && $request->insurance_type == 1){
            $business_source->where('business_type',$request->business_type);
        }
        if($request->vehicle_chassis_no != '' && $request->insurance_type == 1){
            $business_source->where('vehicle_chassic_no',$request->vehicle_chassis_no);
        }
        if($request->business_source_from != '' && $request->business_source_to != ''){
            $business_source->whereBetween('created_at', [$request->business_source_from, $request->business_source_to]);
        }
        if($request->health_category != 0 && $request->insurance_type == 2){
            $business_source->where('category',$request->health_category);
        }
        if($request->health_business_type != 0 && $request->insurance_type == 2){
            $business_source->where('business_type',$request->health_business_type);
        }
        if($request->health_plan != '' && $request->insurance_type == 2){
            $business_source->where('health_plan',$request->health_plan);
        }
        $data = $business_source->get();
        if($type == 'csv'){
            return Excel::download(new BusinessSourceReport($data), 'business-source-report.xlsx');
        }
        // print_r($data);
    }
    public function endorsementReports(Request $request){
        $type = $request->endorsement_report_type;
        $endorsement = Endorsement::orderBy('id','DESC');
        if($request->endorsement_start_date != '' && $request->endorsement_end_date != ''){
            $start_date = date('Y-m-d',strtotime($request->endorsement_start_date));
            $end_date = date('Y-m-d',strtotime($request->endorsement_end_date));
            $endorsement->whereBetween('created_at',[$request->endorsement_start_date,$request->endorsement_end_date]);
        }
        if($request->insurance_company != 0){
            $endorsement->whereHas('policy', function($query1) use ($request){
                    $query1->where('company', $request->insurance_company);
            });
        }
        if($request->customer_name != ''){
            $endorsement->whereHas('policy', function($query1) use ($request){
                $query1->with('customers');
                $query1->whereHas('customers', function($query2) use ($request){
                    $query2->where('name', 'LIKE','%'.$request->customer_name.'%');
                });
            });
        }
        if($request->endorsement_details != ''){
            $endorsement->where('details','LIKE','%'.$request->endorsement_details.'%');
        }
        if(Auth::user()->role == 3){
            $endorsement->whereHas('policy', function($query1) use ($request){
                $query1->where('agent', Auth::user()->id);
            });
            $data = $endorsement->get();
        }elseif(Auth::user()->role == 1){
            $data = $endorsement->get();
        }
        $data = $endorsement->get();
        if($type == 'csv'){
            return Excel::download(new endorsementCSVExport($data), 'endorsement-report.csv');
        }else{
            $records = [];
            if(!blank($data)){
                foreach($data as $source){
                    $company = Company::where('id',$source->policy->company)->first();
                    if(!blank($company)){
                        $cmp = $company->name;
                    }else{
                        $cmp = '';
                    }
                    $customer = Customer::where('id',$source->policy->customer)->first();
                    $records[] = [
                        'endorsement'       =>  date('d-m-Y',strtotime($source->created_at)),
                        'company_name'      =>  $cmp,
                        'details'           =>  $source->details,
                        'policy_no'         =>  $source->policy->policy_no,
                        'customer_name'     =>  $customer->name,
                    ];
                }
            }
            $requests = [];
            $requests['start_date'] = $request->endorsement_start_date;
            $requests['end_date'] = $request->endorsement_end_date;
            $cmp = Company::where('id',$request->insurance_company)->first();
            if(!blank($cmp)){
                $company_name = $cmp->name;
            }else{
                $company_name = "";
            }
            $requests['insurance_company'] = $company_name;
            $requests['customer_name'] = $request->customer_name;
            $requests['endorsement_details'] = $request->endorsement_details;

            view()->share(['records'=>$records,'requests'=>$requests]);
            $customPaper = [0, 0, 567.00, 500.80];
            $pdf = PDF::loadView('admin.reports.endorsement_pdf_report', $records)->setPaper('a4', 'landscape');
            // return view('admin.payout.payout_pdf_report');
            return $pdf->download('endorsement-report.pdf');
        }
    }
    public function claimReports(Request $request){
        $type = $request->claim_report_type;
        $claim = Claim::orderBy('id','DESC');
        if($request->claim_start_date != '' && $request->claim_end_date != ''){
            $start_date = date('Y-m-d',strtotime($request->claim_start_date));
            $end_date = date('Y-m-d',strtotime($request->claim_end_date));
            $claim->whereBetween('created_at',[$request->claim_start_date,$request->claim_end_date]);
        }
        if($request->has($request->claim_status)){
            $claim->where('claim_status',$request->claim_status);
        }
        if($request->has($request->claim_type)){
            $claim->where('claim_type',$request->claim_type);
        }
        if($request->insurance_company != 0){
            $claim->whereHas('policy', function($query1) use ($request){
                    $query1->where('company', $request->insurance_company);
            });
        }
        if($request->customer_name != ''){
            $claim->whereHas('policy', function($query1) use ($request){
                $query1->with('customers');
                $query1->whereHas('customers', function($query2) use ($request){
                    $query2->where('name', 'LIKE','%'.$request->customer_name.'%');
                });
            });
        }
        if(Auth::user()->role == 3){
            $claim->whereHas('policy', function($query1) use ($request){
                $query1->where('agent', Auth::user()->id);
            });
            $data = $claim->get();
        }elseif(Auth::user()->role == 1){
            $data = $claim->get();
        }
        if($type == 'csv'){
            return Excel::download(new claimCSVExport($data), 'claim-report.csv');
        }else{
            $records = [];
            if(!blank($data)){
                foreach($data as $source){
                    $company = Company::where('id',$source->policy->company)->first();
                    if(!blank($company)){
                        $cmp = $company->name;
                    }else{
                        $cmp = '';
                    }
                    $customer = Customer::where('id',$source->policy->customer)->first();
                    $records[] = [
                        'claim_no'          =>  $source->claim_no,
                        'claim_date'        =>  date('d-m-Y',strtotime($source->created_at)),
                        'company_name'      =>  $cmp,
                        'claim_type'        =>  $source->claim_type,
                        'claim_status'      =>  $source->claim_status,
                        'policy_no'         =>  $source->policy->policy_no,
                        'customer_name'     =>  $customer->name,
                    ];
                }
            }
            $requests = [];
            $requests['start_date'] = $request->claim_start_date;
            $requests['end_date'] = $request->claim_end_date;
            $cmp = Company::where('id',$request->insurance_company)->first();
            if(!blank($cmp)){
                $company_name = $cmp->name;
            }else{
                $company_name = "";
            }
            $requests['insurance_company'] = $company_name;
            $requests['customer_name'] = $request->customer_name;
            $requests['claim_type'] = $request->claim_type;
            $requests['claim_status'] = $request->claim_status;
            view()->share(['records'=>$records,'requests'=>$requests]);
            $customPaper = [0, 0, 567.00, 500.80];
            $pdf = PDF::loadView('admin.reports.claim_pdf_report', $records)->setPaper('a4', 'landscape');
            // return view('admin.payout.payout_pdf_report');
            return $pdf->download('claim-report.pdf');
        }
    }
    public function policyReports(Request $request){
        $type = $request->policy_report_type;
        $query = Policy::orderBy('id','DESC');
        if($request->has('insurance_type')){
            $query->where('insurance_type',$request->insurance_type);
        }
        if($request->has('agent_name') && $request->agent_name !== '0'){
            $query->where('agent',$request->agent_name);
        }
        if($request->has('motor_category') && $request->motor_category != 0 && $request->insurance_type == 1){
            $query->where('category',$request->motor_category);
        }
        if($request->has('motor_subcategory') && $request->motor_subcategory != 0 && $request->insurance_type == 1){
            $query->where('sub_category',$request->motor_subcategory);
        }
        if($request->has('health_category') && $request->health_category != 0 && $request->insurance_type == 2){
            $query->where('category',$request->health_category);
        }
        if($request->has('insurance_company') && $request->insurance_company != 0){
            $query->where('company',$request->insurance_company);
        }
        if($request->has('customer') && $request->customer != 0){
            $query->where('customer',$request->customer);
        }
        if($request->has('business_type') && $request->business_type != 0 && $request->insurance_type == 1){
            $query->where('business_type',$request->business_type);
        }
        if($request->has('health_business_type') && $request->health_business_type != 0 && $request->insurance_type == 1){
            $query->where('business_type',$request->health_business_type);
        }
        if($request->has('vehicle_chassis_no') && $request->vehicle_chassis_no != '' && $request->insurance_type == 1){
            $query->where('vehicle_chassis_no',$request->vehicle_chassis_no);
        }
        if($request->has('health_plan') && $request->health_plan != 0 && $request->insurance_type == 2){
            $query->where('health_plan',$request->health_plan);
        }
        if($request->policy_created_from != '' && $request->policy_created_to != ''){
            $start_date = date('Y-m-d',strtotime($request->policy_created_from));
            $end_date = date('Y-m-d',strtotime($request->policy_created_to));
            $query->whereBetween('created_at',[$start_date,$end_date]);
        }
        if($request->policy_expiry_from != '' && $request->policy_expiry_to != ''){
            $expiry_start_date = date('Y-m-d',strtotime($request->policy_expiry_from));
            $expiry_end_date = date('Y-m-d',strtotime($request->policy_expiry_to));
            // dd($expiry_end_date,$expiry_start_date);
            $query->whereBetween('risk_end_date',[$expiry_start_date,$expiry_end_date]);
            // $query->whereBetween('risk_end_date',[$expiry_start_date,$expiry_end_date]);
        }
        if ($request->created_date != '') {
            $created_dates = date('Y-m-d',strtotime($request->created_date));
            $query->whereDate('created_date',$created_dates);
            // $query->whereBetween('created_date',[$created_dates,$created_dates]);
        }
        if(Auth::user()->role == 3){
            // $query->where('agent', Auth::user()->id);
            $data = $query->get();
        }elseif(Auth::user()->role == 1){
            // dd($query->toSql());
            $data = $query->get();
        }
        // $sqlQuery = $query->toSql();
        // $bindingValues = $query->getBindings();
        // dd($sqlQuery, $bindingValues);
        // $data = $query->get();
        // print_r($data);
        if($type == 'csv'){
            if($request->insurance_type == 1){
                return Excel::download(new policyCSVExport($query), 'policy-report.csv');
            }else{
                return Excel::download(new policyHealthCSVExport($query), 'policy-report.csv');
            }
        }else{
            $records = [];
            if(!blank($data)){
                // dd($data);
                foreach($data as $source){
                    $customer = Customer::where('id',$source->customer)->first();
                    // $records = [];
                    if($request->insurance_type == 2){
                        if($source->category == 1){
                            $category = 'Base';
                        }elseif($source->category == 2){
                            $category = 'Personal Accident';  
                        }else{
                            $category = 'Super Popup'; 
                        }
                        $plan = Plan::where('id',$source->health_plan)->first();
                        if(!blank($plan)){
                            $plan_name = $plan->name;
                        }else{
                            $plan_name = "";
                        }
                        $records[] = array(
                            'category'      =>  $category,
                            'plan_name'     =>  $plan_name,
                            'start_date'    =>  date('d-m-Y',strtotime($source->risk_start_date)),
                            'end_date'      =>  date('d-m-Y',strtotime($source->risk_end_date)),
                            'policy_no'     =>  $source->policy_no,
                            'customer'      =>  $source->customers->name,
                            'net_premium'   =>  $source->net_premium_amount
                        );
                    }else{
                        $category_data = Category::where('id',$source->sub_category)->first();
                        if(!blank($category_data)){
                            $category_name = $category_data->name;
                        }else{
                            $category_name = "";
                        }
                        if($source->business_type == 1){
                            $b_type = 'New';
                        }elseif($source->business_type == 2){
                            $b_type = 'Renew';
                        }elseif($source->business_type == 3){
                            $b_type = 'Rollover';
                        }else{
                            $b_type = 'Used';
                        }
                        $records[] = array(
                            'category'          =>  $category_name,
                            'business_type'     =>  $b_type,
                            'start_date'        =>  date('d-m-Y',strtotime($source->risk_start_date)),
                            'end_date'          =>  date('d-m-Y',strtotime($source->risk_end_date)),
                            'policy_no'         =>  $source->policy_no,
                            'customer'          =>  $source->customers->name,
                            'registration_no'   =>  $source->vehicle_registration_no,
                            'net_premium'       =>  $source->net_premium_amount
                        );
                    }
                }
            }
            $requests = [];
            $requests['policy_type']                = $request->insurance_type;
            $requests['policy_created_start_date']  = $request->policy_created_from;
            $requests['policy_created_end_date']    = $request->policy_created_to;
            $requests['policy_expiry_start_date']   = $request->policy_expiry_from;
            $requests['policy_expiry_end_date']     = $request->policy_expiry_to;
            $cmp = Company::where('id',$request->insurance_company)->first();
            if(!blank($cmp)){
                $company_name = $cmp->name;
            }else{
                $company_name = "";
            }
            $requests['insurance_company']  = $company_name;
            $requests['customer_name']      = $request->customer_name;
            if($request->business_type == 1){
                $b_type = 'New';
            }elseif($request->business_type == 2){
                $b_type = 'Renew';
            }elseif($request->business_type == 3){
                $b_type = 'Rollover';
            }else{
                $b_type = '';
            }
            if($request->insurance_type == 2){
                if($source->category == 1){
                    $category = 'Base';
                }elseif($source->category == 2){
                    $category = 'Personal Accident';  
                }else{
                    $category = 'Super Popup'; 
                }
            }else{
                $category_data = Category::where('id',$request->sub_category)->first();
                if(!blank($category_data)){
                    $category = $category_data->name;
                }else{
                    $category = "";
                }
            }
            $requests['business_type']      = $b_type;
            $requests['category']           = $category;
            view()->share(['records'=>$records,'requests'=>$requests]);
            $customPaper = [0, 0, 567.00, 500.80];
            $pdf = PDF::loadView('admin.reports.policy_pdf_report', $records)->setPaper('a4', 'landscape');
            // return view('admin.payout.payout_pdf_report');
            return $pdf->download('policy-report.pdf');
        }
    }
    private function getHealthCategory($category)
{
    switch ($category) {
        case 1:
            return 'Base';
        case 2:
            return 'Personal Accident';
        default:
            return 'Super Popup';
    }
}

private function getBusinessType($type)
{
    switch ($type) {
        case 1:
            return 'New';
        case 2:
            return 'Renew';
        case 3:
            return 'Rollover';
        case 4:
            return 'Used';
        default:
            return '';
    }
}
}
