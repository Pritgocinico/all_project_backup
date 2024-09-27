<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\Models\Claim;
use App\Models\ClaimAttachment;
use App\Models\ClaimRemark;
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
use Illuminate\Support\LazyCollection;

class ClaimController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function getMotorClaimList(Request $request){
        $query = Claim::orderBy('id','DESC')->with('policy');
        $query->whereHas('policy', function($query1) use ($request){
            $query1->where('insurance_type', 1);
        });
        if($request->has('id') && $request->id != ''){
            $query->where('id',$request->id);
        }
        if($request->has('claim_status') && $request->claim_status != ''){
            if($request->claim_status == "Open"){
                $query->where('claim_status',1);
            }elseif($request->claim_status == 'Close'){
                $query->where('claim_status',2);
            }elseif($request->claim_status == 'Repuidated'){
                $query->where('claim_status',3);
            }
        }
        if($request->has('claim_type') && $request->claim_type != ''){
            if($request->claim_type == "OWN DAMAGE"){
                $query->where('claim_type',1);
            }elseif($request->claim_type == 'THIRD PARTY'){
                $query->where('claim_type',2);
            }
        }
        if($request->has('claimNo') && $request->claimNo != ''){
            $query->where('claim_no',$request->claimNo);
        }
        if($request->has('policyId') && $request->policyId != ''){
            $query->where('policy_id',$request->policyId);
        }
        if($request->has('sourcingAgentId') && !blank($request->sourcingAgentId)){
            $agent = $request->sourcingAgentId;
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('agent', $request->sourcingAgentId);
            });
        }
        if($request->has('customerId') && !blank($request->customerId)){
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('customer', $request->customerId);
            });
        }
        if($request->has('claimStartDate') && $request->has('claimEndDate') && !blank($request->claimStartDate) && !blank($claimEndDate)){
            $claimStartDate = date("Y-m-d", strtotime($request->claimStartDate));
            $claimEndDate = date("Y-m-d", strtotime($request->claimEndDate));
            $query->whereBetween('created_at',[$claimStartDate,$claimEndDate]);
        }
        if($request->has('companyId') && !blank($request->companyId)){
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('company', $request->companyId);
            });
        }
        $claims = $query->get();
        $array_push = array();
        if(!blank($claims)){
            foreach($claims as $claim){
                $array = array();
                $array['motorClaimId']                                              =  $claim->id;
                $motorInsurancePolicyBean = array();
                $motorInsurancePolicyBean['motorInsuarancePolicyId']                = $claim->policy->id;
                $motorInsurancePolicyBean['covernoteNo']                            = $claim->policy->covernote_no;
                $motorInsurancePolicyBean['policyNo']                               = $claim->policy->policy_no;
                $company = Company::where('id',$claim->policy->company)->first();
                $insuranceCompanyBean = array();
                if(!blank($company)){
                    $insuranceCompanyBean['insuranceCompanyId']                     = $company->id;
                    $insuranceCompanyBean['insuranceCompanyName']                   = $company->name;
                    $health_plans = Plan::where('company',$company->id)->get();
                    $plans = [];
                    foreach($health_plans as $plan){
                        $insurance_plan = array();
                        $insurance_plan['planId']                                   = $plan->id;
                        $insurance_plan['planName']                                 = $plan->name;
                        $insurance_plan['insuranceCompanyId']                       = $plan->parent;
                        $insurance_plan['insuranceCompanyName']                     = $plan->name;
                        array_push($plans,$insurance_plan);
                    }
                    $insuranceCompanyBean['healthPlanList']                         = $plans;
                }
                $customerBean = array();
                $customer = Customer::where('id',$claim->policy->customer)->first();
                if(!blank($customer)){
                    $customerBean['customerId']                                     = $customer->id;
                    $customerBean['customerName']                                   = $customer->name;
                    $customerBean['customerEmail']                                  = $customer->email;
                    $customerBean['customerMobile']                                 = $customer->phone;
                }
                $motorInsurancePolicyBean['insuranceCompanyBean']                   = $insuranceCompanyBean;
                $motorInsurancePolicyBean['customerBean']                           = $customerBean;
                $motorInsurancePolicyBean['vehicleMake']                            = ($claim->policy->vehicle_make != NULL)?$claim->policy->vehicle_make:"";
                $motorInsurancePolicyBean['vehicleModel']                           = ($claim->policy->vehicle_model != NULL)?$claim->policy->vehicle_model:"";
                $motorInsurancePolicyBean['vehicleRegistrationNo']                  = ($claim->policy->vehicle_registration_no != NULL)?$claim->policy->vehicle_registration_no:"";
                $motorInsurancePolicyBean['vehicleChassisNo']                       = ($claim->policy->vehicle_chassis_no != NULL)?$claim->policy->vehicle_chassis_no:"";
                $motorInsurancePolicyBean['yearOfManufacture']                      = ($claim->policy->year_of_manufacture != NULL)?$claim->policy->year_of_manufacture:"";
                $motorInsurancePolicyBean['vehicleEngine']                          = ($claim->policy->vehicle_engine != NULL)?$claim->policy->vehicle_engine:"";
                $motorInsurancePolicyBean['idvAmount']                              = ($claim->policy->idv_amount != NULL)?$claim->policy->idv_amount:"";
                $motorInsurancePolicyBean['grossPremiumAmount']                     = ($claim->policy->gross_premium_amount != NULL)?$claim->policy->gross_premium_amount:"";
                $motorInsurancePolicyBean['netPremiumAmount']                       = ($claim->policy->net_premium_amount != NULL)?$claim->policy->net_premium_amount:"";
                $motorInsurancePolicyBean['riskStartDate']                          = ($claim->policy->risk_start_date != NULL)? \DateTime::createFromFormat('d/m/Y',$claim->policy->risk_start_date):"";
                $motorInsurancePolicyBean['riskEndDate']                            = ($claim->policy->risk_end_date != NULL)? \DateTime::createFromFormat('d/m/Y',$claim->policy->risk_end_date):"";
                $motorInsurancePolicyBean['motorBusinessType']                      = ($claim->policy->business_type != NULL)?$claim->policy->business_type:"";
                $sourcingAgentBean                              = array();
                $agent = SourcingAgent::where('id',$claim->policy->agent)->first();
                if(!blank($agent)){
                    $sourcingAgentBean['sourcingAgentId']                           = $agent->id;
                    $sourcingAgentBean['sourcingAgentName']                         = $agent->name;
                    $sourcingAgentBean['sourcingAgentMobile']                       = $agent->phone;
                }
                $motorInsurancePolicyBean['sourcingAgentBean']                      = $sourcingAgentBean;
                $subCategoryBean    = array();
                $sub_category = Category::where('id',$claim->policy->sub_category)->first();
                if(!blank($sub_category)){
                    $subCategoryBean['subCategoryId']                               = $sub_category->id;
                    $subCategoryBean['subCategoryName']                             = $sub_category->name;
                    $subCategoryBean['categoryId']                                  = $sub_category->parent;
                    $category = Category::where('id',$sub_category->id)->first();
                    $subCategoryBean['categoryName']                                = $category->name;
                    $subCategoryBean['isRenewable']                                 = $category->renewable;
                }
                $motorInsurancePolicyBean['subCategoryBean']                        = $subCategoryBean;
                $motorInsurancePolicyBean['policyType']                             = "POLICY";
                $array['isPolicyIndividual']                                        = ($claim->policy->policy_type != NULL)?$claim->policy->policy_type:"";
                if($claim->policy->status == 2){
                    $cancel = true;
                }else{
                    $cancel = false;
                }
                $motorInsurancePolicyBean['isCancelled']                            = $cancel;
                $team_lead = array();
                $lead = User::where('id',$claim->policy->team_lead)->first();
                if(!blank($lead)){
                    $team_lead['displayName']                                       = $lead->name;
                }
                $motorInsurancePolicyBean['teamLead']                               =  $team_lead;
                $motorInsurancePolicyBean['pypNo']                                  = ($claim->policy->pyp_no != NULL)?$claim->policy->pyp_no:"";
                $motorInsurancePolicyBean['pypInsuranceCompany']                    = ($claim->policy->pyp_insurance_company != NULL)?$claim->policy->pyp_insurance_company:"";
                $motorInsurancePolicyBean['pypExpiryDate']                          = ($claim->policy->pyp_expiry_date != NULL)? \DateTime::createFromFormat('d/m/Y',$claim->policy->pyp_expiry_date):"";
                $manageBy = array();
                $managed_by = User::where('id',$claim->policy->managed_by)->first();
                if(!blank($managed_by)){
                    $manageBy['id']                                                 = $managed_by->id;
                    $manageBy['name']                                               = $managed_by->name;
                }
                $motorInsurancePolicyBean['managedBy']                              = $manageBy;
                $motorInsurancePolicyBean['ncb']                                    = ($claim->policy->ncb != NULL)?$claim->policy->ncb:"";
                $motorInsurancePolicyBean['cancelDate']                             = ($claim->policy->cancel_date != NULL)? \DateTime::createFromFormat('d/m/Y',$claim->policy->cancel_date):"";
                $motorInsurancePolicyBean['cancelReason']                           = ($claim->policy->cancel_reason != NULL)?$claim->policy->cancel_reason:"";
                $motorInsurancePolicyBean['remarks']                                = ($claim->policy->remarks != NULL)?$claim->policy->remarks:"";
                $motorInsurancePolicyBean['status']                                 = ($claim->policy->status != NULL)?$claim->policy->status:0;
                $motorInsurancePolicyBean['created_at']                             = ($claim->policy->created_at != NULL)? \DateTime::createFromFormat('d/m/Y',$claim->policy->created_at):"";
                $policy_documents = PolicyDocument::where('policy_id',$claim->policy->id)->get();
                $documents = array();
                if(!blank($policy_documents)){
                    $i = 0;
                    foreach($policy_documents as $document){
                        $doc['file']        =   url('/')."/public/policy_document/".$document->file;
                        $doc['name']        =   $document->file_name;
                        $doc['created_at']  =    \DateTime::createFromFormat('d/m/Y',$document->created_at);
                        $i++;
                        array_push($documents,$doc);
                    }
                }
                $motorInsurancePolicyBean['policyDocuments']                        = $documents;
                $policy_attachments = PolicyAttachment::where('policy_id',$claim->policy->id)->get();
                 $attachments = array();
                if(!blank($policy_attachments)){
                   
                    $i = 0;
                    foreach($policy_attachments as $attachment){
                        $atta['file']        =   url('/')."/public/policy_attachment/".$attachment->file;
                        $atta['created_at']  =    \DateTime::createFromFormat('d/m/Y',$attachment->created_at);
                        $i++;
                        array_push($attachments,$atta);
                    }
                    $motorInsurancePolicyBean['policyAttachments']                  = $attachments;
                }
                $policy_payments = PolicyPayment::where('policy_id',$claim->policy->id)->get();
                 $payments = array();
                if(!blank($policy_payments)){
                    $i = 0;
                    foreach($policy_payments as $payment){
                        $pay['id']                  =   $payment->id;
                        $pay['policy_id']           =   $payment->policy_id;
                        $pay['payment_type']        =   $payment->payment_type;
                        if($payment->payment_type == 2){
                            $pay['cheque_no']       =   $payment->cheque_no;  
                            $pay['cheque_date']     =   ($payment->cheque_date != '')? \DateTime::createFromFormat('d/m/Y',$payment->cheque_date):"";
                            $pay['bank_name']       =   $payment->bank_name; 
                        }
                        if($payment->payment_type == 3){
                            $pay['transaction_no']  = $payment->transaction_no;
                        }
                        $pay['payment_date']        =   ($payment->payment_date != '')? \DateTime::createFromFormat('d/m/Y',$payment->payment_date):"";
                        $pay['created_at']          =    \DateTime::createFromFormat('d/m/Y',$payment->created_at);
                        $i++;
                        array_push($payments,$pay);
                    }
                }
                $motorInsurancePolicyBean['policyPayments']                     = $payments;
                $array['motorInsurancePolicyBean']                              =  $motorInsurancePolicyBean;
                $array['claim_date']                                            = ($claim->claim_date != '')? \DateTime::createFromFormat('d/m/Y',$claim->claim_date):"";
                $array['claim_no']                                              = ($claim->claim_no != '')?$claim->claim_no:"";
                $array['contact_person']                                        = ($claim->contact_person != '')?$claim->contact_person:"";
                $array['contact_person_no']                                     = ($claim->contact_person_no != '')?$claim->contact_person_no:"";
                $array['surveyar_name']                                         = ($claim->surveyar_name != '')?$claim->surveyar_name:"";
                $array['surveyar_no']                                           = ($claim->surveyar_no != '')?$claim->surveyar_no:"";
                $array['surveyar_email']                                        = ($claim->surveyar_email)?$claim->surveyar_email:"";
                $array['repairing_location']                                    = ($claim->repairing_location)?$claim->repairing_location:"";
                $status = '';
                if($claim->claim_status == 1){
                    $status = 'Open';
                }elseif($claim->claim_status == 2){
                    $status = 'Close';
                }elseif($claim->claim_status == 3){
                    $status = 'Repuidated';
                }
                $claim_type = "";
                if($claim->claim_type == 1){
                    $claim_type = 'OWN DAMAGE';
                }else{
                    $claim_type = 'THIRD PARTY';
                }
                $array['claim_type']                                            = $claim_type;
                $array['claim_status']                                          =  $status;
                $array['status_date']                                           =  ($claim->status_date != '')? \DateTime::createFromFormat('d/m/Y',$claim->status_date):"";
                $array['paymentType']                                           =  $claim->payment_type;
                if($claim->payment_type == 2){
                    $array['chequeNo']                                          =  $claim->cheque_no;
                    $array['bankName']                                          =  $claim->bank_name;
                    $array['chequeDate']                                        =  ($claim->cheque_date != '')? \DateTime::createFromFormat('d/m/Y',$claim->cheque_date):"";
                }
                if($claim->payment_type == 3){
                    $array['transactionNo']                                     =  $claim->transaction_no;
                }
                $motorClaimAttachmentList = array();
                $claimattachments = ClaimAttachment::where('claim_id',$claim->id)->get();
                $attachments = array();
                if(!blank($claimattachments)){
                    $i = 0;
                    foreach($claimattachments as $attachment){
                        $atta['file']        =   url('/')."/public/claim_attachment/".$attachment->file;
                        $atta['created_at']  =    \DateTime::createFromFormat('d/m/Y',$attachment->created_at);
                        $i++;
                        array_push($attachments,$atta);
                    }
                }
                $array['motorClaimAttachmentList']                        =  $attachments;
                $claim_remarks = array();
                $remarks = ClaimRemark::where('claim_id',$claim->id)->get();
                if(!blank($remarks)){
                    foreach($remarks as $remark){
                        $created_by = User::where('id',$remark->created_by)->first();
                        $c_remark = array();
                        $c_remark['remark']         =  $remark->remark;
                        $c_remark['created_by']     = (!blank($created_by))?$created_by->name:'admin';
                        $c_remark['remark_date']    =  \DateTime::createFromFormat('d/m/Y',$remark->remark_date);
                        $c_remark['created_at']     =  \DateTime::createFromFormat('d/m/Y',$remark->created_at);
                        array_push($claim_remarks,$c_remark);
                    }
                }
                $array['claimRemarks']                                    = $claim_remarks;
                $array['remarks']                                         = ($claim->remarks != '')?$claim->remarks:"";
                $array['created_at']                                      =  \DateTime::createFromFormat('d/m/Y',$claim->created_at);
                array_push($array_push,$array);
            }
            return response()->json([
                'result'                    =>  200,
                'description'               =>  'Vehicle Claims',
                'status'                    =>  1,
                'motorClaimListJson'        =>  json_encode($array_push),
                'motorClaimListCount'       =>  count($claims),
                'message'                   =>  'Success!'
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'records not found.','error'=> 'Vehicle Policy Claims Not Found.'],200);
        } 
    }
    public function getHealthClaimList(Request $request){
        $query = Claim::orderBy('id','DESC')->with('policy');
        $query->whereHas('policy', function($query1) use ($request){
            $query1->where('insurance_type', 2);
        });
        if($request->has('id') && $request->id != ''){
            $query->where('id',$request->id);
        }
        if($request->has('claim_status') && $request->claim_status != ''){
            if($request->claim_status == "Open"){
                $query->where('claim_status',1);
            }elseif($request->claim_status == 'Close'){
                $query->where('claim_status',2);
            }elseif($request->claim_status == 'Repuidated'){
                $query->where('claim_status',3);
            }
        }
        if($request->has('claim_type') && $request->claim_type != ''){
            if($request->claim_type == "OWN DAMAGE"){
                $query->where('claim_type',1);
            }elseif($request->claim_type == 'THIRD PARTY'){
                $query->where('claim_type',2);
            }
        }
        if($request->has('claimNo') && $request->claimNo != ''){
            $query->where('claim_no',$request->claimNo);
        }
        if($request->has('policyId') && $request->policyId != ''){
            $query->where('policy_id',$request->policyId);
        }
        if($request->has('sourcingAgentId') && !blank($request->sourcingAgentId)){
            $agent = $request->sourcingAgentId;
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('agent', $request->sourcingAgentId);
            });
        }
        if($request->has('customerId') && !blank($request->customerId)){
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('customer', $request->customerId);
            });
        }
        if($request->has('claimStartDate') && $request->has('claimEndDate') && !blank($request->claimStartDate) && !blank($claimEndDate)){
            $claimStartDate = date("Y-m-d", strtotime($request->claimStartDate));
            $claimEndDate = date("Y-m-d", strtotime($request->claimEndDate));
            $query->whereBetween('created_at',[$claimStartDate,$claimEndDate]);
        }
        if($request->has('companyId') && !blank($request->companyId)){
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('company', $request->companyId);
            });
        }
        $claims = $query->get();
        $array_push = array();
        if(!blank($claims)){
            foreach($claims as $claim){
                $array = array();
                $array['healthClaimId']                                             =  $claim->id;
                $healthInsurancePolicyBean = array();
                $healthInsurancePolicyBean['healthInsuarancePolicyId']               = $claim->policy->id;
                $healthInsurancePolicyBean['covernoteNo']                            = $claim->policy->covernote_no;
                $healthInsurancePolicyBean['policyNo']                               = $claim->policy->policy_no;
                $company = Company::where('id',$claim->policy->company)->first();
                $insuranceCompanyBean = array();
                if(!blank($company)){
                    $insuranceCompanyBean['insuranceCompanyId']                     = $company->id;
                    $insuranceCompanyBean['insuranceCompanyName']                   = $company->name;
                    $health_plans = Plan::where('company',$company->id)->get();
                    foreach($health_plans as $plan){
                        $insurance_plan = array();
                        $insurance_plan['planId']                                   = $plan->id;
                        $insurance_plan['planName']                                 = $plan->name;
                        $insurance_plan['insuranceCompanyId']                       = $plan->parent;
                        $insurance_plan['insuranceCompanyName']                     = $plan->name;
                    }
                    $insuranceCompanyBean['healthPlanList']                         = $insurance_plan;
                }
                $customerBean = array();
                $customer = Customer::where('id',$claim->policy->customer)->first();
                if(!blank($customer)){
                    $customerBean['customerId']                                     = $customer->id;
                    $customerBean['customerName']                                   = $customer->name;
                    $customerBean['customerEmail']                                  = $customer->email;
                    $customerBean['customerMobile']                                 = $customer->phone;
                }
                $healthInsurancePolicyBean['insuranceCompanyBean']                   = $insuranceCompanyBean;
                $healthInsurancePolicyBean['customerBean']                           = $customerBean;
                $healthInsurancePolicyBean['idvAmount']                              = ($claim->policy->idv_amount != NULL)?$claim->policy->idv_amount:"";
                $healthInsurancePolicyBean['sumInsuredAmount']                       = ($claim->policy->sum_insured_amount != NULL)?$claim->policy->sum_insured_amount:"";
                $healthInsurancePolicyBean['grossPremiumAmount']                     = ($claim->policy->gross_premium_amount != NULL)?$claim->policy->gross_premium_amount:"";
                $healthInsurancePolicyBean['netPremiumAmount']                       = ($claim->policy->net_premium_amount != NULL)?$claim->policy->net_premium_amount:"";
                $healthInsurancePolicyBean['riskStartDate']                          = ($claim->policy->risk_start_date != NULL)? \DateTime::createFromFormat('d/m/Y',$claim->policy->risk_start_date):"";
                $healthInsurancePolicyBean['riskEndDate']                            = ($claim->policy->risk_end_date != NULL)? \DateTime::createFromFormat('d/m/Y',$claim->policy->risk_end_date):"";
                $healthInsurancePolicyBean['healthBusinessType']                      = ($claim->policy->business_type != NULL)?$claim->policy->business_type:"";
                $sourcingAgentBean                              = array();
                $agent = SourcingAgent::where('id',$claim->policy->agent)->first();
                if(!blank($agent)){
                    $sourcingAgentBean['sourcingAgentId']                           = $agent->id;
                    $sourcingAgentBean['sourcingAgentName']                         = $agent->name;
                    $sourcingAgentBean['sourcingAgentMobile']                       = $agent->phone;
                }
                $healthInsurancePolicyBean['sourcingAgentBean']                      = $sourcingAgentBean;
                $healthInsurancePolicyBean['policyType']                             = "POLICY";
                $array['isPolicyIndividual']                                        = ($claim->policy->policy_type != NULL)?$claim->policy->policy_type:"";
                if($claim->policy->status == 2){
                    $cancel = true;
                }else{
                    $cancel = false;
                }
                $healthInsurancePolicyBean['isCancelled']                            = $cancel;
                $team_lead = array();
                $lead = User::where('id',$claim->policy->team_lead)->first();
                if(!blank($lead)){
                    $team_lead['displayName']                                       = $lead->name;
                }
                $healthInsurancePolicyBean['teamLead']                               =  $team_lead;
                $healthInsurancePolicyBean['pypNo']                                  = ($claim->policy->pyp_no != NULL)?$claim->policy->pyp_no:"";
                $healthInsurancePolicyBean['pypInsuranceCompany']                    = ($claim->policy->pyp_insurance_company != NULL)?$claim->policy->pyp_insurance_company:"";
                $healthInsurancePolicyBean['pypExpiryDate']                          = ($claim->policy->pyp_expiry_date != NULL)? \DateTime::createFromFormat('d/m/Y',$claim->policy->pyp_expiry_date):"";
                $manageBy = array();
                $managed_by = User::where('id',$claim->policy->managed_by)->first();
                if(!blank($managed_by)){
                    $manageBy['id']                                                 = $managed_by->id;
                    $manageBy['name']                                               = $managed_by->name;
                }
                $healthInsurancePolicyBean['managedBy']                              = $manageBy;
                $healthInsurancePolicyBean['ncb']                                    = ($claim->policy->ncb != NULL)?$claim->policy->ncb:"";
                $healthInsurancePolicyBean['cancelDate']                             = ($claim->policy->cancel_date != NULL)? \DateTime::createFromFormat('d/m/Y',$claim->policy->cancel_date):"";
                $healthInsurancePolicyBean['cancelReason']                           = ($claim->policy->cancel_reason != NULL)?$claim->policy->cancel_reason:"";
                $healthInsurancePolicyBean['remarks']                                = (isset($endorsement->policy->remarks) != NULL)?$claim->policy->remarks:"";
                $healthInsurancePolicyBean['status']                                 = ($claim->policy->status != NULL)?$claim->policy->status:0;
                $healthInsurancePolicyBean['created_at']                             = ($claim->policy->created_at != NULL)? \DateTime::createFromFormat('d/m/Y',$claim->policy->created_at):"";
                $policy_documents = PolicyDocument::where('policy_id',$claim->policy->id)->get();
                 $documents = array();
                 if(!blank($policy_documents)){
                   
                    $i = 0;
                    foreach($policy_documents as $document){
                        $doc['file']        =   url('/')."/public/policy_document/".$document->file;
                        $doc['name']        =   $document->file_name;
                        $doc['created_at']  =    \DateTime::createFromFormat('d/m/Y',$document->created_at);
                        $i++;
                        array_push($documents,$doc);
                    }
                }
                $healthInsurancePolicyBean['policyDocuments'] = $documents;
                $policy_attachments = PolicyAttachment::where('policy_id',$claim->policy->id)->get();
                $attachments = array();
                if(!blank($policy_attachments)){
                    $i = 0;
                    foreach($policy_attachments as $attachment){
                        $atta['file']        =   url('/')."/public/policy_attachment/".$attachment->file;
                        $atta['created_at']  =    \DateTime::createFromFormat('d/m/Y',$attachment->created_at);
                        $i++;
                        array_push($attachments,$atta);
                    }
                    $healthInsurancePolicyBean['policyAttachments']                  = $attachments;
                }
                $policy_payments = PolicyPayment::where('policy_id',$claim->policy->id)->get();
                 $payments = array();
                 if(!blank($policy_payments)){
                   
                    $i = 0;
                    foreach($policy_payments as $payment){
                        $pay['id']                  =   $payment->id;
                        $pay['policy_id']           =   $payment->policy_id;
                        $pay['payment_type']        =   $payment->payment_type;
                        if($payment->payment_type == 2){
                            $pay['cheque_no']       =   $payment->cheque_no;  
                            $pay['cheque_date']     =    \DateTime::createFromFormat('d/m/Y',$payment->cheque_date);
                            $pay['bank_name']       =   $payment->bank_name; 
                        }
                        if($payment->payment_type == 3){
                            $pay['transaction_no']  = $payment->transaction_no;
                        }
                        $pay['payment_date']        =   ($payment->payment_date != '')? \DateTime::createFromFormat('d/m/Y',$payment->payment_date):"";
                        $pay['created_at']          =   ($payment->creatde_at != '')?$payment->created_at:"";
                        $i++;
                        array_push($payments,$pay);
                    }
                }
                $healthInsurancePolicyBean['policyPayments']                    = $payments;
                $array['healthInsurancePolicyBean']                             =  $healthInsurancePolicyBean;
                $array['claim_date']                                            =   \DateTime::createFromFormat('d/m/Y',$claim->claim_date);
                $array['claim_no']                                              = $claim->claim_no;
                $array['contact_person']                                        = $claim->contact_person;
                $array['contact_person_no']                                     = $claim->contact_person_no;
                $array['surveyar_name']                                         = $claim->surveyar_name;
                $array['surveyar_no']                                           = $claim->surveyar_no;
                $array['surveyar_email']                                        = $claim->surveyar_email;
                $array['repairing_location']                                    = $claim->repairing_location;
                $status = '';
                if($claim->claim_status == 1){
                    $status = 'Open';
                }elseif($claim->claim_status == 2){
                    $status = 'Close';
                }elseif($claim->claim_status == 3){
                    $status = 'Repuidated';
                }
                $array['claim_status']                                          =  $status;
                $claim_type = "";
                if($claim->claim_type == 1){
                    $claim_type = 'OWN DAMAGE';
                }else{
                    $claim_type = 'THIRD PARTY';
                }
                $array['claim_type']                                            = $claim_type;
                $array['status_date']                                           =  ($claim->status_date != '')? \DateTime::createFromFormat('d/m/Y',$claim->status_date):"";
                $array['paymentType']                                           =  $claim->payment_type;
                if($claim->payment_type == 2){
                    $array['chequeNo']                                          =  $claim->cheque_no;
                    $array['bankName']                                          =  $claim->bank_name;
                    $array['chequeDate']                                        =  ($claim->cheque_date != '')? \DateTime::createFromFormat('d/m/Y',$claim->cheque_date):"";
                }
                if($claim->payment_type == 3){
                    $array['transactionNo']                                     =  $claim->transaction_no;
                }
                $healthClaimAttachmentList = array();
                $claimattachments = ClaimAttachment::where('claim_id',$claim->id)->get();
                $attachments = array();
                if(!blank($claimattachments)){
                    $i = 0;
                    foreach($claimattachments as $attachment){
                        $atta['file']        =   url('/')."/public/claim_attachment/".$attachment->file;
                        $atta['created_at']  =    \DateTime::createFromFormat('d/m/Y',$attachment->created_at);
                        $i++;
                        array_push($attachments,$atta);
                    }
                }
                $array['healthClaimAttachmentList']                             = $attachments;
                $array['remarks']                                               = $claim->remarks;
                $array['created_at']                                            =  \DateTime::createFromFormat('d/m/Y',$claim->created_at);
                array_push($array_push,$array);
            }
            return response()->json([
                'result'                    =>  200,
                'description'               =>  'Health Claims',
                'status'                    =>  1,
                'healthClaimListJson'       =>  json_encode($array_push),
                'healthClaimListCount'      =>  count($claims),
                'message'                   =>  'Success!'
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'records not found.','error'=> 'Health Policy Claims Not Found.'],200);
        } 
    }
    public function addMotorClaim(Request $request){
        $validator = Validator::make($request->all(), [
            'policy_id'                         => 'required',
            'claim_date'                        => 'required',
            'claimNo'                           => 'required',
            'contact_person'                    => 'required',
            'contact_person_no'                 => 'required',
            'claim_type'                        => 'required|not_in:0',
            'claim_status'                      => 'required|not_in:0',
            'paymentType'                       => 'required',
            'transaction_no'                    => 'required_if:paymentType,3',
            'cheque_no'                         => 'required_if:paymentType,2',
            'cheque_date'                       => 'required_if:paymentType,2',
            'bank_name'                         => 'required_if:paymentType,2',
        ]);
        if($validator->fails()){
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $motorClaim = new Claim();
            $motorClaim->policy_id          = $request->policy_id;
            $motorClaim->claim_date         = date('Y-m-d',strtotime($request->claim_date));
            $motorClaim->claim_no           = ($request->claimNo != '')?$request->claimNo:"";
            $motorClaim->contact_person     = ($request->contact_person != '')?$request->contact_person:"";
            $motorClaim->contact_person_no  = ($request->contact_person_no != '')?$request->contact_person_no:"";
            $motorClaim->surveyar_name      = ($request->surveyar_name != '')?$request->surveyar_name:"";
            $motorClaim->surveyar_no        = ($request->surveyar_no != '')?$request->surveyar_no:"";
            $motorClaim->surveyar_email     = ($request->surveyar_email != '')?$request->surveyar_email:"";
            $motorClaim->repairing_location = ($request->repairing_location != '')?$request->repairing_location:"";
            $motorClaim->claim_type         = ($request->claim_type != '')?$request->claim_type:"";
            $motorClaim->claim_status       = ($request->claim_status != '')?$request->claim_status:"";
            $motorClaim->status_text        = ($request->status_text != '')?$request->status_text:"";
            $motorClaim->status_date        = ($request->status_date != '')?date('Y-m-d',strtotime($request->status_date)):"";
            $motorClaim->payment_type       = ($request->paymentType != '')?$request->paymentType:"";
            if($request->has('cheque_no')){
                $motorClaim->cheque_no          = ($request->cheque_no != '')?$request->cheque_no:"";
            }
            if($request->has('cheque_date')){
                $motorClaim->cheque_date        = ($request->cheque_date != '')?$request->cheque_date:"";
            }
            if($request->has('bank_name')){
                $motorClaim->bank_name          = ($request->bank_name != '')?$request->bank_name:"";
            }
            if($request->has('transaction_no')){
                $motorClaim->transaction_no     = ($request->transaction_no != '')?$request->transaction_no:"";
            }
            $motorClaim->remarks                = ($request->remarks != '')?$request->remarks:"";
            $motorClaim->save();
            if($motorClaim){
                if($request->has('remark') && $request->remark != ''){
                    $remark = new ClaimRemark();
                    $remark->claim_id       = $motorClaim->id;
                    $remark->remark         = $request->remark;
                    $remark->remark_date    = Carbon::now();
                    $remark->created_by     = $request->user_id;
                    $remark->save();
                }
                if($request->has('claimAttachment')){
                    foreach($request->claimAttachment as $file){
                        if($file && $file != null){
                            $image_file         =   $file;
                            $destinationPath1   =   'public/claim_attachment/';
                            $rand1              =   rand(1,100);
                            $docImage1          =   date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment=$docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new ClaimAttachment();
                        $attachment->claim_id   = $motorClaim->id;
                        $attachment->policy_id  = $request->policy_id;
                        $attachment->file       = $img_attachment;
                        $attachment->save();
                    }
                }
                return response()->json([
                    'result'        =>  200,
                    'claim_id'      => $motorClaim->id,
                    'status'        =>  1,
                    'description'   =>  'Save successfully.',
                    'message'       =>  'Success!.'
                ],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }
    }
    public function addHealthClaim(Request $request){
        $validator = Validator::make($request->all(), [
            'policy_id'                         => 'required',
            'claim_date'                        => 'required',
            'claimNo'                           => 'required',
            'contact_person'                    => 'required',
            'contact_person_no'                 => 'required',
            'claim_type'                        => 'required|not_in:0',
            'claim_status'                      => 'required|not_in:0',
            'paymentType'                       => 'required',
            'transaction_no'                    => 'required_if:paymentType,3',
            'cheque_no'                         => 'required_if:paymentType,2',
            'cheque_date'                       => 'required_if:paymentType,2',
            'bank_name'                         => 'required_if:paymentType,2',
        ]);
        if($validator->fails()){
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $healthClaim = new Claim();
            $healthClaim->policy_id          = $request->policy_id;
            $healthClaim->claim_date         = date('Y-m-d',strtotime($request->claim_date));
            $healthClaim->claim_no           = ($request->claimNo != '')?$request->claimNo:"";
            $healthClaim->contact_person     = ($request->contact_person != '')?$request->contact_person:"";
            $healthClaim->contact_person_no  = ($request->contact_person_no != '')?$request->contact_person_no:"";
            $healthClaim->surveyar_name      = ($request->surveyar_name != '')?$request->surveyar_name:"";
            $healthClaim->surveyar_no        = ($request->surveyar_no != '')?$request->surveyar_no:"";
            $healthClaim->surveyar_email     = ($request->surveyar_email != '')?$request->surveyar_email:"";
            $healthClaim->repairing_location = ($request->repairing_location != '')?$request->repairing_location:"";
            $healthClaim->claim_type         = ($request->claim_type != '')?$request->claim_type:"";
            $healthClaim->claim_status       = ($request->claim_status != '')?$request->claim_status:"";
            $healthClaim->status_text        = ($request->status_text != '')?$request->status_text:"";
            $healthClaim->status_date        = ($request->status_date != '')?date('Y-m-d',strtotime($request->status_date)):"";
            $healthClaim->payment_type       = ($request->paymentType != '')?$request->paymentType:"";
            if($request->has('cheque_no')){
                $healthClaim->cheque_no          = ($request->cheque_no != '')?$request->cheque_no:"";
            }
            if($request->has('cheque_date')){
                $healthClaim->cheque_date        = ($request->cheque_date != '')?$request->cheque_date:"";
            }
            if($request->has('bank_name')){
                $healthClaim->bank_name          = ($request->bank_name != '')?$request->bank_name:"";
            }
            if($request->has('transaction_no')){
                $healthClaim->transaction_no     = ($request->transaction_no != '')?$request->transaction_no:"";
            }
            $healthClaim->remarks                = ($request->remarks != '')?$request->remarks:"";
            $healthClaim->save();
            if($healthClaim){
                if($request->has('remark') && $request->remark != ''){
                    $remark = new ClaimRemark();
                    $remark->claim_id       = $healthClaim->id;
                    $remark->remark         = $request->remark;
                    $remark->remark_date    = Carbon::now();
                    $remark->created_by     = $request->user_id;
                    $remark->save();
                }
                if($request->has('claimAttachment')){
                    foreach($request->claimAttachment as $file){
                        if($file && $file != null){
                            $image_file         =   $file;
                            $destinationPath1   =   'public/claim_attachment/';
                            $rand1              =   rand(1,100);
                            $docImage1          =   date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment     =   $docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new ClaimAttachment();
                        $attachment->claim_id   = $healthClaim->id;
                        $attachment->policy_id  = $request->policy_id;
                        $attachment->file       = $img_attachment;
                        $attachment->save();
                    }
                }
                return response()->json([
                    'result'        =>  200,
                    'claim_id'      => $healthClaim->id,
                    'status'        =>  1,
                    'description'   =>  'Save successfully.',
                    'message'       =>  'Success!.'
                ],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }
    }
    public function editMotorClaim(Request $request){
        $validator = Validator::make($request->all(), [
            'id'                                => 'required',
            // 'policy_id'                         => 'required',
            'claim_date'                        => 'required',
            'claimNo'                           => 'required',
            'contact_person'                    => 'required',
            'contact_person_no'                 => 'required',
            'claim_type'                        => 'required|not_in:0',
            'claim_status'                      => 'required|not_in:0',
            'paymentType'                       => 'required',
            'transaction_no'                    => 'required_if:paymentType,3',
            'cheque_no'                         => 'required_if:paymentType,2',
            'cheque_date'                       => 'required_if:paymentType,2',
            'bank_name'                         => 'required_if:paymentType,2',
        ]);
        if($validator->fails()){
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $motorClaim = Claim::where('id',$request->id)->first();
            // $motorClaim->policy_id          = $request->policy_id;
            $motorClaim->claim_date         = date('Y-m-d',strtotime($request->claim_date));
            $motorClaim->claim_no           = ($request->claimNo != '')?$request->claimNo:"";
            $motorClaim->contact_person     = ($request->contact_person != '')?$request->contact_person:"";
            $motorClaim->contact_person_no  = ($request->contact_person_no != '')?$request->contact_person_no:"";
            $motorClaim->surveyar_name      = ($request->surveyar_name != '')?$request->surveyar_name:"";
            $motorClaim->surveyar_no        = ($request->surveyar_no != '')?$request->surveyar_no:"";
            $motorClaim->surveyar_email     = ($request->surveyar_email != '')?$request->surveyar_email:"";
            $motorClaim->repairing_location = ($request->repairing_location != '')?$request->repairing_location:"";
            $motorClaim->claim_type         = ($request->claim_type != '')?$request->claim_type:"";
            $motorClaim->claim_status       = ($request->claim_status != '')?$request->claim_status:"";
            $motorClaim->status_text        = ($request->status_text != '')?$request->status_text:"";
            $motorClaim->status_date        = ($request->status_date != '')?date('Y-m-d',strtotime($request->status_date)):"";
            $motorClaim->payment_type       = ($request->paymentType != '')?$request->paymentType:"";
            if($request->has('cheque_no')){
                $motorClaim->cheque_no          = ($request->cheque_no != '')?$request->cheque_no:"";
            }
            if($request->has('cheque_date')){
                $motorClaim->cheque_date        = ($request->cheque_date != '')?$request->cheque_date:"";
            }
            if($request->has('bank_name')){
                $motorClaim->bank_name          = ($request->bank_name != '')?$request->bank_name:"";
            }
            if($request->has('transaction_no')){
                $motorClaim->transaction_no     = ($request->transaction_no != '')?$request->transaction_no:"";
            }
            $motorClaim->remarks                = ($request->remarks != '')?$request->remarks:"";
            $motorClaim->save();
            if($motorClaim){
                if($request->has('claimAttachment')){
                    foreach($request->claimAttachment as $file){
                        if($file && $file != null){
                            $image_file         =   $file;
                            $destinationPath1   =   'public/claim_attachment/';
                            $rand1              =   rand(1,100);
                            $docImage1          =   date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment=$docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new ClaimAttachment();
                        $attachment->claim_id   = $motorClaim->id;
                        $attachment->policy_id  = $request->policy_id;
                        $attachment->file       = $img_attachment;
                        $attachment->save();
                    }
                }
                return response()->json([
                    'result'        =>  200,
                    'status'        =>  1,
                    'description'   =>  'Updated successfully.',
                    'message'       =>  'Success!.'
                ],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }
    }
    public function editHealthClaim(Request $request){
        $validator = Validator::make($request->all(), [
            'id'                                => 'required',
            // 'policy_id'                         => 'required',
            'claim_date'                        => 'required',
            'claimNo'                           => 'required',
            'contact_person'                    => 'required',
            'contact_person_no'                 => 'required',
            'claim_type'                        => 'required|not_in:0',
            'claim_status'                      => 'required|not_in:0',
            'paymentType'                       => 'required',
            'transaction_no'                    => 'required_if:paymentType,3',
            'cheque_no'                         => 'required_if:paymentType,2',
            'cheque_date'                       => 'required_if:paymentType,2',
            'bank_name'                         => 'required_if:paymentType,2',
        ]);
        if($validator->fails()){
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $healthClaim = Claim::where('id',$request->id)->first();
            // $healthClaim->policy_id          = $request->policy_id;
            $healthClaim->claim_date         = date('Y-m-d',strtotime($request->claim_date));
            $healthClaim->claim_no           = ($request->claimNo != '')?$request->claimNo:"";
            $healthClaim->contact_person     = ($request->contact_person != '')?$request->contact_person:"";
            $healthClaim->contact_person_no  = ($request->contact_person_no != '')?$request->contact_person_no:"";
            $healthClaim->surveyar_name      = ($request->surveyar_name != '')?$request->surveyar_name:"";
            $healthClaim->surveyar_no        = ($request->surveyar_no != '')?$request->surveyar_no:"";
            $healthClaim->surveyar_email     = ($request->surveyar_email != '')?$request->surveyar_email:"";
            $healthClaim->repairing_location = ($request->repairing_location != '')?$request->repairing_location:"";
            $healthClaim->claim_type         = ($request->claim_type != '')?$request->claim_type:"";
            $healthClaim->claim_status       = ($request->claim_status != '')?$request->claim_status:"";
            $healthClaim->status_text        = ($request->status_text != '')?$request->status_text:"";
            $healthClaim->status_date        = ($request->status_date != '')?date('Y-m-d',strtotime($request->status_date)):"";
            $healthClaim->payment_type       = ($request->paymentType != '')?$request->paymentType:"";
            if($request->has('cheque_no')){
                $healthClaim->cheque_no          = ($request->cheque_no != '')?$request->cheque_no:"";
            }
            if($request->has('cheque_date')){
                $healthClaim->cheque_date        = ($request->cheque_date != '')?$request->cheque_date:"";
            }
            if($request->has('bank_name')){
                $healthClaim->bank_name          = ($request->bank_name != '')?$request->bank_name:"";
            }
            if($request->has('transaction_no')){
                $healthClaim->transaction_no     = ($request->transaction_no != '')?$request->transaction_no:"";
            }
            $healthClaim->remarks                = ($request->remarks != '')?$request->remarks:"";
            $healthClaim->save();
            if($healthClaim){
                if($request->has('remark') && $request->remark != ''){
                    $remark = new ClaimRemark();
                    $remark->claim_id       = $healthClaim->id;
                    $remark->remark         = $request->remark;
                    $remark->remark_date    = Carbon::now();
                    $remark->created_by     = $request->user_id;
                    $remark->save();
                }
                if($request->has('claimAttachment')){
                    foreach($request->claimAttachment as $file){
                        if($file && $file != null){
                            $image_file         =   $file;
                            $destinationPath1   =   'public/claim_attachment/';
                            $rand1              =   rand(1,100);
                            $docImage1          =   date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment     =   $docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new ClaimAttachment();
                        $attachment->claim_id   = $healthClaim->id;
                        $attachment->policy_id  = $request->policy_id;
                        $attachment->file       = $img_attachment;
                        $attachment->save();
                    }
                }
                return response()->json([
                    'result'        =>  200,
                    'status'        =>  1,
                    'description'   =>  'Updated successfully.',
                    'message'       =>  'Success!.'
                ],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }
    }
    public function deleteMotorClaim(Request $Request, $id = NULL){
        if(!blank($id)){
            $clm = Claim::where('id',$id)->first();
            if(!blank($clm)){
                $attachment = ClaimAttachment::where('claim_id',$id)->delete();
                $remarks = ClaimRemark::where('claim_id',$id)->delete();
                $claim = Claim::where('id',$id)->delete();
                return response()->json(['result'=>200,'status'=>1,'description'=>'Deleted successfully.','message'=> 'Success!.'],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Claim Not Found.','message'=> 'not found.'],200);
            }
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
        }
    }
    public function deleteHealthClaim(Request $Request, $id = NULL){
        if(!blank($id)){
            $clm = Claim::where('id',$id)->first();
            if(!blank($clm)){
                $attachment = ClaimAttachment::where('claim_id',$id)->delete();
                $remarks = ClaimRemark::where('claim_id',$id)->delete();
                $claim = Claim::where('id',$id)->delete();
                return response()->json(['result'=>200,'status'=>1,'description'=>'Deleted successfully.','message'=> 'Success!.'],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Claim Not Found.','message'=> 'not found.'],200);
            }
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
        }
    }
    public function getClaimRemarks(Request $request){
        $query = ClaimRemark::orderBy('id','DESC');
        if($request->has('id') && $request->id != ''){
            $query->where('id',$request->id);
        }
        if($request->has('claimId') && $request->claimId != ''){
            $query->where('claim_id',$request->claimId);
        }
        $claim_remarks = $query->get();
        $remarks = array();
        if(!blank($claim_remarks)){
            foreach($claim_remarks as $c_remarks){
                $remark = array();
                $created_by = User::where('id',$c_remarks->created_by)->first();
                $remark['remarkId']         = $c_remarks->id;
                $remark['claimId']          = $c_remarks->claim_id;
                $remark['remark']           = ($c_remarks->remark)??"";
                $remark['created_by']       = ($created_by->name)??"admin";
                $remark['remark_date']      = (\DateTime::createFromFormat('d/m/Y',$c_remarks->remark_date))??"";
                $remark['created_at']       = (\DateTime::createFromFormat('d/m/Y',$c_remarks->created_at))??"";
                array_push($remarks,$remark);
            }
            return response()->json([
                'result'                    =>  200,
                'description'               =>  'Claim Remarks',
                'status'                    =>  1,
                'ClaimRemarksListJson'      =>  json_encode($remarks),
                'ClaimRemarksListCount'     =>  count($claim_remarks),
                'message'                   =>  'Success!'
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'records not found.','error'=> 'Claim Remarks Not Found.'],200);
        }

    }
    public function addClaimRemark(Request $request){
        $validator = Validator::make($request->all(), [
            'claim_id'                      => 'required',
            'remark'                        => 'required',
            'user_id'                       => 'required',
            'remark_date'                   => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $claim_id = $request->claim_id;
            $remark_data = $request->remark;
            $remark_date = $request->remark_date;
    
            $remark = new ClaimRemark();
            $remark->claim_id       = $claim_id;
            $remark->remark         = $remark_data;
            $remark->remark_date    = date('Y-m-d',strtotime($remark_date));
            $remark->created_by     = $request->user_id;
            $remark->save();
            if($remark){
                return response()->json([
                    'result'                    =>  200,
                    'description'               =>  'Claim Remark Added Successfully',
                    'status'                    =>  1,
                    'message'                   =>  'Success!'
                ],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'records not found.','error'=> 'Claim Remarks Not Found.'],200);
            }
        }
    }
}
