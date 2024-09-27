<?php

namespace App\Http\Controllers\API;

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
use App\Models\Parameter;
use App\Models\Policy;
use App\Models\PolicyParameter;
use App\Models\PolicyAttachment;
use App\Models\PolicyDocument;
use App\Models\PolicyPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use URL;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CancelPolicyExport;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use Notification;
use App\Notifications\Notifications;
class PolicyController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function getPolicyInitialData(Request $request){
        $categories = Category::where('parent',0)->get();
        $categoryListJson = array();
        $cate = array();
        foreach($categories as $category){
            $cat = array();
            $cat['categoryId'] = $category->id;
            $cat['categoryName']    = $category->name;
            $sub_categories = Category::where('parent',$category->id)->where('status',1)->get(); 
            $sub = array();
            foreach($sub_categories as $sub_category){
                $sub_cat = array();
                $sub_cat['subCategoryId']   = $sub_category->id;
                $sub_cat['subCategoryName'] = $sub_category->name;
                $sub_cat['categoryId']      = $sub_category->parent;
                $sub_cat['categoryName']    = $category->name;
                $sub_cat['gst']             = $sub_category->gst;
                array_push($sub,$sub_cat);
            }
            $cat['subCategoryList'] = $sub;
            array_push($cate,$cat);
        }
        $categoryListJson = json_encode($cate);
        $insuranceCompanyListJson = array();
        $companies = Company::where('status',1)->get();
        $comp = array();
        foreach($companies as $company){
            $com = array();
            $com['insuranceCompanyId'] = $company->id;
            $com['insuranceCompanyName'] = $company->name;
            $health_plans = Plan::where('company',$company->id)->get();
            $plans = array();
            foreach($health_plans as $plan){
                $insurance_plan = array();
                $insurance_plan['planId']                   = $plan->id;
                $insurance_plan['planName']                 = $plan->name;
                $insurance_plan['insuranceCompanyId']       = $plan->parent;
                $insurance_plan['insuranceCompanyName']     = $plan->name;
                array_push($plans,$insurance_plan);
            }
            $com['healthPlanList'] =$plans; 
            array_push($comp,$com);
        }
        $insuranceCompanyListJson = json_encode($comp);
        $sourcingAgentListJson = array();
        
        $sourcing_agents = SourcingAgent::where('status',1)->get();
        $agents = array();
        foreach($sourcing_agents as $s_agent){
            $agent = array();
            $agent['sourcingAgentId'] = $s_agent->id;
            $agent['sourcingAgentName'] = $s_agent->name;
            $agent['teamLeadId'] = $s_agent->team_lead;
            $team_lead = User::where('id',$s_agent->team_lead)->first();
            if(!blank($team_lead)){
                $agent['teamLeadName'] = $team_lead->name;
            }else{
                $agent['teamLeadName'] = '';
            }
            array_push($agents,$agent);
        }
        
        $sourcingAgentListJson = json_encode($agents);
        $healthCategoryListJson = array();
        $h_category = array();
        $health_category = array();
        $health_category['categoryId'] = 1;
        $health_category['categoryName'] = "Base";
        array_push($h_category,$health_category);
        $health_category = array();
        $health_category['categoryId'] = 2;
        $health_category['categoryName'] = "Super Topup";
        array_push($h_category,$health_category);
        $health_category = array();
        $health_category['categoryId'] = 1;
        $health_category['categoryName'] = "Personal Accident";
        array_push($h_category,$health_category);

        $healthCategoryListJson = json_encode($h_category);

        return response()->json([
            "result"                    =>  200,
            "description"               =>  "Dashboard data",
            "status"                    =>  1,
            'categoryListJson'          =>  $categoryListJson,
            'insuranceCompanyListJson'  =>  $insuranceCompanyListJson,
            'sourcingAgentListJson'     =>  $sourcingAgentListJson,
            'healthCategoryListJson'    =>  $healthCategoryListJson,
            'teamLeadJson'              => [],
        ],200);
    }
    public function getVehicleInsurancePolicyList(Request $request){
            
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            if(!blank($accessToken)){
                $currentUser = User::where('id',$accessToken->user_id)->first();
            }else{
                $currentUser = User::where('id',1)->first();
            }
            $query = Policy::latest()->where('insurance_type',1)->orderBy('id','Desc');
            if($request->has('id') && $request->id != ''){
                $query->where('id',$request->id);
            }
            if($request->has('policytype')){
                $query->where('status', $request->policytype);
            }else{
                $query->where('status','!=',2);
            }
            if($request->has('policyNo') && $request->policyNo != ''){
                $query->where('policy_no','LIKE','%'.$request->policyNo.'%');
            }
            if($request->has('covernoteNo') && $request->covernoteNo != ''){
                $query->where('covernote_no','LIKE','%'.$request->covernoteNo.'%');
            }
            if($request->has('vehicleMake') && $request->vehicleMake != ''){
                $query->where('vehicle_make','LIKE','%'.$request->vehicleMake.'%');
            }
            if($request->has('vehicleModel') && $request->vehicleModel != ''){
                $query->where('vehicle_make','LIKE','%'.$request->vehicleModel.'%');
            }
            if($request->has('vehicleRegistrationNo') && $request->vehicleRegistrationNo != ''){
                $query->where('vehicle_registration_no','LIKE','%'.$request->vehicleRegistrationNo.'%');
            }
            if($request->has('vehicleChassisNo') && $request->vehicleChassisNo != ''){
                $query->where('vehicle_chassis_no','LIKE','%'.$request->vehicleChassisNo.'%');
            }
            if($request->has('vehicleEngine') && $request->vehicleEngine != ''){
                $query->where('vehicle_engine','LIKE','%'.$request->vehicleEngine.'%');
            }
            if($request->has('policyCreatedStartDate') && $request->policyCreatedStartDate != '' && $request->has('policyCreatedEndDate') && $request->policyCreatedEndDate != ''){
                $policyCreatedStartDate =  \DateTime::createFromFormat('d/m/Y',$request->policyCreatedStartDate);
                $policyCreatedEndDate =  \DateTime::createFromFormat('d/m/Y',$request->policyCreatedEndDate);
                $query->whereBetween('created_at',[$policyCreatedStartDate,$policyCreatedEndDate]);
            }
            if($request->has('policyExpiryStartDate') && $request->policyExpiryStartDate != '' && $request->has('policyExpiryEndDate') && $request->policyExpiryEndDate != ''){
                $policyExpiryStartDate =  \DateTime::createFromFormat('d/m/Y',$request->policyExpiryStartDate);
                $policyExpiryEndDate =  \DateTime::createFromFormat('d/m/Y',$request->policyExpiryStartDate);
                $query->whereBetween('risk_end_date',[$policyExpiryStartDate,$policyExpiryEndDate]);
            }
            if($request->has('sourcingAgentId') && $request->sourceAgentId != 0){
                $query->where('agent',$request->sourcingAgentId);
            }
            if($request->has('customerId') && $request->customerId != 0){
                $query->where('customer',$request->customerId);
            }
            if($request->has('insuranceCompanyId') && $request->insuranceCompanyId != 0){
                $query->where('company',$request->insuranceCompanyId);
            }
            if($request->has('subCategoryId') && $request->subCategoryId != 0){
                $query->where('sub_category',$request->subCategoryId);
            }
            if($request->has('categoryId') && $request->categoryId != 0){
                $query->where('category',$request->categoryId);
            }
            if($request->has('businessType') && $request->businessType != 0){
                $query->where('business_type',$request->businessType);
            }
            if($currentUser->role == 1){
                if($request->has('perPage') && $request->perPage != '' && $request->has('pageNo') && $request->pageNo != '' ){
                    $policies = $query->paginate($request->perPage, ['*'], 'page', $request->pageNo);
                }else{
                    $policies = $query->get();
                }
            }elseif($currentUser->role == 3){
                if($request->has('perPage') && $request->perPage != '' && $request->has('pageNo') && $request->pageNo != '' ){
                    $agent = SourcingAgent::where('user_id',$currentUser->id)->first();
                    if($agent->id){
                    $query->where('agent',$agent->id);
                    }
                    $policies = $query->paginate($request->perPage, ['*'], 'page', $request->pageNo);
                }else{
                    $agent = SourcingAgent::where('user_id',$currentUser->id)->first(); 
                    if($agent->id){
                    $query->where('agent',$agent->id);
                    }
                    $policies = $query->get();
                }
            }
            // print_r($policies);
            // exit;
            if(!blank($policies)){
                 $array_push = array();
                foreach($policies as $policy){
                    $array = array();
                    $array['motorInsuarancePolicyId']                   = $policy->id;
                    $array['InsuranceType']                             = ($policy->insurance_type != NULL)?$policy->insurance_type:"";
                    $array['policyNo']                                  = ($policy->policy_no != NULL)?$policy->policy_no:"";
                    $array['category']                                  = ($policy->category != NULL)?(int)$policy->category:"";
                    $businessSourcBean = array();
                    $businessSourcBean = [
                        'businessSourceId' => null,
                        'businessSourceName' => null,
                    ];
                    $businessSource = BusinessSource::where('id',$policy->business_source)->first();
                    if(!blank($businessSource)){
                        $company = Company::where('id',$businessSource->company)->first();
                        $businessSourcBean['businessSourceId']                     = $businessSource->id;
                        $businessSourcBean['businessSourceName']                     = "";
                        if(!blank($company)){
                            $businessSourcBean['businessSourceName']          = $company->name;
                        }
                    }
                    $array['businessSourcBean']                              = $businessSourcBean;
                    $CategoryBean                                       = [];
                    $category = Category::where('id',$policy->category_id)->first();
                    if(!blank($category)){
                        $CategoryBean['categoryId']                     = $category->id;
                        $CategoryBean['categoryName']                   = $category->name;
                    }
                    $array['CategoryBean']                              = $CategoryBean;
                    $company = Company::where('id',$policy->company)->first();
                    $insuranceCompanyBean                        = array();
                    if(!blank($company)){
                        $insuranceCompanyBean['insuranceCompanyId']              = $company->id;
                        $insuranceCompanyBean['insuranceCompanyName']            = $company->name;
                    }
                    $array['insuranceCompanyBean'] = $insuranceCompanyBean;
                    $customerBean = array();
                    $customer = Customer::where('id',$policy->customer)->first();
                    if(!blank($customer)){
                        $customerBean['customerId']                 = $customer->id;
                        $customerBean['customerName']               = $customer->name;
                        $customerBean['customerEmail']              = $customer->email;
                        $customerBean['customerMobile']             = $customer->phone;
                    }
                    $array['customerBean']                          = $customerBean;
                    $array['vehicleMake']                           = ($policy->vehicle_make != NULL)?$policy->vehicle_make:"";
                    $array['vehicleModel']                          = ($policy->vehicle_model != NULL)?$policy->vehicle_model:"";
                    $array['vehicleRegistrationNo']                 = ($policy->vehicle_registration_no != NULL)?$policy->vehicle_registration_no:"";
                    $array['vehicleChassisNo']                      = ($policy->vehicle_chassis_no != NULL)?$policy->vehicle_chassis_no:"";
                    $array['yearOfManufacture']                     = ($policy->year_of_manufacture != NULL)?$policy->year_of_manufacture:"";
                    $array['vehicleEngine']                         = ($policy->vehicle_engine != NULL)?$policy->vehicle_engine:"";
                    $array['idvAmount']                             = ($policy->idv_amount != NULL)?$policy->idv_amount:"";
                    $array['grossPremiumAmount']                    = ($policy->gross_premium_amount != NULL)?$policy->gross_premium_amount:"";
                    $array['netPremiumAmount']                      = ($policy->net_premium_amount != NULL)?$policy->net_premium_amount:"";
                    $array['riskStartDate']                         = ($policy->risk_start_date != NULL)?date('d-m-Y',strtotime($policy->risk_start_date)):"";
                    $array['riskEndDate']                           = ($policy->risk_end_date != NULL)?date('d-m-Y',strtotime($policy->risk_end_date)):"";
                    $array['motorBusinessType']                     = ($policy->business_type != NULL)?$policy->business_type:"";
                    $sourcingAgentBean                              = array();
                    $agent = SourcingAgent::where('id',$policy->agent)->first();
                    if(!blank($agent)){
                        $sourcingAgentBean['sourcingAgentId']           = $agent->id;
                        $sourcingAgentBean['sourcingAgentName']         = $agent->name;
                        $sourcingAgentBean['sourcingAgentMobile']       = $agent->phone;
                    }
                    $array['sourcingAgentBean']                     = $sourcingAgentBean;
                    $subCategoryBean    = array();
                    $sub_category = Category::where('id',$policy->sub_category)->first();
                    if(!blank($sub_category)){
                        $subCategoryBean['subCategoryId']           = $sub_category->id;
                        $subCategoryBean['subCategoryName']         = $sub_category->name;
                        $subCategoryBean['categoryId']              = $sub_category->parent;
                        $category = Category::where('id',$sub_category->parent)->first();
                        $subCategoryBean['categoryName']            = $category->name;
                        $subCategoryBean['isRenewable']             = $category->renewable;
                    }
                    $array['subCategoryBean']                       = $subCategoryBean;
                    $array['policyType']                            = "POLICY";
                    $policyParameter = array();
                    $parameters = PolicyParameter::where('policy_id',$policy->id)->get();
                    if(!blank($parameters)){
                        foreach ($parameters as $item){
                            if ($item->type == 1){
                                $policyParameter['Public Carrier']  = $item->value;
                            }elseif ($item->type == 2){
                                $policyParameter['Private Carrier']  = $item->value;
                            }elseif ($item->type == 3){
                            }elseif ($item->type == 4){
                                $param = DB::table('parameters')->where('type',4)->get();
                                foreach ($param as $para){
                                    if ($para->id == $item->parameter_id){
                                        $policyParameter[$para->label]  = $item->value;
                                    }
                                }
                            }elseif ($item->type == 5){
                                $param = DB::table('parameters')->where('type',5)->get();
                                foreach ($param as $para){
                                    if ($para->id == $item->parameter_id){
                                        $policyParameter['CC']  = $para->label;
                                    }
                                }
                            }elseif($item->type == 6){
                                $param = DB::table('parameters')->where('type',6)->get();
                                foreach ($param as $para){
                                    if ($para->id == $item->parameter_id){
                                        $policyParameter['PA to Passanger']  = $para->label;
                                    }
                                }
                            }elseif($item->type == 7){
                                $param = DB::table('parameters')->where('type',7)->get();
                                foreach ($param as $para){
                                    if ($para->id == $item->parameter_id){
                                        $policyParameter[$para->label]  = $item->value;
                                    }
                                }
                            }
                        }
                    }
                    $array['policyParameter'] = $policyParameter;
                    $array['isPolicyIndividual']                    = ($policy->policy_type != NULL)?$policy->policy_type:"";
                    if($policy->status == 2){
                        $cancel = true;
                    }else{
                        $cancel = false;
                    }
                    $array['isCancelled']                           = $cancel;
                    $team_lead = array();
                    $lead = User::where('id',$policy->team_lead)->first();
                    if(!blank($lead)){
                        $team_lead['id']                                = $lead->id;
                        $team_lead['displayName']                       = $lead->name;
                    }
                    $array['teamLead']                              =  $team_lead;
                    // $array['sum_premium_amount']                = ($policy->sum_premium_amount != NULL)?$policy->sum_premium_amount:"";
                    // $array['policy_type']                       = ($policy->policy_type != NULL)?$policy->policy_type:"";
                    // $array['policy_individual_rate']            = ($policy->policy_individual_rate != NULL)?$policy->policy_individual_rate:"";
                    // $array['health_plan']                       = ($policy->health_plan != NULL)?$policy->health_plan:"";
                    // $plan = Plan::where('id',$policy->health_plan)->first();
                    // if(!blank($plan)){
                    //     $array['health_plan_name']              = $plan->name;
                    // }
                    // $array['health_category']                   = ($policy->health_category != NULL)?(int)$policy->health_category:"";
                    // $health_category = Category::where('id',$policy->health_category)->first();
                    // if(!blank($health_category)){
                    //     $array['health_category_name']             = $health_category->name;
                    // }
                    $array['tp']                              = ($policy->tp != NULL)?$policy->tp:0;
                    $array['od']                              = ($policy->od != NULL)?$policy->od:0;
                    $array['covernoteNo']                     = ($policy->covernote_no != NULL)?$policy->covernote_no:"";
                    $array['pypNo']                           = ($policy->pyp_no != NULL)?$policy->pyp_no:"";
                    $array['pypInsuranceCompany']             = ($policy->pyp_insurance_company != NULL)?$policy->pyp_insurance_company:"";
                    $array['pypExpiryDate']                   = ($policy->pyp_expiry_date != NULL)?date('d-m-Y',strtotime($policy->pyp_expiry_date)):"";
                    // $array['business_amount']                   = ($policy->business_amount != NULL)?$policy->business_amount:"";
                    // $array['managedBy']                        = ($policy->managed_by != NULL)?$policy->managed_by:"";
                    $manageBy = array();
                    $managed_by = User::where('id',$policy->managed_by)->first();
                    if(!blank($managed_by)){
                        $manageBy['id']                         = $managed_by->id;
                        $manageBy['name']                       = $managed_by->name;
                    }
                    $array['managedBy']                        = $manageBy;
                    $array['ncb']                              = ($policy->ncb != NULL)?$policy->ncb:"";
                    $array['cancelDate']                       = ($policy->cancel_date != NULL)?date('d-m-Y',strtotime($policy->cancel_date)):"";
                    $array['cancelReason']                     = ($policy->cancel_reason != NULL)?$policy->cancel_reason:"";
                    $array['remarks']                          = ($policy->remarks != NULL)?$policy->remarks:"";
                    $array['status']                           = ($policy->status != NULL)?$policy->status:0;
                    $created_by = User::where('id',$policy->created_by)->first();
                    $array['created_by']                       = $created_by->name;
                    $array['created_at']                       = ($policy->created_at != NULL)?date('d-m-Y',strtotime($policy->created_at)):"";
                    $policy_documents = PolicyDocument::where('policy_id',$policy->id)->get();
                    $documents = array();
                    if(!blank($policy_documents)){
                       
                        $i = 0;
                        foreach($policy_documents as $document){
                            $doc['file']        =   url('/')."/public/policy_document/".$document->file;
                            $doc['name']        =   $document->file_name;
                            $doc['type']        =   pathinfo($document->file_name, PATHINFO_EXTENSION);
                            $doc['created_at']  =   date('d-m-Y',strtotime($document->created_at));
                            $i++;
                            array_push($documents,$doc);
                        }
                    }
                    $array['policyDocuments']                  = $documents;
                    $policy_attachments = PolicyAttachment::where('policy_id',$policy->id)->get();
                    if(!blank($policy_attachments)){
                        $attachments = array();
                        $i = 0;
                        foreach($policy_attachments as $attachment){
                            $atta['file']        =   url('/')."/public/policy_attachment/".$attachment->file;
                            $atta['name']        =   $attachment->file;
                            $atta['type']        =   pathinfo($attachment->file, PATHINFO_EXTENSION);
                            $atta['created_at']  =   date('d-m-Y',strtotime($attachment->created_at));
                            $i++;
                            array_push($attachments,$atta);
                        }
                        $array['policyAttachments']                  = $attachments;
                    }
                    $policy_payments = PolicyPayment::where('policy_id',$policy->id)->get();
                    $payments = array();
                    if(!blank($policy_payments)){
                        $i = 0;
                        foreach($policy_payments as $payment){
                            $pay['id']                  =   $payment->id;
                            $pay['policy_id']           =   $payment->policy_id;
                            $pay['payment_type']        =   $payment->payment_type;
                            if($payment->payment_type == 2){
                                $pay['cheque_no']       =   $payment->cheque_no;  
                                $pay['cheque_date']     =   date('d-m-Y',strtotime($payment->cheque_date));
                                $pay['bank_name']       =   $payment->bank_name; 
                            }
                            if($payment->payment_type == 3){
                                $pay['transaction_no']  = $payment->transaction_no;
                            }
                            $pay['payment_made_by']     =   ($payment->made_by != NULL)?$payment->made_by:"";
                            $pay['payment_date']        =   date('d-m-Y',strtotime($payment->payment_date));
                            $pay['created_at']          =   date('d-m-Y',strtotime($payment->created_at));
                            $i++;
                            array_push($payments,$pay);
                        }
                    }
                    $array['policyPayments']                  = $payments;
                    array_push($array_push,$array);
                }
                return response()->json([
                    'result'        => 200,
                    'description'   => 'Policy',
                    'status' => 1,
                    'motorInsurancePolicyListJson'=>json_encode($array_push),
                    'motorInsurancePolicyListCount'=>count($policies),
                    'totalCount'=>count($policies),
                    'message'       => 'Success!'
                ],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Policies Not Found.','error'=> 'Policies Not Found.'],200);
            }
    }
    public function getHealthInsurancePolicyList(Request $request){
    $authorization = $request->header('Authorization');
    $accessToken = AccessToken::where('access_token', $authorization)->first();
    $currentUser = User::where('id',$accessToken->user_id)->first();
    $query = Policy::latest()->where('insurance_type', 2)->orderBy('id', 'Desc');

    if ($request->has('id') && $request->id != '') {
        $query->where('id', $request->id);
    }
    if ($request->has('policyNo') && $request->policyNo != '') {
        $query->where('policy_no', 'LIKE', '%' . $request->policyNo . '%');
    }
    if ($request->has('policytype')) {
        $query->where('status', $request->policytype);
    } else {
        $query->where('status', '!=', 2);
    }
    if ($request->has('policyCreatedStartDate') && $request->has('policyCreatedEndDate') && $request->policyCreatedStartDate != '' && $request->policyCreatedEndDate != '') {
        $policyCreatedStartDate = \DateTime::createFromFormat('d/m/Y', $request->policyCreatedStartDate);
        $policyCreatedEndDate = \DateTime::createFromFormat('d/m/Y', $request->policyCreatedEndDate);
        $query->whereBetween('created_at', [$policyCreatedStartDate, $policyCreatedEndDate]);
    }
    if ($request->has('policyExpiryStartDate') && $request->has('policyExpiryEndDate') && $request->policyExpiryStartDate != '' && $request->policyExpiryEndDate != '') {
        $policyExpiryStartDate = \DateTime::createFromFormat('d/m/Y', $request->policyExpiryStartDate);
        $policyExpiryEndDate = \DateTime::createFromFormat('d/m/Y', $request->policyExpiryEndDate);
        $query->whereBetween('risk_end_date', [$policyExpiryStartDate, $policyExpiryEndDate]);
    }
    if ($request->has('sourcingAgentId') && $request->sourcingAgentId != '') {
        $query->where('agent', $request->sourcingAgentId);
    }
    if ($request->has('customerId') && $request->customerId != '') {
        $query->where('customer', $request->customerId);
    }
    if ($request->has('insuranceCompanyId') && $request->insuranceCompanyId != '') {
        $query->where('company', $request->insuranceCompanyId);
    }
    if ($request->has('planId') && $request->planId != '') {
        $query->where('health_plan', $request->planId);
    }
    if ($request->has('categoryId') && $request->categoryId != '') {
        $query->where('category', $request->categoryId);
    }
    if ($request->has('businessType') && $request->businessType != '') {
        $query->where('business_type', $request->businessType);
    }

    if ($currentUser->role == 1) {
        if ($request->has('perPage') && $request->perPage != '' && $request->has('pageNo') && $request->pageNo != '') {
            $policies = $query->paginate($request->perPage, ['*'], 'page', $request->pageNo);
        } else {
            $policies = $query->get();
        }
    } elseif ($currentUser->role == 3) {
        $agent = SourcingAgent::where('user_id', $currentUser->id)->first(); 
        if ($agent->id) {
            $query->where('agent', $agent->id);
        }
        if ($request->has('perPage') && $request->perPage != '' && $request->has('pageNo') && $request->pageNo != '') {
            $policies = $query->paginate($request->perPage, ['*'], 'page', $request->pageNo);
        } else {
            $policies = $query->get();
        }
    }
    
    $array_push = array();
    if (!blank($policies)) {
        foreach ($policies as $policy) {
            $array = array();
            $array['healthInsuarancePolicyId'] = $policy->id;
            $array['InsuranceType'] = ($policy->insurance_type != NULL) ? $policy->insurance_type : "";
            $array['policyNo'] = ($policy->policy_no != NULL) ? $policy->policy_no : "";
            
            $customerBean = array();
            $customer = Customer::where('id', $policy->customer)->first();
            if (!blank($customer)) {
                $customerBean['customerId'] = $customer->id;
                $customerBean['customerName'] = $customer->name;
                $customerBean['customerEmail'] = $customer->email;
                $customerBean['customerMobile'] = $customer->phone;
            }
            $array['customerBean'] = $customerBean;

            $array['category'] = ($policy->category != NULL) ? (int)$policy->category : "";
            $CategoryBean = array();
            if ($policy->category == 1) {
                $CategoryBean['categoryId'] = 1;
                $CategoryBean['categoryName'] = 'Base';
            }
            if ($policy->category == 2) {
                $CategoryBean['categoryId'] = 2;
                $CategoryBean['categoryName'] = 'Super Topup';
            }
            if ($policy->category == 3) {
                $CategoryBean['categoryId'] = 3;
                $CategoryBean['categoryName'] = 'Personal Accident';
            }
            $array['healthCategoryBean'] = (object) $CategoryBean;

            $healthPlanBean = array();
            $plan = Plan::where('id', $policy->health_plan)->first();
            if (!blank($plan)) {
                $healthPlanBean['planId'] = $plan->id;
                $healthPlanBean['planName'] = $plan->name;
                $healthPlanBean['insuranceCompanyId'] = $plan->company;
                $cmp = Company::where('id', $plan->company)->first();
                if ($cmp) {
                    $healthPlanBean['insuranceCompanyName'] = $cmp->name;
                }
            }
            $array['healthPlanBean'] = !empty($healthPlanBean) ? $healthPlanBean : null;

            $company = Company::where('id', $policy->company)->first();
            
            $insuranceCompanyBean = array();
            if (!blank($company)) {
                $insuranceCompanyBean['insuranceCompanyId'] = $company->id;
                $insuranceCompanyBean['insuranceCompanyName'] = $company->name;
                $health_plans = Plan::where('company', $company->id)->get();
                $insurance_plan = array();
                foreach ($health_plans as $plan) {
                    $insurance_plan['planId'] = $plan->id;
                    $insurance_plan['planName'] = $plan->name;
                    $insurance_plan['insuranceCompanyId'] = $plan->parent;
                    $insurance_plan['insuranceCompanyName'] = $plan->name;
                }
                $insuranceCompanyBean['healthPlanList'] = (object) $insurance_plan;
            }
            $array['insuranceCompanyBean'] = $insuranceCompanyBean;

            $array['covernoteNo'] = ($policy->covernote_no != NULL) ? $policy->covernote_no : "";
            $array['idvAmount'] = ($policy->idv_amount != NULL) ? $policy->idv_amount : "";
            $array['sumInsuredAmount'] = ($policy->sum_insured_amount != NULL) ? $policy->sum_insured_amount : "";
            $array['grossPremiumAmount'] = ($policy->gross_premium_amount != NULL) ? $policy->gross_premium_amount : "";
            $array['netPremiumAmount'] = ($policy->net_premium_amount != NULL) ? $policy->net_premium_amount : "";
            $array['riskStartDate'] = ($policy->risk_start_date != NULL) ? date('d/m/Y', strtotime($policy->risk_start_date)) : "";
            $array['riskEndDate'] = ($policy->risk_end_date != NULL) ? date('d/m/Y', strtotime($policy->risk_end_date)) : "";
            $array['healthBusinessType'] = ($policy->business_type != NULL) ? $policy->business_type : "";

            $sourcingAgentBean = array();
            $agent = SourcingAgent::where('id', $policy->agent)->first();
            if (!blank($agent)) {
                $sourcingAgentBean['sourcingAgentId'] = $agent->id;
                $sourcingAgentBean['sourcingAgentName'] = $agent->name;
                $sourcingAgentBean['sourcingAgentMobile'] = $agent->phone;
            }
            $array['sourcingAgentBean'] = $sourcingAgentBean;

            $array['policyType'] = "POLICY";
            $array['isCancelled'] = $policy->status == 2;

            $team_lead = array();
            $lead = User::where('id', $policy->team_lead)->first();
            if (!blank($lead)) {
                $team_lead['displayName'] = $lead->name;
            }
            $array['teamLead'] = $team_lead;

            $array['pypNo'] = ($policy->pyp_no != NULL) ? $policy->pyp_no : "";
            $array['pypInsuranceCompany'] = ($policy->pyp_insurance_company != NULL) ? $policy->pyp_insurance_company : "";
            $array['pypExpiryDate'] = ($policy->pyp_expiry_date != NULL) ? date('d-m-Y', strtotime($policy->pyp_expiry_date)) : "";

            $manageBy = array();
            $managed_by = User::where('id', $policy->managed_by)->first();
            if (!blank($managed_by)) {
                $manageBy['id'] = $managed_by->id;
                $manageBy['name'] = $managed_by->name;
            }
            $array['managedBy'] = $manageBy;

            $array['tp'] = ($policy->tp != NULL) ? $policy->tp : 0;
            $array['od'] = ($policy->od != NULL) ? $policy->od : 0;
            $array['ncb'] = ($policy->ncb != NULL) ? $policy->ncb : "";
            $array['cancelDate'] = ($policy->cancel_date != NULL) ? date('d-m-Y', strtotime($policy->cancel_date)) : "";
            $array['cancelReason'] = ($policy->cancel_reason != NULL) ? $policy->cancel_reason : "";
            $array['remarks'] = ($policy->remarks != NULL) ? $policy->remarks : "";
            $array['status'] = ($policy->status != NULL) ? $policy->status : 0;

            $created_by = User::where('id', $policy->created_by)->first();
            $array['created_by'] = $created_by->name;
            $array['created_at'] = ($policy->created_at != NULL) ? date('d-m-Y', strtotime($policy->created_at)) : "";

            $policy_documents = PolicyDocument::where('policy_id', $policy->id)->get();
            $documents = array();
            if (!blank($policy_documents)) {
                foreach ($policy_documents as $document) {
                    $doc['file'] = url('/') . "/public/policy_document/" . $document->file;
                    $doc['name'] = $document->file_name;
                    $doc['type'] = pathinfo($document->file_name, PATHINFO_EXTENSION);
                    $doc['created_at'] = $document->created_at;
                    array_push($documents, $doc);
                }
            }
            $array['policyDocuments'] = $documents;

            $policy_attachments = PolicyAttachment::where('policy_id', $policy->id)->get();
            if (!blank($policy_attachments)) {
                $attachments = array();
                foreach ($policy_attachments as $attachment) {
                    $atta['file'] = url('/') . "/public/policy_attachment/" . $attachment->file;
                    $atta['name'] = $attachment->file;
                    $atta['type'] = pathinfo($attachment->file, PATHINFO_EXTENSION);
                    $atta['created_at'] = $attachment->created_at;
                    array_push($attachments, $atta);
                }
                $array['policyAttachments'] = $attachments;
            }

            $policy_payments = PolicyPayment::where('policy_id', $policy->id)->get();
            $payments = array();
            if (!blank($policy_payments)) {
                foreach ($policy_payments as $payment) {
                    $pay['id'] = $payment->id;
                    $pay['policy_id'] = $payment->policy_id;
                    $pay['payment_type'] = $payment->payment_type;
                    if ($payment->payment_type == 2) {
                        $pay['cheque_no'] = $payment->cheque_no;  
                        $pay['cheque_date'] = $payment->cheque_date;
                        $pay['bank_name'] = $payment->bank_name; 
                    }
                    if ($payment->payment_type == 3) {
                        $pay['transaction_no'] = $payment->transaction_no;
                    }
                    $pay['payment_made_by'] = ($payment->made_by != NULL) ? $payment->made_by : "";
                    $pay['payment_date'] = $payment->payment_date;
                    $pay['created_at'] = $payment->created_at;
                    array_push($payments, $pay);
                }
            }
            $array['policyPayments'] = $payments;
            array_push($array_push, $array);
        }

        return response()->json([
            'result' => 200,
            'description' => 'Policy',
            'status' => 1,
            'healthInsurancePolicyListJson' => json_encode($array_push),
            'healthInsurancePolicyListCount' => count($policies),
            'totalCount' => count($policies),
            'message' => 'Success!'
        ], 200);
    } else {
        return response()->json(['result' => 200, 'status' => 0, 'description' => 'Policies Not Found.', 'error' => 'Health Policies Not Found.'], 200);
    }
}

    public function getTpCalculationData(Request $request){
        if($request->has('subCategoryId')){
            $parameters = Parameter::where('sub_category_id',$request->subCategoryId)->groupBy('type')->get(); 
            $tpCalculationBeanList = [];
            $i = 1;
            if(!blank($parameters)){
                
                foreach($parameters as $parameter){
                    $param_data = [];
                    if($parameter->type == 1 || $parameter->type == 2 || $parameter->type == 3 || $parameter->type == 4 || $parameter->type == 5 || $parameter->type == 6){
                        if($parameter->type == 1){
                            $type = 'Public Carrier';
                        }elseif($parameter->type == 2){
                            $type = 'Private Carrier';
                        }elseif($parameter->type == 3){
                            $type = 'Taxi';
                        }elseif($parameter->type == 4){
                            $type = 'Bus';
                        }elseif($parameter->type == 5){
                            $type = 'CC';
                        }elseif($parameter->type == 6){
                            $type = 'PA to Passanger';
                        }
                        $param_data['tpCalculationParameterId']  = $parameter->id;
                        $param_data['level_no']                  = 2;
                        $param_data['sr']                        = $i;
                        $param_data['typeId']                    = $parameter->type;
                        $param_data['type']                      = $type;
                        $tpCalculationParameterBeans = [];
                        $parameters_data = Parameter::where('type',$parameter->type)->where('sub_category_id',$request->subCategoryId)->get();
                        $sr = 0;
                        foreach($parameters_data as $data){
                            $sr++;
                            if($data->type != 3){
                                $param = [];
                                $param['tpCalculationParameterId']  = $data->id;
                                $param['levelNo']                   = 2;
                                $param['sr']                        = $sr;
                                $parma['typeId']                    = $sr;
                                if($data->type == 1 || $data->type == 2){
                                    $param['type']              =   $data->carrier; 
                                }elseif($data->type == 4){
                                    if($data->display_type == 'dropdown'){
                                        $param['startRange']           = 1;
                                        $param['endRange']             = 10;
                                    }
                                    $param['field_type']               = $data->display_type;
                                    if($data->display_type == 'hidden_field'){
                                        $param['value']                    = $data->carrier_value;
                                    }
                                    $param['type']              =   $data->label;
                                }elseif($data->type == 3 && $data->label != 'paid_driver'){
                                    $param['type']              =   $data->taxi_cc; 
                                }else{
                                    $param['type']              =   $data->label; 
                                }
                                array_push($tpCalculationParameterBeans,$param);
                            }else{
                                if($data->label != 'paid_driver'){
                                    $param = [];
                                    $param['tpCalculationParameterId']  = $data->id;
                                    $param['levelNo']                   = 1;
                                    $param['sr']                        = $sr;
                                    $parma['typeId']                    = $sr;
                                    $param['field_type']                = 'checkbox';
                                    $param['type']                      =  $data->taxi_cc; 
                                }else{
                                    $param = [];
                                    $param['tpCalculationParameterId']  = $data->id;
                                    $param['levelNo']                   = 2;
                                    $param['sr']                        = $sr;
                                    $parma['typeId']                    = $sr;
                                    $param['type']                      =   $data->label; 
                                }
                                array_push($tpCalculationParameterBeans,$param);
                            }
                        }
                        $param_data['tpCalculationParameterBeans'] = $tpCalculationParameterBeans;
                        array_push($tpCalculationBeanList,$param_data);
                    }else{
                        $parameters_data = Parameter::where('type',$parameter->type)->where('sub_category_id',$request->subCategoryId)->get();
                        $sr = 0;
                        foreach($parameters_data as $data){
                            $sr++;
                            $param_data = [];
                            $param_data['tpCalculationParameterId']  = $data->id;
                            $param_data['level_no']                  = 1;
                            $param_data['sr']                        = $i;
                            $param_data['typeId']                    = $data->type;
                            $param_data['type']                      = $data->label;
                            if($data->display_type == 'dropdown'){
                                $param_data['startRange']           = 1;
                                $param_data['endRange']             = 10;
                            }
                            $param_data['field_type']               = $data->display_type;
                            if($data->display_type == 'hidden_field'){
                                $param_data['value']                    = $data->carrier_value;
                            }
                            $tpCalculationParameterBeans = [];
                            array_push($tpCalculationBeanList,$param_data);
                        }
                    }
                    $i++;
                }
            }
            return response()->json([
                'result'                    =>  200,
                'description'               =>  'Tp Calculation Parameter List',
                'status'                    =>  1,
                'tpCalculationBeanListJson'  =>  $tpCalculationBeanList,
                'tpCalculationBeanListCount' =>  $i-1,
                'message'                   =>  'Success!'
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);    
        }
    }
    public function addVehicleInsurancePolicy(Request $request){
        // print_r($request->All());
        // exit;
        $messages = [
            'pypNo.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypInsuranceCompany.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypExpiryDate.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
        ];
        $validator = Validator::make($request->all(), [
            'categoryId'                    => 'required',
            'subCategoryId'                 => 'required',
            'insuranceCompanyId'            => 'required',
            'customerId'                    => 'required|not_in:0',
            'vehicleModel'                  => 'required',
            'vehicleEngine'                 => 'required',
            'vehicleMake'                   => 'required',
            'yearOfManufacture'             => 'required',
            'vehicleChassisNo'              => 'required',
            'idvAmount'                     => 'required',
            'motorBusinessType'             => 'required',
            // 'sourcingAgentId'               => 'required|not_in:0',
            'grossPremiumAmount'            => 'required',
            'netPremiumAmount'              => 'required',
            'riskStartDate'                 => 'required',
            'riskEndDate'                   => 'required',
            // 'ncb'                           => 'required',
            // 'teamLead'                      => 'required|not_in:0',
            // 'managedBy'                     => 'required|not_in:0',
            'policyType'                    => 'required_if:categoryId,1',
            'pypNo'                         => 'required_if:motorBusinessType,2|required_if:motorBusinessType,3',
            'pypInsuranceCompany'           => 'required_if:motorBusinessType,2|required_if:motorBusinessType,3',
            'pypExpiryDate'                 => 'required_if:motorBusinessType,2|required_if:motorBusinessType,3',
        ], $messages);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $policy = new Policy();
            $policy->insurance_type                 = 1;
            $policy->category                       = $request->categoryId;
            $policy->policy_type                    = $request->policyType;
            $policy->business_source                    = $request->bussinesSourceId;
            $policy->policy_individual_rate         = $request->policyIndividualRate;
            $policy->sub_category                   = $request->subCategoryId;
            $policy->company                        = $request->insuranceCompanyId;
            $policy->covernote_no                   = $request->covernoteNo;
            $policy->policy_no                      = $request->policyNo;
            $policy->customer                       = $request->customerId;
            $policy->vehicle_model                  = $request->vehicleModel;
            $policy->vehicle_engine                 = $request->vehicleEngine;
            $policy->vehicle_chassis_no             = $request->vehicleChassisNo;
            $policy->idv_amount                     = $request->idvAmount;
            $policy->team_lead                      = ($request->has('teamLead'))?$request->teamLead:0;
            $policy->managed_by                     = ($request->has('managedBy'))?$request->managedBy:0;
            $policy->net_premium_amount             = $request->netPremiumAmount;
            $policy->pyp_no                         = ($request->has('pypNo'))?$request->pypNo:"";
            $policy->pyp_insurance_company          = ($request->has('pypInsuranceCompany'))?$request->pypInsuranceCompany:"";
            $policy->pyp_expiry_date                = $request->pypExpiryDate !== null? \DateTime::createFromFormat('d/m/Y',$request->pypExpiryDate):"";
            $policy->agent                          = ($request->has('sourcingAgentId'))?$request->sourcingAgentId:0;
            $policy->vehicle_make                   = $request->vehicleMake;
            $policy->vehicle_registration_no        = $request->vehicleRegistrationNo;
            $policy->year_of_manufacture            = $request->yearOfManufacture;
            $policy->ncb                            = ($request->has('ncb'))?$request->ncb:0;
            $policy->gross_premium_amount           = $request->grossPremiumAmount;
            $policy->risk_start_date                =  \DateTime::createFromFormat('d-m-Y',$request->riskStartDate);
            $policy->risk_end_date                  =  \DateTime::createFromFormat('d-m-Y',$request->riskEndDate);
            $policy->business_type                  = $request->motorBusinessType;
            $policy->remarks                        = $request->remarks;
            $policy->tp                             = ($request->has('tp'))?$request->tp:0;
            $policy->od                             = ($request->has('od'))?$request->od:0;
            $policy->created_by                     = $request->userId;
            $policy->status                         = 1;
            $policy->save();
            if($policy){
                // Handle policy attachments
                    if ($request->hasFile('policyAttachments')) {
                        $policyAttachments = $request->file('policyAttachments');
                        // Ensure it's always an array, even if it's a single file
                        if (!is_array($policyAttachments)) {
                            $policyAttachments = [$policyAttachments];
                        }
            
                        foreach ($policyAttachments as $file) {
                            if ($file && $file->isValid()) {
                                $destinationPath1 = 'public/policy_attachment/';
                                $docImage1 = date('YmdHis') . rand(1, 100) . "." . $file->getClientOriginalExtension();
                                $file->move($destinationPath1, $docImage1);
                                $img_attachment = $docImage1;
            
                                $attachment = new PolicyAttachment();
                                $attachment->policy_id = $policy->id;
                                $attachment->file = $img_attachment;
                                $attachment->save();
                            }
                        }
                    }
            
                    // Handle policy documents
                    if ($request->hasFile('policyDocuments')) {
                        $policyDocuments = $request->file('policyDocuments');
                        // Ensure it's always an array, even if it's a single file
                        if (!is_array($policyDocuments)) {
                            $policyDocuments = [$policyDocuments];
                        }
            
                        foreach ($policyDocuments as $document) {
                            if ($document && $document->isValid()) {
                                $destinationPath = 'public/policy_document/';
                                $docImage = date('YmdHis') . rand(1, 100) . "." . $document->getClientOriginalExtension();
                                $document->move($destinationPath, $docImage);
                                $image_name = $document->getClientOriginalName();
                                $img = $docImage;
            
                                $policy_document = new PolicyDocument();
                                $policy_document->policy_id = $policy->id;
                                $policy_document->file = $img;
                                $policy_document->file_name = $image_name;
                                $policy_document->save();
                            }
                        }
                    }
                if($request->has('policyParameters')){
                    foreach($request->policyParameters as $param){
                        if($param->typeid == 1 || $param->typeid == 2){
                            $parameter = new PolicyParameter();
                            $parameter->type                    = $param->typeid;
                            $parameter->policy_id               = $policy->id;
                            $parameter->sub_category_id         = $request->subcategory;
                            $parameter->parameter_id            = $param->tpCalculationParameterId;
                            $parameter->value                   = $param->tpCalculationParameterId;
                            $parameter->save();
                        }elseif($param->typeid == 3){
                            $parameter = new PolicyParameter();
                            $parameter->type                    = $param->typeid;
                            $parameter->policy_id               = $policy->id;
                            $parameter->sub_category_id         = $request->subcategory;
                            $parameter->parameter_id            = $param->tpCalculationParameterId;
                            $parameter->taxi_cc                  = $param->value;
                            $parameter->save();
                        }elseif($param->typeid == 4){
                            $parameter = new PolicyParameter();
                            $parameter->type                    = $param->typeid;
                            $parameter->policy_id               = $policy->id;
                            $parameter->sub_category_id         = $request->subcategory;
                            $parameter->parameter_id            = $param->tpCalculationParameterId;
                            $parameter->value                   = $param->tpCalculationParameterId;
                            $parameter->save();
                        }elseif($param->typeid == 5 || $param->typeid == 6){
                            $parameter = new PolicyParameter();
                            $parameter->type                    = $param->typeid;
                            $parameter->policy_id               = $policy->id;
                            $parameter->sub_category_id         = $request->subcategory;
                            $parameter->parameter_id            = $param->tpCalculationParameterId;
                            $parameter->value                   = $param->tpCalculationParameterId;
                            $parameter->save();
                        }elseif($param->typeid == 7){
                            $parameter = new PolicyParameter();
                            $parameter->type                    = $param->typeid;
                            $parameter->policy_id               = $policy->id;
                            $parameter->sub_category_id         = $request->subcategory;
                            $parameter->parameter_id            = $param->tpCalculationParameterId;
                            $parameter->value                   = $param->value;
                            $parameter->save();
                        }
                    }
                }
            //   dd($request->policyPayments);
                if($request->has('policyPayments')){
                    foreach($request->policyPayments as $payments){
                        $payment_type = 1;
                        if($payments['paymentType'] == 'CHEQUE'){
                            $payment_type   = 2;
                        }elseif($payments['paymentType'] == 'CASH'){
                            $payment_type   = 1;
                        }elseif($payments['paymentType'] == 'ONLINE'){
                            $payment_type   = 3;
                        }
                        $policy_payment = new PolicyPayment();
                        $policy_payment->policy_id          = $policy->id;
                        $policy_payment->payment_type       = $payment_type;
                        if($payment_type == 2){
                            $policy_payment->cheque_no      = $payments['chequeNumber'];
                            $policy_payment->cheque_date    =  \DateTime::createFromFormat('d/m/Y',$payments['chequeDate']);
                            $policy_payment->bank_name      = $payments['bankName'];
                        }
                        if($payment_type == 3){
                            $policy_payment->transaction_no = $payments['transactionId'];
                        }
                        $policy_payment->made_by            = (isset($payments['made_by']))?$payments['made_by']:"";
                        $policy_payment->payment_date       = date('Y/m/d');
                        $policy_payment->status             = 1;
                        $policy_payment->save();
                    }
                }
                try{
                    //send notification to Admin
                    $userSchema = User::first();
                    $details = [
                        'name'  => 'Policy Added.',
                        'type'  => 'Policy',
                        'body'  => $policy->policy_no.' '.'added by '. $userSchema->name,
                        'url'   => route('admin.policies'),
                    ];
                    Notification::send($userSchema, new Notifications($details));
                    if($request->userId != 1){
                        $userSchema11 = User::where('id',$request->userId)->first();
                        $details11 = [
                            'name'  => 'Policy Added.',
                            'type'  => 'Policy',
                            'body'  => $policy->policy_no.' '.'added by '. $userSchema11->name,
                            'url'   => route('admin.policies'),
                        ];
                        Notification::send($userSchema11, new Notifications($details11));
                    }
                    $agent = SourcingAgent::where('id',$request->sourcingAgentId)->first();
                    $userSchema1 = User::where('id',$agent->user_id)->first();
                    $details1 = [
                        'name'  => 'Policy Added.',
                        'type'  => 'Policy',
                        'body'  => $policy->policy_no.' '.'added by '. $userSchema1->name,
                        'url'   => route('admin.policies'),
                    ];
                    Notification::send($userSchema1, new Notifications($details1));
                }catch(\Exception $e){
        
                }
                return response()->json(['result'=>200,'status'=>1,'description'=>'Save successfully.','message'=> 'Success!.'],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }
    }
    public function addHealthInsurancePolicy(Request $request){
        // print_r($request->All());
        // exit;
        $messages = [
            'pypNo.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypInsuranceCompany.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypExpiryDate.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
        ];
        $validator = Validator::make($request->all(), [
            'categoryId'                    => 'required',
            'insuranceCompanyId'            => 'required',
            'customerId'                    => 'required|not_in:0',
            // 'idvAmount'                     => 'required',
            'healthBusinessType'            => 'required',
            'healthPlan'                    => 'required',
            // 'sourcingAgentId'               => 'required|not_in:0',
            'sumInsuredAmount'              => 'required',
            'grossPremiumAmount'            => 'required',
            'netPremiumAmount'              => 'required',
            'riskStartDate'                 => 'required',
            'riskEndDate'                   => 'required',
            // 'ncb'                           => 'required',
            // 'teamLead'                      => 'required|not_in:0',
            // 'managedBy'                     => 'required|not_in:0',
            'pypNo'                         => 'required_if:healthBusinessType,2|required_if:healthBusinessType,3',
            'pypInsuranceCompany'           => 'required_if:healthBusinessType,2|required_if:healthBusinessType,3',
            'pypExpiryDate'                 => 'required_if:healthBusinessType,2|required_if:healthBusinessType,3',
        ], $messages);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $policy = new Policy();
            $policy->insurance_type                 = 2;
            $policy->category                       = $request->categoryId;
            $policy->health_plan                    = $request->healthPlan;
            $policy->company                        = $request->insuranceCompanyId;
            $policy->covernote_no                   = $request->covernoteNo;
            $policy->policy_no                      = $request->policyNo;
            $policy->customer                       = $request->customerId;
            $policy->team_lead                      = ($request->has('teamLead'))?$request->teamLead:0;
            $policy->managed_by                     = ($request->has('pypNmanagedByo'))?$request->managedBy:0;
            $policy->net_premium_amount             = $request->netPremiumAmount;
            $policy->pyp_no                         = ($request->has('pypNo'))?$request->pypNo:"";
            $policy->pyp_insurance_company          = ($request->has('pypInsuranceCompany'))?$request->pypInsuranceCompany:"";
            $policy->pyp_expiry_date                = $request->pypExpiryDate !== null? \DateTime::createFromFormat('d-m-Y',$request->pypExpiryDate):"";
            $policy->agent                          = ($request->has('sourcingAgentId'))?$request->sourcingAgentId:0;
            $policy->gross_premium_amount           = $request->grossPremiumAmount;
            $policy->ncb                            = ($request->has('ncb'))?$request->ncb:0;
            $policy->risk_start_date                =  \DateTime::createFromFormat('d-m-Y',$request->riskStartDate);
            $policy->risk_end_date                  =  \DateTime::createFromFormat('d-m-Y',$request->riskEndDate);
            $policy->tp                             = ($request->has('tp'))?$request->tp:0;
            $policy->od                             = ($request->has('od'))?$request->od:0;
            $policy->business_type                  = $request->healthBusinessType;
            $policy->remarks                        = $request->remarks;
            $policy->created_by                     = $request->userId;
            $policy->status                         = 1;
            $policy->save();
            // if($policy){
                if ($request->hasFile('policyAttachments')) {
                    $policyAttachments = $request->file('policyAttachments');
                    // Ensure it's always an array, even if it's a single file
                    if (!is_array($policyAttachments)) {
                        $policyAttachments = [$policyAttachments];
                    }
                
                    foreach ($policyAttachments as $file) {
                        if ($file && $file->isValid()) {
                            $destinationPath1 = 'public/policy_attachment/';
                            $docImage1 = date('YmdHis') . rand(1, 100) . "." . $file->getClientOriginalExtension();
                            $file->move($destinationPath1, $docImage1);
                            $img_attachment = $docImage1;
                
                            $attachment = new PolicyAttachment();
                            $attachment->policy_id = $policy->id;
                            $attachment->file = $img_attachment;
                            $attachment->save();
                        } else {
                            $img_attachment = '';
                        }
                    }
                }
                
                if ($request->hasFile('policyDocuments')) {
                    $policyDocuments = $request->file('policyDocuments');
                    // Ensure it's always an array, even if it's a single file
                    if (!is_array($policyDocuments)) {
                        $policyDocuments = [$policyDocuments];
                    }
                
                    foreach ($policyDocuments as $document) {
                        if ($document && $document->isValid()) {
                            $destinationPath = 'public/policy_document/';
                            $docImage = date('YmdHis') . rand(1, 100) . "." . $document->getClientOriginalExtension();
                            $document->move($destinationPath, $docImage);
                            $image_name = $document->getClientOriginalName();
                            $img = $docImage;
                
                            $policy_document = new PolicyDocument();
                            $policy_document->policy_id = $policy->id;
                            $policy_document->file = $img;
                            $policy_document->file_name = $image_name;
                            $policy_document->save();
                        }
                    }
                }

                // $payment = PolicyPayment::where('policy_id',$policy->id)->delete();
                if($request->has('policyPayments')){
                    foreach($request->policyPayments as $payments){
                        $payment_type = 1;
                        if($payments['paymentType'] == 'CHEQUE'){
                            $payment_type   = 2;
                        }elseif($payments['paymentType'] == 'CASH'){
                            $payment_type   = 1;
                        }elseif($payments['paymentType'] == 'ONLINE'){
                            $payment_type   = 3;
                        }
                        $policy_payment = new PolicyPayment();
                        $policy_payment->policy_id          = $policy->id;
                        $policy_payment->payment_type       = $payment_type;
                        if($payment_type == 2){
                            $policy_payment->cheque_no      = $payments['chequeNumber'];
                            $policy_payment->cheque_date    =  \DateTime::createFromFormat('d/m/Y',$payments['chequeDate']);
                            $policy_payment->bank_name      = $payments['bankName'];
                        }
                        if($payment_type == 3){
                            $policy_payment->transaction_no = $payments['transactionId'];
                        }
                        $policy_payment->made_by            = (isset($payments['made_by']))?$payments['made_by']:"";
                        $policy_payment->payment_date       = date('Y/m/d');
                        $policy_payment->status             = 1;
                        $policy_payment->save();
                    }
                }
                try{
                    //send notification to Admin
                    $userSchema = User::first();
                    $details = [
                        'name'  => 'Policy Added.',
                        'type'  => 'Policy',
                        'body'  => $policy->policy_no.' '.'added by '. $userSchema->name,
                        'url'   => route('admin.policies'),
                    ];
                    Notification::send($userSchema, new Notifications($details));
                    if($request->userId != 1){
                        $userSchema11 = User::where('id',$request->userId)->first();
                        $details11 = [
                            'name'  => 'Policy Added.',
                            'type'  => 'Policy',
                            'body'  => $policy->policy_no.' '.'added by '. $userSchema11->name,
                            'url'   => route('admin.policies'),
                        ];
                        Notification::send($userSchema11, new Notifications($details11));
                    }
                    $agent = SourcingAgent::where('id',$request->sourcingAgentId)->first();
                    $userSchema1 = User::where('id',$agent->user_id)->first();
                    $details1 = [
                        'name'  => 'Policy Added.',
                        'type'  => 'Policy',
                        'body'  => $policy->policy_no.' '.'added by '. $userSchema1->name,
                        'url'   => route('admin.policies'),
                    ];
                    Notification::send($userSchema1, new Notifications($details1));
                }catch(\Exception $e){
        
                }
                return response()->json(['result'=>200,'status'=>1,'description'=>'Save successfully.','message'=> 'Success!.'],200);
            // }else{
            //     return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            // }
        }
        
    }
public function editVehicleInsurancePolicy(Request $request) {
    try {
        // Log incoming request data for debugging
        \Log::info('Request Data: ', $request->all());

        $messages = [
            'pypNo.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypInsuranceCompany.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypExpiryDate.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
        ];

        $validator = Validator::make($request->all(), [
            'categoryId' => 'required',
            'subCategoryId' => 'required',
            'insuranceCompanyId' => 'required',
            'customerId' => 'required|not_in:0',
            'vehicleModel' => 'required',
            'vehicleEngine' => 'required',
            'vehicleMake' => 'required',
            'yearOfManufacture' => 'required',
            'vehicleChassisNo' => 'required',
            'idvAmount' => 'required',
            'motorBusinessType' => 'required',
            'sourcingAgentId' => 'required|not_in:0',
            'grossPremiumAmount' => 'required',
            'netPremiumAmount' => 'required',
            'riskStartDate' => 'required',
            'riskEndDate' => 'required',
            'ncb' => 'required',
            'teamLead' => 'required|not_in:0',
            'managedBy' => 'required|not_in:0',
            'policyType' => 'required_if:categoryId,1',
            'pypNo' => 'required_if:motorBusinessType,2|required_if:motorBusinessType,3',
            'pypInsuranceCompany' => 'required_if:motorBusinessType,2|required_if:motorBusinessType,3',
            'pypExpiryDate' => 'required_if:motorBusinessType,2|required_if:motorBusinessType,3',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        } else {
            $policy = Policy::where('id',$request->id)->first();

            if (!$policy) {
                return response()->json(['result'=>200,'status'=>0,'description'=>'Policy not found.','error'=> 'Policy not found.'],200);
            }

            $policy->category = $request->categoryId;
            $policy->policy_type = $request->policyType;
            $policy->business_source                    = $request->bussinesSourceId;
            $policy->policy_individual_rate = $request->policyIndividualRate;
            $policy->sub_category = $request->subCategoryId;
            $policy->company = $request->insuranceCompanyId;
            $policy->covernote_no = $request->covernoteNo;
            $policy->policy_no = $request->policyNo;
            $policy->customer = $request->customerId;
            $policy->vehicle_model = $request->vehicleModel;
            $policy->vehicle_engine = $request->vehicleEngine;
            $policy->vehicle_chassis_no = $request->vehicleChassisNo;
            $policy->idv_amount = $request->idvAmount;
            $policy->team_lead = $request->teamLead;
            $policy->managed_by = $request->managedBy;
            $policy->net_premium_amount = $request->netPremiumAmount;
            $policy->pyp_no = ($request->has('pypNo'))?$request->pypNo:"";
            $policy->pyp_insurance_company = ($request->has('pypInsuranceCompany'))?$request->pypInsuranceCompany:"";
            $policy->pyp_expiry_date = $request->pypExpiryDate !== null? \DateTime::createFromFormat('d-m-Y',$request->pypExpiryDate):"";
            $policy->agent = $request->sourcingAgentId;
            $policy->vehicle_make = $request->vehicleMake;
            $policy->vehicle_registration_no = $request->vehicleRegistrationNo;
            $policy->year_of_manufacture = $request->yearOfManufacture;
            $policy->ncb = $request->ncb;
            $policy->tp = ($request->has('tp'))?$request->tp:0;
            $policy->od = ($request->has('od'))?$request->od:0;
            $policy->gross_premium_amount = $request->grossPremiumAmount;
            $policy->risk_start_date =  \DateTime::createFromFormat('d-m-Y',$request->riskStartDate);
            $policy->risk_end_date =  \DateTime::createFromFormat('d-m-Y',$request->riskEndDate);
            $policy->business_type = $request->motorBusinessType;
            $policy->remarks = $request->remarks;
            $policy->created_by = $request->userId;
            $policy->status = 1;
            $policy->save();

            if($policy) {
                if($request->has('policyAttachments')){
                    foreach($request->policyAttachments as $file){
                        if($file && $file != null){
                            $image_file = $file;
                            $destinationPath1 = 'public/policy_attachment/';
                            $rand1=rand(1,100);
                            $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment=$docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new PolicyAttachment();
                        $attachment->policy_id = $policy->id;
                        $attachment->file = $img_attachment;
                        $attachment->save();
                    }
                }
                if($request->has('policyDocuments')){
                    foreach($request->policyDocuments as $document){
                        $image = $document;
                        $destinationPath = 'public/policy_document/';
                        $rand=rand(1,100);
                        $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
                        $image->move($destinationPath, $docImage);
                        $image_name = $image->getClientOriginalName();
                        $img=$docImage;
                        $policy_document = new PolicyDocument();
                        $policy_document->policy_id = $policy->id;
                        $policy_document->file = $img;
                        $policy_document->file_name = $image_name;
                        $policy_document->save();
                    }
                }
                $parameterDelete = PolicyParameter::where('policy_id',$policy->id)->delete();
                if($request->has('policyParameters')){
                    foreach($request->policyParameters as $param){
                        if($param->typeid == 1 || $param->typeid == 2){
                            $parameter = new PolicyParameter();
                            $parameter->type = $param->typeid;
                            $parameter->policy_id = $policy->id;
                            $parameter->sub_category_id = $request->subcategory;
                            $parameter->parameter_id = $param->tpCalculationParameterId;
                            $parameter->value = $param->tpCalculationParameterId;
                            $parameter->save();
                        } elseif($param->typeid == 3){
                            $parameter = new PolicyParameter();
                            $parameter->type = $param->typeid;
                            $parameter->policy_id = $policy->id;
                            $parameter->sub_category_id = $request->subcategory;
                            $parameter->parameter_id = $param->tpCalculationParameterId;
                            $parameter->taxi_cc = $param->value;
                            $parameter->save();
                        } elseif($param->typeid == 4){
                            $parameter = new PolicyParameter();
                            $parameter->type = $param->typeid;
                            $parameter->policy_id = $policy->id;
                            $parameter->sub_category_id = $request->subcategory;
                            $parameter->parameter_id = $param->tpCalculationParameterId;
                            $parameter->value = $param->tpCalculationParameterId;
                            $parameter->save();
                        } elseif($param->typeid == 5 || $param->typeid == 6){
                            $parameter = new PolicyParameter();
                            $parameter->type = $param->typeid;
                            $parameter->policy_id = $policy->id;
                            $parameter->sub_category_id = $request->subcategory;
                            $parameter->parameter_id = $param->tpCalculationParameterId;
                            $parameter->value = $param->tpCalculationParameterId;
                            $parameter->save();
                        }
                    }
                }
                return response()->json(['result'=>200,'status'=>1,'description'=>'Updated Successfully'],200);
            }
        }
    } catch (\Exception $e) {
        // Log the error message for debugging
        \Log::error('Error editing vehicle insurance policy: '.$e->getMessage());
        return response()->json(['status'=>0, 'error'=>'An unexpected error occurred. Please try again.'], 500);
    }
}

    public function editHealthInsurancePolicy(Request $request){
        // print_r($request->All());
        // exit;
        $messages = [
            'pypNo.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypInsuranceCompany.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypExpiryDate.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
        ];
        $validator = Validator::make($request->all(), [
            'categoryId'                    => 'required',
            'insuranceCompanyId'            => 'required',
            'customerId'                    => 'required|not_in:0',
            // 'idvAmount'                     => 'required',
            'healthBusinessType'            => 'required',
            // 'healthPlan'                    => 'required',
            'sourcingAgentId'               => 'required|not_in:0',
            'sumInsuredAmount'              => 'required',
            'grossPremiumAmount'            => 'required',
            'netPremiumAmount'              => 'required',
            'riskStartDate'                 => 'required',
            'riskEndDate'                   => 'required',
            'ncb'                           => 'required',
            'teamLead'                      => 'required|not_in:0',
            'managedBy'                     => 'required|not_in:0',
            'pypNo'                         => 'required_if:healthBusinessType,2|required_if:healthBusinessType,3',
            'pypInsuranceCompany'           => 'required_if:healthBusinessType,2|required_if:healthBusinessType,3',
            'pypExpiryDate'                 => 'required_if:healthBusinessType,2|required_if:healthBusinessType,3',
        ], $messages);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $policy = Policy::where('id',$request->id)->first();
            $policy->category                       = $request->categoryId;
            $policy->health_plan                    = $request->healthPlan;
            $policy->company                        = $request->insuranceCompanyId;
            $policy->covernote_no                   = $request->covernoteNo;
            $policy->policy_no                      = $request->policyNo;
            $policy->customer                       = $request->customerId;
            $policy->team_lead                      = $request->teamLead;
            $policy->managed_by                     = $request->managedBy;
            $policy->net_premium_amount             = $request->netPremiumAmount;
            $policy->pyp_no                         = ($request->has('pypNo'))?$request->pypNo:"";
            $policy->pyp_insurance_company          = ($request->has('pypInsuranceCompany'))?$request->pypInsuranceCompany:"";
            $policy->pyp_expiry_date                = $request->pypExpiryDate !== null? \DateTime::createFromFormat('d-m-Y',$request->pypExpiryDate):"";
            $policy->agent                          = $request->sourcingAgentId;
            $policy->gross_premium_amount           = $request->grossPremiumAmount;
            $policy->ncb                            = $request->ncb;
            $policy->tp                             = ($request->has('tp'))?$request->tp:0;
            $policy->od                             = ($request->has('od'))?$request->od:0;
            $policy->risk_start_date                =  \DateTime::createFromFormat('d-m-Y',$request->riskStartDate);
            $policy->risk_end_date                  =  \DateTime::createFromFormat('d-m-Y',$request->riskEndDate);
            $policy->business_type                  = $request->healthBusinessType;
            $policy->remarks                        = $request->remarks;
            $policy->created_by                     = $request->userId;
            $policy->status                         = 1;
            $policy->save();
            if($policy){
                if($request->has('policyAttachments')){
                    foreach($request->policyAttachments as $file){
                        if($file && $file != null){
                            $image_file = $file;
                            $destinationPath1 = 'public/policy_attachment/';
                            $rand1=rand(1,100);
                            $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment=$docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new PolicyAttachment();
                        $attachment->policy_id = $policy->id;
                        $attachment->file = $img_attachment;
                        $attachment->save();
                    }
                }
                if($request->has('policyDocuments')){
                    foreach($request->policyDocuments as $document){
                        $image = $document;
                        $destinationPath = 'public/policy_document/';
                        $rand=rand(1,100);
                        $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
                        $image->move($destinationPath, $docImage);
                        $image_name = $image->getClientOriginalName();
                        $img=$docImage;
                        $policy_document = new PolicyDocument();
                        $policy_document->policy_id = $policy->id;
                        $policy_document->file      = $img;
                        $policy_document->file_name = $image_name;
                        $policy_document->save();
                    }
                }
                $payment = PolicyPayment::where('policy_id',$policy->id)->delete();
                if($request->has('policyPayments')){
                    foreach($request->policyPayments as $payments){
                        $payment_type = 1;
                        if($payments['paymentType'] == 'CHEQUE'){
                            $payment_type   = 2;
                        }elseif($payments['paymentType'] == 'CASH'){
                            $payment_type   = 1;
                        }elseif($payments['paymentType'] == 'ONLINE'){
                            $payment_type   = 3;
                        }
                        $policy_payment = new PolicyPayment();
                        $policy_payment->policy_id          = $policy->id;
                        $policy_payment->payment_type       = $payment_type;
                        if($payment_type == 2){
                            $policy_payment->cheque_no      = $payments['chequeNumber'];
                            $policy_payment->cheque_date    =  \DateTime::createFromFormat('d/m/Y',$payments['chequeDate']);
                            $policy_payment->bank_name      = $payments['bankName'];
                        }
                        if($payment_type == 3){
                            $policy_payment->transaction_no = $payments->transactionId;
                        }
                        $policy_payment->made_by            = (isset($payments['made_by']))?$payments['made_by']:"";
                        $policy_payment->payment_date       = date('Y/m/d');
                        $policy_payment->status             = 1;
                        $policy_payment->save();
                    }
                }
                return response()->json(['result'=>200,'status'=>1,'description'=>'Updated successfully.','message'=> 'Success!.'],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }
    }
    public function deleteVehicleInsurancePolicy(Request $request, $id = NULL){
        $policy_attachments = PolicyAttachment::where('policy_id',$id)->delete();
        $policy_documents = PolicyDocument::where('policy_id',$id)->delete();
        $policy_parameter = PolicyParameter::where('policy_id',$id)->delete();
        $policy_payment = PolicyPayment::where('policy_id',$id)->delete();
        $policy = Policy::where('id',$id)->delete();
        if($policy){
            return response()->json(['result'=>200,'status'=>1,'description'=>'Deleted successfully.','message'=> 'Success!.'],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
        }
    }
    public function deleteHealthInsurancePolicy(Request $request, $id = NULL){
        $policy_attachments = PolicyAttachment::where('policy_id',$id)->delete();
        $policy_documents = PolicyDocument::where('policy_id',$id)->delete();
        $policy_parameter = PolicyParameter::where('policy_id',$id)->delete();
        $policy_payment = PolicyPayment::where('policy_id',$id)->delete();
        $policy = Policy::where('id',$id)->delete();
        if($policy){
            return response()->json(['result'=>200,'status'=>1,'description'=>'Deleted successfully.','message'=> 'Success!.'],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
        }
    }
    public function renewVehicleInsurancePolicy(Request $request){
        // print_r($request->All());
        // exit;
        $messages = [
            'pypNo.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypInsuranceCompany.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypExpiryDate.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
        ];
        $validator = Validator::make($request->all(), [
            'categoryId'                    => 'required',
            'subCategoryId'                 => 'required',
            'insuranceCompanyId'            => 'required',
            'customerId'                    => 'required|not_in:0',
            'vehicleModel'                  => 'required',
            'vehicleEngine'                 => 'required',
            'vehicleMake'                   => 'required',
            'yearOfManufacture'             => 'required',
            'vehicleChassisNo'              => 'required',
            'idvAmount'                     => 'required',
            'motorBusinessType'             => 'required',
            'sourcingAgentId'               => 'required|not_in:0',
            'grossPremiumAmount'            => 'required',
            'netPremiumAmount'              => 'required',
            'riskStartDate'                 => 'required',
            'riskEndDate'                   => 'required',
            // 'ncb'                           => 'required',
            // 'teamLead'                      => 'required|not_in:0',
            // 'managedBy'                     => 'required|not_in:0',
            'policyType'                    => 'required_if:categoryId,1',
            'pypNo'                         => 'required_if:motorBusinessType,2|required_if:motorBusinessType,3',
            'pypInsuranceCompany'           => 'required_if:motorBusinessType,2|required_if:motorBusinessType,3',
            'pypExpiryDate'                 => 'required_if:motorBusinessType,2|required_if:motorBusinessType,3',
        ], $messages);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $policy = new Policy();
            $policy->insurance_type                 = 1;
            $policy->category                       = $request->categoryId;
            $policy->policy_type                    = $request->policyType;
            $policy->policy_individual_rate         = $request->policyIndividualRate;
            $policy->sub_category                   = $request->subCategoryId;
            $policy->company                        = $request->insuranceCompanyId;
            $policy->covernote_no                   = $request->covernoteNo;
            $policy->policy_no                      = $request->policyNo;
            $policy->customer                       = $request->customerId;
            $policy->vehicle_model                  = $request->vehicleModel;
            $policy->vehicle_engine                 = $request->vehicleEngine;
            $policy->vehicle_chassis_no             = $request->vehicleChassisNo;
            $policy->idv_amount                     = $request->idvAmount;
            $policy->team_lead                      = ($request->has('teamLead'))?$request->teamLead:0;
            $policy->managed_by                     = ($request->has('managedBy'))?$request->managedBy:0;
            $policy->net_premium_amount             = $request->netPremiumAmount;
            $policy->pyp_no                         = ($request->has('pypNo'))?$request->pypNo:"";
            $policy->pyp_insurance_company          = ($request->has('pypInsuranceCompany'))?$request->pypInsuranceCompany:"";
            $policy->pyp_expiry_date                = ($request->has('pypExpiryDate'))?$request->pypExpiryDate:"";
            $policy->agent                          = ($request->has('sourcingAgentId'))?$request->sourcingAgentId:0;
            $policy->vehicle_make                   = $request->vehicleMake;
            $policy->vehicle_registration_no        = $request->vehicleRegistrationNo;
            $policy->year_of_manufacture            = $request->yearOfManufacture;
            $policy->ncb                            = ($$request->has('ncb'))?$request->ncb:0;
            $policy->tp                             = ($request->has('tp'))?$request->tp:0;
            $policy->od                             = ($request->has('od'))?$request->od:0;
            $policy->gross_premium_amount           = $request->grossPremiumAmount;
            $policy->risk_start_date                =  \DateTime::createFromFormat('d/m/Y',$request->riskStartDate);
            $policy->risk_end_date                  =  \DateTime::createFromFormat('d/m/Y',$request->riskEndDate);
            $policy->business_type                  = $request->motorBusinessType;
            $policy->remarks                        = $request->remarks;
            $policy->created_by                     = $request->userId;
            $policy->status                         = 1;
            $policy->save();
            if($policy){
                if($request->has('policyAttachments')){
                    foreach($request->policyAttachments as $file){
                        if($file && $file != null){
                            $image_file = $file;
                            $destinationPath1 = 'public/policy_attachment/';
                            $rand1=rand(1,100);
                            $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment=$docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new PolicyAttachment();
                        $attachment->policy_id = $policy->id;
                        $attachment->file = $img_attachment;
                        $attachment->save();
                    }
                }
                if($request->has('policyDocuments')){
                    foreach($request->policyDocuments as $document){
                        $image = $document;
                        $destinationPath = 'public/policy_document/';
                        $rand=rand(1,100);
                        $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
                        $image->move($destinationPath, $docImage);
                        $image_name = $image->getClientOriginalName();
                        $img=$docImage;
                        $policy_document = new PolicyDocument();
                        $policy_document->policy_id = $policy->id;
                        $policy_document->file      = $img;
                        $policy_document->file_name = $image_name;
                        $policy_document->save();
                    }
                }
                // if($request->has('param')){
                //     foreach($request->param as $key=>$value){
                //         if($key == 'taxi'){
                //             $parameter = new PolicyParameter();
                //             $parameter->policy_id               = $policy->id;
                //             $parameter->type                    = 3;
                //             $parameter->sub_category_id         = $request->subcategory;
                //             $parameter->parameter_id            = $value['cc']['value'];
                //             $parameter->taxi_cc                 = $value['cc']['value'];
                //             $parameter->seating_capacity        = $value['seating_capacity']['value'];
                //             $parameter->paid_driver             = $value['paid_driver']['value'];
                //             $parameter->save();
                //         }elseif($key == 'cc'){
                //             $parameter = new PolicyParameter();
                //             $parameter->policy_id               = $policy->id;
                //             $parameter->type                    = 5;
                //             $parameter->sub_category_id         = $request->subcategory;
                //             $parameter->parameter_id            = $value['value'];
                //             $parameter->save();
                //         }elseif($key == 'pa_to_passanger'){
                //             $parameter = new PolicyParameter();
                //             $parameter->policy_id               = $policy->id;
                //             $parameter->type                    = 6;
                //             $parameter->sub_category_id         = $request->subcategory;
                //             $parameter->parameter_id            = $value['value'];
                //             $parameter->save();
                //         }elseif($key == 'public'){
                //             $parameter = new PolicyParameter();
                //             $parameter->type                    = 1;
                //             $parameter->policy_id               = $policy->id;
                //             $parameter->sub_category_id         = $request->subcategory;
                //             $parameter->parameter_id            = $value['value'];
                //             $parameter->value                   = $value['value'];
                //             $parameter->save();
                //         }elseif($key == 'private'){
                //             $parameter = new PolicyParameter();
                //             $parameter->type                    = 2;
                //             $parameter->policy_id               = $policy->id;
                //             $parameter->sub_category_id         = $request->subcategory;
                //             $parameter->parameter_id            = $value['value'];
                //             $parameter->value                   = $value['value'];
                //             $parameter->save();
                //         }elseif($key == 'custom'){
                //             foreach($value as $custom_key=>$custom_value){
                //                 $parameter = new PolicyParameter();
                //                 $parameter->type                    = 7;
                //                 $parameter->policy_id               = $policy->id;
                //                 $parameter->sub_category_id         = $request->subcategory;
                //                 $parameter->parameter_id            = $custom_key;
                //                 $parameter->value                   = $custom_value['value'];
                //                 $parameter->save();
                //             }
                //         }elseif($key == 'bus'){
                //             foreach($value as $bus_key=>$bus_value){
        
                //                 $parameter = new PolicyParameter();
                //                 $parameter->type                    = 4;
                //                 $parameter->policy_id               = $policy->id;
                //                 $parameter->sub_category_id         = $request->subcategory;
                //                 $parameter->parameter_id            = $bus_key;
                //                 $parameter->value                   = $bus_value['value'];
                //                 $parameter->save();
                //             }
                //         }
                //     }
                // }
                if($request->has('policyPayments')){
                    foreach($request->policyPayments as $payments){
                        $payment_type = 1;
                        if($payments['paymentType'] == 'CHEQUE'){
                            $payment_type   = 2;
                        }elseif($payments['paymentType'] == 'CASH'){
                            $payment_type   = 1;
                        }elseif($payments['paymentType'] == 'ONLINE'){
                            $payment_type   = 3;
                        }
                        $policy_payment = new PolicyPayment();
                        $policy_payment->policy_id          = $policy->id;
                        $policy_payment->payment_type       = $payment_type;
                        if($payment_type == 2){
                            $policy_payment->cheque_no      = $payments['chequeNumber'];
                            $policy_payment->cheque_date    =  \DateTime::createFromFormat('d/m/Y',$payments['chequeDate']);
                            $policy_payment->bank_name      = $payments['bankName'];
                        }
                        if($payment_type == 3){
                            $policy_payment->transaction_no = $payments->transactionId;
                        }
                        $policy_payment->made_by            = (isset($payments['made_by']))?$payments['made_by']:"";
                        $policy_payment->payment_date       = date('Y/m/d');
                        $policy_payment->status             = 1;
                        $policy_payment->save();
                    }
                }
                try{
                    //send notification to Admin
                    $userSchema = User::first();
                    $details = [
                        'name'  => 'Policy Renew.',
                        'type'  => 'Policy',
                        'body'  => $policy->policy_no.' '.'renew by '. $userSchema->name,
                        'url'   => route('admin.policies'),
                    ];
                    Notification::send($userSchema, new Notifications($details));
                    if($request->userId != 1){
                        $userSchema11 = User::where('id',$request->userId)->first();
                        $details11 = [
                            'name'  => 'Policy Renew.',
                            'type'  => 'Policy',
                            'body'  => $policy->policy_no.' '.'renew by '. $userSchema11->name,
                            'url'   => route('admin.policies'),
                        ];
                        Notification::send($userSchema11, new Notifications($details11));
                    }
                    $agent = SourcingAgent::where('id',$request->sourcingAgentId)->first();
                    $userSchema1 = User::where('id',$agent->user_id)->first();
                    $details1 = [
                        'name'  => 'Policy Renew.',
                        'type'  => 'Policy',
                        'body'  => $policy->policy_no.' '.'renew by '. $userSchema1->name,
                        'url'   => route('admin.policies'),
                    ];
                    Notification::send($userSchema1, new Notifications($details1));
                }catch(\Exception $e){
        
                }
                return response()->json(['result'=>200,'status'=>1,'description'=>'Renew successfully.','message'=> 'Success!.'],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }
    }
    public function renewHealthInsurancePolicy(Request $request){
        // print_r($request->All());
        // exit;
        $messages = [
            'pypNo.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypInsuranceCompany.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
            'pypExpiryDate.required_if' => 'The PYP number field is required when the motor business type is Renew or Rollover.',
        ];
        $validator = Validator::make($request->all(), [
            'categoryId'                    => 'required',
            'insuranceCompanyId'            => 'required',
            'customerId'                    => 'required|not_in:0',
            // 'idvAmount'                     => 'required',
            'healthBusinessType'            => 'required',
            'healthPlan'                    => 'required',
            // 'sourcingAgentId'               => 'required|not_in:0',
            'sumInsuredAmount'              => 'required',
            'grossPremiumAmount'            => 'required',
            'netPremiumAmount'              => 'required',
            'riskStartDate'                 => 'required',
            'riskEndDate'                   => 'required',
            // 'ncb'                           => 'required',
            // 'teamLead'                      => 'required|not_in:0',
            // 'managedBy'                     => 'required|not_in:0',
            'pypNo'                         => 'required_if:healthBusinessType,2|required_if:healthBusinessType,3',
            'pypInsuranceCompany'           => 'required_if:healthBusinessType,2|required_if:healthBusinessType,3',
            'pypExpiryDate'                 => 'required_if:healthBusinessType,2|required_if:healthBusinessType,3',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $policy = new Policy();
            $policy->insurance_type                 = 2;
            $policy->category                       = $request->categoryId;
            $policy->health_plan                    = $request->healthPlan;
            $policy->company                        = $request->insuranceCompanyId;
            $policy->covernote_no                   = $request->covernoteNo;
            $policy->policy_no                      = $request->policyNo;
            $policy->customer                       = $request->customerId;
            $policy->team_lead                      = $request->teamLead;
            $policy->managed_by                     = $request->managedBy;
            $policy->net_premium_amount             = $request->netPremiumAmount;
            $policy->pyp_no                         = ($request->has('pypNo'))?$request->pypNo:"";
            $policy->pyp_insurance_company          = ($request->has('pypInsuranceCompany'))?$request->pypInsuranceCompany:"";
            $policy->pyp_expiry_date                = ($request->has('pypExpiryDate'))?$request->pypExpiryDate:"";
            $policy->agent                          = $request->sourcingAgentId;
            $policy->gross_premium_amount           = $request->grossPremiumAmount;
            $policy->ncb                            = $request->ncb;
            $policy->tp                             = ($request->has('tp'))?$request->tp:0;
            $policy->od                             = ($request->has('od'))?$request->od:0;
            $policy->risk_start_date                =  \DateTime::createFromFormat('d/m/Y',$request->riskStartDate);
            $policy->risk_end_date                  =  \DateTime::createFromFormat('d/m/Y',$request->riskEndDate);
            $policy->business_type                  = $request->healthBusinessType;
            $policy->remarks                        = $request->remarks;
            $policy->created_by                     = $request->userId;
            $policy->status                         = 1;
            $policy->save();
            // if($policy){
                if($request->has('policyAttachments')){
                    foreach($request->policyAttachments as $file){
                        if($file && $file != null){
                            $image_file = $file;
                            $destinationPath1 = 'public/policy_attachment/';
                            $rand1=rand(1,100);
                            $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment=$docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new PolicyAttachment();
                        $attachment->policy_id = $policy->id;
                        $attachment->file = $img_attachment;
                        $attachment->save();
                    }
                }
                if($request->has('policyDocuments')){
                    foreach($request->policyDocuments as $document){
                        $image = $document;
                        $destinationPath = 'public/policy_document/';
                        $rand=rand(1,100);
                        $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
                        $image->move($destinationPath, $docImage);
                        $image_name = $image->getClientOriginalName();
                        $img=$docImage;
                        $policy_document = new PolicyDocument();
                        $policy_document->policy_id = $policy->id;
                        $policy_document->file      = $img;
                        $policy_document->file_name = $image_name;
                        $policy_document->save();
                    }
                }
                if($request->has('policyPayments')){
                    foreach($request->policyPayments as $payments){
                        $payment_type = 1;
                        if($payments['paymentType'] == 'CHEQUE'){
                            $payment_type   = 2;
                        }elseif($payments['paymentType'] == 'CASH'){
                            $payment_type   = 1;
                        }elseif($payments['paymentType'] == 'ONLINE'){
                            $payment_type   = 3;
                        }
                        $policy_payment = new PolicyPayment();
                        $policy_payment->policy_id          = $policy->id;
                        $policy_payment->payment_type       = $payment_type;
                        if($payment_type == 2){
                            $policy_payment->cheque_no      = $payments['chequeNumber'];
                            $policy_payment->cheque_date    =  \DateTime::createFromFormat('d/m/Y',$payments['chequeDate']);
                            $policy_payment->bank_name      = $payments['bankName'];
                        }
                        if($payment_type == 3){
                            $policy_payment->transaction_no = $payments->transactionId;
                        }
                        $policy_payment->made_by            = (isset($payments['made_by']))?$payments['made_by']:"";
                        $policy_payment->payment_date       = date('Y/m/d');
                        $policy_payment->status             = 1;
                        $policy_payment->save();
                    }
                }
                try{
                    //send notification to Admin
                    $userSchema = User::first();
                    $details = [
                        'name'  => 'Policy Renew.',
                        'type'  => 'Policy',
                        'body'  => $policy->policy_no.' '.'renew by '. $userSchema->name,
                        'url'   => route('admin.policies'),
                    ];
                    Notification::send($userSchema, new Notifications($details));
                    if($request->userId != 1){
                        $userSchema11 = User::where('id',$request->userId)->first();
                        $details11 = [
                            'name'  => 'Policy Renew.',
                            'type'  => 'Policy',
                            'body'  => $policy->policy_no.' '.'renew by '. $userSchema11->name,
                            'url'   => route('admin.policies'),
                        ];
                        Notification::send($userSchema11, new Notifications($details11));
                    }
                    $agent = SourcingAgent::where('id',$request->sourcingAgentId)->first();
                    $userSchema1 = User::where('id',$agent->user_id)->first();
                    $details1 = [
                        'name'  => 'Policy Renew.',
                        'type'  => 'Policy',
                        'body'  => $policy->policy_no.' '.'renew by '. $userSchema1->name,
                        'url'   => route('admin.policies'),
                    ];
                    Notification::send($userSchema1, new Notifications($details1));
                }catch(\Exception $e){
        
                }
                return response()->json(['result'=>200,'status'=>1,'description'=>'Save successfully.','message'=> 'Success!.'],200);
            // }else{
            //     return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            // }
        }
        
    }
    public function cancelInsurancePolicy(Request $request){
        $validator = Validator::make($request->all(), [
            'policyId'                  => 'required',
            'cancel_date'               => 'required',
            'reason'                    => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $policy = Policy::where('id',$request->policyId)->first();
            $policy->status         = 2;
            $policy->cancel_date    =  \DateTime::createFromFormat('d/m/Y',$request->cancel_date);
            $policy->cancel_reason  = $request->reason;
            $policy->save();
            return response()->json(['result'=>200,'status'=>1,'description'=>'Policy Cancelled successfully.','message'=> 'Success!.'],200);
        }
    }
    public function addPolicyAttachment(Request $request){
        $messages = [
            'policyAttachments.required_if' => 'Please select attachment.',
        ];
        $validator = Validator::make($request->all(), [
            'policyId'                    => 'required',
            'policyAttachments'          => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{ 
            if($request->has('policyAttachments')){
                foreach($request->policyAttachments as $file){
                    if($file && $file != null){
                        $image_file = $file;
                        $destinationPath1 = 'public/policy_attachment/';
                        $rand1=rand(1,100);
                        $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                        $image_file->move($destinationPath1, $docImage1);
                        $img_attachment=$docImage1;
                    }else{
                        $img_attachment='';
                    }
                    $attachment = new PolicyAttachment();
                    $attachment->policy_id  = $request->policyId;
                    $attachment->file       = $img_attachment;
                    $attachment->save();
                }
            }
            return response()->json(['result'=>200,'status'=>1,'description'=>'Save successfully.','message'=> 'Success!.'],200);
        }
    }
}
