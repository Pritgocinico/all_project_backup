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
use App\Models\Policy;
use App\Models\PolicyParameter;
use App\Models\PolicyAttachment;
use App\Models\PolicyDocument;
use App\Models\PolicyPayment;
use App\Models\Endorsement;
use App\Models\EndorsementAttachment;
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

class EndorsementController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function getMotorEndorsementList(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $currentUser = User::where('id',$accessToken->user_id)->first();
        $query = Endorsement::orderBy('id','DESC')->with('policy');
        $query->whereHas('policy', function($query1) use ($request){
            $query1->where('insurance_type', 1);
        });
        if($request->has('id')){
            $query->where('id',$request->id);
        }
        if($request->has('policyId')){
            $query->where('policy_id',$request->policyId);
        }
        if($request->has('sourcingAgentId')){
            $agent = $request->sourcingAgentId;
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('agent', $request->sourcingAgentId);
            });
        }
        if($request->has('customerId')){
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('customer', $request->customerId);
            });
        }
        if($request->has('endorsementStartDate') && $request->has('endorsementEndDate')){
            $endorsementStartDate =  \DateTime::createFromFormat('d/m/Y',$request->endorsementStartDate);
            $endorsementEndDate = \DateTime::createFromFormat('d/m/Y',$request->endorsementEndDate);
            $query->whereBetween('created_at',[$endorsementStartDate,$endorsementEndDate]);
        }
        if($request->has('companyId')){
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('company', $request->companyId);
            });
        }
        if($request->has('planId')){
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('health_plan', $request->planId);
            });
        }
        if($request->has('endorsement_details') && $request->endorsement_details != ''){
            $endorsement->where('details','LIKE','%'.$request->endorsement_details.'%');
        }
        if($currentUser->role == 1){
            $endorsements = $query->get();
        }elseif($currentUser->role == 3){
            $endorsement->whereHas('policy', function($query1) use ($request){
                $query1->where('agent', $currentUser->id);
            });
            $endorsements = $endorsement->get();
        }
        $array_push = array();
        if(!blank($endorsements)){
            foreach($endorsements as $endorsement){
                $array = array();
                $array['motorEndorsementId']                                        =  $endorsement->id;
                $motorInsurancePolicyBean = array();
                $motorInsurancePolicyBean['motorInsuarancePolicyId']                = $endorsement->policy->id;
                $motorInsurancePolicyBean['covernoteNo']                            = $endorsement->policy->covernote_no;
                $motorInsurancePolicyBean['policyNo']                               = $endorsement->policy->policy_no;
                $company = Company::where('id',$endorsement->policy->company)->first();
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
                $customer = Customer::where('id',$endorsement->policy->customer)->first();
                if(!blank($customer)){
                    $customerBean['customerId']                                     = $customer->id;
                    $customerBean['customerName']                                   = $customer->name;
                    $customerBean['customerEmail']                                  = $customer->email;
                    $customerBean['customerMobile']                                 = $customer->phone;
                }
                $motorInsurancePolicyBean['insuranceCompanyBean']                   = $insuranceCompanyBean;
                $motorInsurancePolicyBean['customerBean']                           = $customerBean;
                $motorInsurancePolicyBean['vehicleMake']                            = ($endorsement->policy->vehicle_make != NULL)?$endorsement->policy->vehicle_make:"";
                $motorInsurancePolicyBean['vehicleModel']                           = ($endorsement->policy->vehicle_model != NULL)?$endorsement->policy->vehicle_model:"";
                $motorInsurancePolicyBean['vehicleRegistrationNo']                  = ($endorsement->policy->vehicle_registration_no != NULL)?$endorsement->policy->vehicle_registration_no:"";
                $motorInsurancePolicyBean['vehicleChassisNo']                       = ($endorsement->policy->vehicle_chassis_no != NULL)?$endorsement->policy->vehicle_chassis_no:"";
                $motorInsurancePolicyBean['yearOfManufacture']                      = ($endorsement->policy->year_of_manufacture != NULL)?$endorsement->policy->year_of_manufacture:"";
                $motorInsurancePolicyBean['vehicleEngine']                          = ($endorsement->policy->vehicle_engine != NULL)?$endorsement->policy->vehicle_engine:"";
                $motorInsurancePolicyBean['idvAmount']                              = ($endorsement->policy->idv_amount != NULL)?$endorsement->policy->idv_amount:"";
                $motorInsurancePolicyBean['grossPremiumAmount']                     = ($endorsement->policy->gross_premium_amount != NULL)?$endorsement->policy->gross_premium_amount:"";
                $motorInsurancePolicyBean['netPremiumAmount']                       = ($endorsement->policy->net_premium_amount != NULL)?$endorsement->policy->net_premium_amount:"";
                $motorInsurancePolicyBean['riskStartDate']                          = ($endorsement->policy->risk_start_date != NULL)?$endorsement->policy->risk_start_date:"";
                $motorInsurancePolicyBean['riskEndDate']                            = ($endorsement->policy->risk_end_date != NULL)?$endorsement->policy->risk_end_date:"";
                $motorInsurancePolicyBean['motorBusinessType']                      = ($endorsement->policy->business_type != NULL)?$endorsement->policy->business_type:"";
                $sourcingAgentBean                              = array();
                $agent = SourcingAgent::where('id',$endorsement->policy->agent)->first();
                if(!blank($agent)){
                    $sourcingAgentBean['sourcingAgentId']                           = $agent->id;
                    $sourcingAgentBean['sourcingAgentName']                         = $agent->name;
                    $sourcingAgentBean['sourcingAgentMobile']                       = $agent->phone;
                }
                $motorInsurancePolicyBean['sourcingAgentBean']                      = $sourcingAgentBean;
                $subCategoryBean    = array();
                $sub_category = Category::where('id',$endorsement->policy->sub_category)->first();
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
                $array['isPolicyIndividual']                                        = ($endorsement->policy->policy_type != NULL)?$endorsement->policy->policy_type:"";
                if($endorsement->policy->status == 2){
                    $cancel = true;
                }else{
                    $cancel = false;
                }
                $motorInsurancePolicyBean['isCancelled']                            = $cancel;
                $team_lead = array();
                $lead = User::where('id',$endorsement->policy->team_lead)->first();
                if(!blank($lead)){
                    $team_lead['displayName']                                       = $lead->name;
                }
                $motorInsurancePolicyBean['teamLead']                               =  $team_lead;
                $motorInsurancePolicyBean['pypNo']                                  = ($endorsement->policy->pyp_no != NULL)?$endorsement->policy->pyp_no:"";
                $motorInsurancePolicyBean['pypInsuranceCompany']                    = ($endorsement->policy->pyp_insurance_company != NULL)?$endorsement->policy->pyp_insurance_company:"";
                $motorInsurancePolicyBean['pypExpiryDate']                          = ($endorsement->policy->pyp_expiry_date != NULL)?$endorsement->policy->pyp_expiry_date:"";
                $manageBy = array();
                $managed_by = User::where('id',$endorsement->policy->managed_by)->first();
                if(!blank($managed_by)){
                    $manageBy['id']                                                 = $managed_by->id;
                    $manageBy['name']                                               = $managed_by->name;
                }
                $motorInsurancePolicyBean['managedBy']                              = $manageBy;
                $motorInsurancePolicyBean['ncb']                                    = ($endorsement->policy->ncb != NULL)?$endorsement->policy->ncb:"";
                $motorInsurancePolicyBean['cancelDate']                             = ($endorsement->policy->cancel_date != NULL)?$endorsement->policy->cancel_date:"";
                $motorInsurancePolicyBean['cancelReason']                           = ($endorsement->policy->cancel_reason != NULL)?$endorsement->policy->cancel_reason:"";
                $motorInsurancePolicyBean['remarks']                                = ($endorsement->policy->remarks != NULL)?$endorsement->policy->remarks:"";
                $motorInsurancePolicyBean['status']                                 = ($endorsement->policy->status != NULL)?$endorsement->policy->status:0;
                $motorInsurancePolicyBean['created_at']                             = ($endorsement->policy->created_at != NULL)?$endorsement->policy->created_at:"";
                $policy_documents = PolicyDocument::where('policy_id',$endorsement->policy->id)->get();
                $documents = array();
                if(!blank($policy_documents)){
                    $i = 0;
                    foreach($policy_documents as $document){
                        $doc['file']        =   url('/')."/public/policy_document/".$document->file;
                        $doc['name']        =   $document->file_name;
                        $doc['created_at']  =   $document->created_at;
                        $i++;
                        array_push($documents,$doc);
                    }
                }
                $motorInsurancePolicyBean['policyDocuments']                        = $documents;
                $policy_attachments = PolicyAttachment::where('policy_id',$endorsement->policy->id)->get();
                 $attachments = array();
                if(!blank($policy_attachments)){
                   
                    $i = 0;
                    foreach($policy_attachments as $attachment){
                        $atta['file']        =   url('/')."/public/policy_attachment/".$attachment->file;
                        $atta['name']        =   $attachment->file;
                        $atta['type']        =   pathinfo($attachment->file, PATHINFO_EXTENSION);
                        $atta['created_at']  =   $attachment->created_at;
                        $i++;
                        array_push($attachments,$atta);
                    }
                    $motorInsurancePolicyBean['policyAttachments']                  = $attachments;
                }
                $policy_payments = PolicyPayment::where('policy_id',$endorsement->policy->id)->get();
                 $payments = array();
                if(!blank($policy_payments)){
                   
                    $i = 0;
                    foreach($policy_payments as $payment){
                        $pay['id']                  =   $payment->id;
                        $pay['policy_id']           =   $payment->policy_id;
                        $pay['payment_type']        =   $payment->payment_type;
                        if($payment->payment_type == 2){
                            $pay['cheque_no']       =   $payment->cheque_no;  
                            $pay['cheque_date']     =   $payment->cheque_date;
                            $pay['bank_name']       =   $payment->bank_name; 
                        }
                        if($payment->payment_type == 3){
                            $pay['transaction_no']  = $payment->transaction_no;
                        }
                        $pay['payment_date']        =   $payment->payment_date;
                        $pay['created_at']          =   $payment->created_at;
                        $i++;
                        array_push($payments,$pay);
                    }
                }
                $motorInsurancePolicyBean['policyPayments']                     = $payments;
                $array['motorInsurancePolicyBean']                              =  $motorInsurancePolicyBean;
                $array['endorsementDetails']                                    =  $endorsement->details;
                $array['supportingDocuments']                                   =  $endorsement->supporting_documents;
                $array['paymentType']                                           =  $endorsement->payment_type;
                if($endorsement->payment_type == 2){
                    $array['chequeNo']                                          =  $endorsement->cheque_no;
                    $array['bankName']                                          =  $endorsement->bank_name;
                    $array['chequeDate']                                        =  $endorsement->cheque_date;
                }
                if($endorsement->payment_type == 3){
                    $array['transactionNo']                                     =  $endorsement->transaction_no;
                }
                $motorEndorsementAttachmentList = array();
                $endorsementattachments = EndorsementAttachment::where('endorsement_id',$endorsement->id)->get();
                $attachments = array();
                if(!blank($endorsementattachments)){
                    $i = 0;
                    foreach($endorsementattachments as $attachment){
                        $atta['file']        =   url('/')."/public/endorsement_attachment/".$attachment->file;
                        $atta['name']        =   $attachment->file;
                        $atta['type']        =   pathinfo($attachment->file, PATHINFO_EXTENSION);
                        $atta['created_at']  =   $attachment->created_at;
                        $i++;
                        array_push($attachments,$atta);
                    }
                }
                $array['motorEndorsementAttachmentList']                        = $attachments;
                $array['created_at']                                            = $endorsement->created_at;
                array_push($array_push,$array);
            }
            return response()->json([
                'result'                    =>  200,
                'description'               =>  'Motor Endorsement',
                'status'                    =>  1,
                'motorEndorsementListJson'  =>  json_encode($array_push),
                'motorEndorsementListCount' =>  count($endorsements),
                'message'                   =>  'Success!'
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'records not found.','error'=> 'Motor Endorsements Not Found.'],200);
        } 
    }
    public function getHealthEndorsementList(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $currentUser = User::where('id',$accessToken->user_id)->first();
        $query = Endorsement::orderBy('id','DESC')->with('policy');
        $query->whereHas('policy', function($query1) use ($request){
            $query1->where('insurance_type', 2);
        });
        if($request->has('id')){
            $query->where('id',$request->id);
        }
        if($request->has('policyId')){
            $query->where('policy_id',$request->policyId);
        }
        if($request->has('sourcingAgentId')){
            $agent = $request->sourcingAgentId;
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('agent', $request->sourcingAgentId);
            });
        }
        if($request->has('customerId')){
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('customer', $request->customerId);
            });
        }
        if($request->has('endorsementStartDate') && $request->has('endorsementEndDate')){
            $endorsementStartDate =  \DateTime::createFromFormat('d/m/Y',$request->endorsementStartDate);
            $endorsementEndDate =  \DateTime::createFromFormat('d/m/Y',$request->endorsementEndDate);
            $query->whereBetween('created_at',[$endorsementStartDate,$endorsementEndDate]);
        }
        if($request->has('companyId')){
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('company', $request->companyId);
            });
        }
        if($request->has('planId')){
            $query = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('health_plan', $request->planId);
            });
        }
        if($request->has('endorsement_details') && $request->endorsement_details != ''){
            $endorsement->where('details','LIKE','%'.$request->endorsement_details.'%');
        }
        if($currentUser->role == 1){
            $endorsements = $query->get();
        }elseif($currentUser->role == 3){
            $endorsement->whereHas('policy', function($query1) use ($request){
                $query1->where('agent', $currentUser->id);
            });
            $endorsements = $endorsement->get();
        }
        $array_push = array();
        if(!blank($endorsements)){
            foreach($endorsements as $endorsement){
                $array = array();
                $array['healthEndorsementId']                                        =  $endorsement->id;
                $healthInsurancePolicyBean = array();
                $healthInsurancePolicyBean['healthInsuarancePolicyId']                = $endorsement->policy->id;
                $healthInsurancePolicyBean['covernoteNo']                            = $endorsement->policy->covernote_no;
                $healthInsurancePolicyBean['policyNo']                               = $endorsement->policy->policy_no;
                $company = Company::where('id',$endorsement->policy->company)->first();
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
                $customer = Customer::where('id',$endorsement->policy->customer)->first();
                if(!blank($customer)){
                    $customerBean['customerId']                                     = $customer->id;
                    $customerBean['customerName']                                   = $customer->name;
                    $customerBean['customerEmail']                                  = $customer->email;
                    $customerBean['customerMobile']                                 = $customer->phone;
                }
                $healthInsurancePolicyBean['insuranceCompanyBean']                   = $insuranceCompanyBean;
                $healthInsurancePolicyBean['customerBean']                           = $customerBean;
                $healthInsurancePolicyBean['idvAmount']                              = ($endorsement->policy->idv_amount != NULL)?$endorsement->policy->idv_amount:"";
                $healthInsurancePolicyBean['sumInsuredAmount']                       = ($endorsement->policy->sum_insured_amount != NULL)?$endorsement->policy->sum_insured_amount:"";
                $healthInsurancePolicyBean['grossPremiumAmount']                     = ($endorsement->policy->gross_premium_amount != NULL)?$endorsement->policy->gross_premium_amount:"";
                $healthInsurancePolicyBean['netPremiumAmount']                       = ($endorsement->policy->net_premium_amount != NULL)?$endorsement->policy->net_premium_amount:"";
                $healthInsurancePolicyBean['riskStartDate']                          = ($endorsement->policy->risk_start_date != NULL)?$endorsement->policy->risk_start_date:"";
                $healthInsurancePolicyBean['riskEndDate']                            = ($endorsement->policy->risk_end_date != NULL)?$endorsement->policy->risk_end_date:"";
                $healthInsurancePolicyBean['healthBusinessType']                      = ($endorsement->policy->business_type != NULL)?$endorsement->policy->business_type:"";
                $sourcingAgentBean                              = array();
                $agent = SourcingAgent::where('id',$endorsement->policy->agent)->first();
                if(!blank($agent)){
                    $sourcingAgentBean['sourcingAgentId']                           = $agent->id;
                    $sourcingAgentBean['sourcingAgentName']                         = $agent->name;
                    $sourcingAgentBean['sourcingAgentMobile']                       = $agent->phone;
                }
                $healthInsurancePolicyBean['sourcingAgentBean']                      = $sourcingAgentBean;
                $healthInsurancePolicyBean['policyType']                             = "POLICY";
                $array['isPolicyIndividual']                                        = ($endorsement->policy->policy_type != NULL)?$endorsement->policy->policy_type:"";
                if($endorsement->policy->status == 2){
                    $cancel = true;
                }else{
                    $cancel = false;
                }
                $healthInsurancePolicyBean['isCancelled']                            = $cancel;
                $team_lead = array();
                $lead = User::where('id',$endorsement->policy->team_lead)->first();
                if(!blank($lead)){
                    $team_lead['displayName']                                       = $lead->name;
                }
                $healthInsurancePolicyBean['teamLead']                               =  $team_lead;
                $healthInsurancePolicyBean['pypNo']                                  = ($endorsement->policy->pyp_no != NULL)?$endorsement->policy->pyp_no:"";
                $healthInsurancePolicyBean['pypInsuranceCompany']                    = ($endorsement->policy->pyp_insurance_company != NULL)?$endorsement->policy->pyp_insurance_company:"";
                $healthInsurancePolicyBean['pypExpiryDate']                          = ($endorsement->policy->pyp_expiry_date != NULL)?$endorsement->policy->pyp_expiry_date:"";
                $manageBy = array();
                $managed_by = User::where('id',$endorsement->policy->managed_by)->first();
                if(!blank($managed_by)){
                    $manageBy['id']                                                 = $managed_by->id;
                    $manageBy['name']                                               = $managed_by->name;
                }
                $healthInsurancePolicyBean['managedBy']                              = $manageBy;
                $healthInsurancePolicyBean['ncb']                                    = ($endorsement->policy->ncb != NULL)?$endorsement->policy->ncb:"";
                $healthInsurancePolicyBean['cancelDate']                             = ($endorsement->policy->cancel_date != NULL)?$endorsement->policy->cancel_date:"";
                $healthInsurancePolicyBean['cancelReason']                           = ($endorsement->policy->cancel_reason != NULL)?$endorsement->policy->cancel_reason:"";
                $healthInsurancePolicyBean['remarks']                                = ($endorsement->policy->remarks != NULL)?$endorsement->policy->remarks:"";
                $healthInsurancePolicyBean['status']                                 = ($endorsement->policy->status != NULL)?$endorsement->policy->status:0;
                $healthInsurancePolicyBean['created_at']                             = ($endorsement->policy->created_at != NULL)?$endorsement->policy->created_at:"";
                $policy_documents = PolicyDocument::where('policy_id',$endorsement->policy->id)->get();
                if(!blank($policy_documents)){
                    $documents = array();
                    $i = 0;
                    foreach($policy_documents as $document){
                        $doc['file']        =   url('/')."/public/policy_document/".$document->file;
                        $doc['name']        =   $document->file_name;
                        $doc['created_at']  =   $document->created_at;
                        $i++;
                        array_push($documents,$doc);
                    }
                }
                $healthInsurancePolicyBean['policyDocuments']                        = $documents;
                $policy_attachments = PolicyAttachment::where('policy_id',$endorsement->policy->id)->get();
                if(!blank($policy_attachments)){
                    $attachments = array();
                    $i = 0;
                    foreach($policy_attachments as $attachment){
                        $atta['file']        =   url('/')."/public/policy_attachment/".$attachment->file;
                        $atta['name']        =   $attachment->file;
                        $atta['type']        =   pathinfo($attachment->file, PATHINFO_EXTENSION);
                        $atta['created_at']  =   $attachment->created_at;
                        $i++;
                        array_push($attachments,$atta);
                    }
                    $healthInsurancePolicyBean['policyAttachments']                  = $attachments;
                }
                $policy_payments = PolicyPayment::where('policy_id',$endorsement->policy->id)->get();
                if(!blank($policy_payments)){
                    $payments = array();
                    $i = 0;
                    foreach($policy_payments as $payment){
                        $pay['id']                  =   $payment->id;
                        $pay['policy_id']           =   $payment->policy_id;
                        $pay['payment_type']        =   $payment->payment_type;
                        if($payment->payment_type == 2){
                            $pay['cheque_no']       =   $payment->cheque_no;  
                            $pay['cheque_date']     =   $payment->cheque_date;
                            $pay['bank_name']       =   $payment->bank_name; 
                        }
                        if($payment->payment_type == 3){
                            $pay['transaction_no']  = $payment->transaction_no;
                        }
                        $pay['payment_date']        =   $payment->payment_date;
                        $pay['created_at']          =   $payment->created_at;
                        $i++;
                        array_push($payments,$pay);
                    }
                }
                $healthInsurancePolicyBean['policyPayments']                    = $payments;
                $array['healthInsurancePolicyBean']                             =  $healthInsurancePolicyBean;
                $array['endorsementDetails']                                    =  $endorsement->details;
                $array['supportingDocuments']                                   =  $endorsement->supporting_documents;
                $array['paymentType']                                           =  $endorsement->payment_type;
                if($endorsement->payment_type == 2){
                    $array['chequeNo']                                          =  $endorsement->cheque_no;
                    $array['bankName']                                          =  $endorsement->bank_name;
                    $array['chequeDate']                                        =  $endorsement->cheque_date;
                }
                if($endorsement->payment_type == 3){
                    $array['transactionNo']                                     =  $endorsement->transaction_no;
                }
                $healthEndorsementAttachmentList = array();
                $endorsementattachments = EndorsementAttachment::where('endorsement_id',$endorsement->id)->get();
                $attachments = array();
                if(!blank($endorsementattachments)){
                    $i = 0;
                    foreach($endorsementattachments as $attachment){
                        $atta['file']        =   url('/')."/public/endorsement_attachment/".$attachment->file;
                        $atta['name']        =   $attachment->file;
                        $atta['type']        =   pathinfo($attachment->file, PATHINFO_EXTENSION);
                        $atta['created_at']  =   $attachment->created_at;
                        $i++;
                        array_push($attachments,$atta);
                    }
                }
                $array['healthEndorsementAttachmentList']                        =  $attachments;
                $array['created_at']                                            = $endorsement->created_at;
                array_push($array_push,$array);
            }
            return response()->json([
                'result'                    =>  200,
                'description'               =>  'Health Endorsement',
                'status'                    =>  1,
                'healthEndorsementListJson'  =>  json_encode($array_push),
                'healthEndorsementListCount' =>  count($endorsements),
                'message'                   =>  'Success!'
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'records not found.','error'=> 'Health Endorsements Not Found.'],200);
        } 
    }
    public function addMotorEndorsement(Request $request){
        $messages = [
            'transactionNo.required_if' => 'The Transaction Number field is required when the Payment type is ONLINE.',
            'chequeDate.required_if' => 'The Cheque Date field is required when the Payment type is CHEQUE.',
            'bankName.required_if' => 'The Bank Name field is required when the Payment type is CHEQUE.',
            'chequeNo.required_if' => 'The Cheque Number field is required when the Payment type is CHEQUE.',
        ];
        $validator = Validator::make($request->all(), [
            'endorsementDetails'                => 'required',
            'policyId'                          => 'required',
            // 'motorEndorsementAttachment'        => 'required',
            'transactionNo'                     => 'required_if:paymentType,3',
            'chequeNo'                          => 'required_if:paymentType,2',
            'chequeDate'                        => 'required_if:paymentType,2',
            'bankName'                          => 'required_if:paymentType,2',
        ], $messages);
        if($validator->fails()){
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            if(!blank($request)){
                $motor_endorsement = new Endorsement();
                $motor_endorsement->policy_id = $request->policyId;
                $motor_endorsement->details = $request->endorsementDetails;
                $motor_endorsement->supporting_documents = $request->supportingDocuments;
                $motor_endorsement->payment_type = $request->paymentType;
                if($request->has('chequeNo')){
                    $motor_endorsement->cheque_no = $request->chequeNo;
                }
                if($request->has('bankName')){
                    $motor_endorsement->bank_name = $request->bankName;
                }
                if($request->has('chequeDate')){
                    $motor_endorsement->cheque_date = $request->chequeDate;
                }
                if($request->has('transactionNo')){
                    $motor_endorsement->transaction_no = $request->transactionNo;
                }
                $motor_endorsement->save();
                if($request->has('motorEndorsementAttachment')){
                    foreach($request->motorEndorsementAttachment as $file){
                        if($file && $file != null){
                            $image_file = $file;
                            $destinationPath1 = 'public/endorsement_attachment/';
                            $rand1=rand(1,100);
                            $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment=$docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new EndorsementAttachment();
                        $attachment->endorsement_id     = $motor_endorsement->id;
                        $attachment->policy_id          = $request->policy_id;
                        $attachment->file               = $img_attachment;
                        $attachment->save();
                    }
                }
                return response()->json(['result'=>200,'status'=>1,'description'=>'Save successfully.','message'=> 'Success!.'],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }
    }
    public function editMotorEndorsement(Request $request){
        // print_r($request->All());
        // exit;
        $messages = [
            'transactionNo.required_if' => 'The Transaction Number field is required when the Payment type is ONLINE.',
            'chequeDate.required_if' => 'The Cheque Date field is required when the Payment type is CHEQUE.',
            'bankName.required_if' => 'The Bank Name field is required when the Payment type is CHEQUE.',
            'chequeNo.required_if' => 'The Cheque Number field is required when the Payment type is CHEQUE.',
        ];
        $validator = Validator::make($request->all(), [
            'endorsementDetails'                => 'required',
            'policyId'                          => 'required',
            // 'motorEndorsementAttachment'        => 'required',
            'transactionNo'                     => 'required_if:paymentType,3',
            'chequeDate'                        => 'required_if:paymentType,2',
            'chequeNo'                        => 'required_if:paymentType,2',
            'bankName'                          => 'required_if:paymentType,2',
        ],$messages);
        if($validator->fails()){
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            if(!blank($request)){
                $motor_endorsement = Endorsement::where('id', $request->id)->first();
                $motor_endorsement->policy_id = $request->policyId;
                $motor_endorsement->details = $request->endorsementDetails;
                $motor_endorsement->supporting_documents = $request->supportingDocuments;
                $motor_endorsement->payment_type = $request->paymentType;
                if($request->has('chequeNo')){
                    $motor_endorsement->cheque_no = $request->chequeNo;
                }
                if($request->has('bankName')){
                    $motor_endorsement->bank_name = $request->bankName;
                }
                if($request->has('chequeDate')){
                    $motor_endorsement->cheque_date = $request->chequeDate;
                }
                if($request->has('transactionNo')){
                    $motor_endorsement->transaction_no = $request->transactionNo;
                }
                $insert             = $motor_endorsement->save();
                if($request->has('motorEndorsementAttachment')){
                    foreach($request->motorEndorsementAttachment as $file){
                        if($file && $file != null){
                            $image_file = $file;
                            $destinationPath1 = 'public/endorsement_attachment/';
                            $rand1=rand(1,100);
                            $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment=$docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new EndorsementAttachment();
                        $attachment->endorsement_id     = $motor_endorsement->id;
                        $attachment->policy_id          = $request->policyId;
                        $attachment->file               = $img_attachment;
                        $attachment->save();
                    }
                }
                return response()->json(['result'=>200,'status'=>1,'description'=>'Updated successfully.','message'=> 'Success!.'],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }      
    }
    public function deleteMotorEndorsement(Request $request, $id){
        if(!blank($id)){
            $motor_endorsement = Endorsement::where('id', $id)->first();
            $motor_endorsement->delete();
            return response()->json(['result'=>200,'status'=>1,'description'=>'Deleted successfully.','message'=> 'Success!.'],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
        }
    }
    public function addHealthEndorsement(Request $request){
        $messages = [
            'transactionNo.required_if' => 'The Transaction Number field is required when the Payment type is ONLINE.',
            'chequeDate.required_if' => 'The Cheque Date field is required when the Payment type is CHEQUE.',
            'bankName.required_if' => 'The Bank Name field is required when the Payment type is CHEQUE.',
            'chequeNo.required_if' => 'The Cheque Number field is required when the Payment type is CHEQUE.',
        ];
        $validator = Validator::make($request->all(), [
            'endorsementDetails'                => 'required',
            'policyId'                          => 'required',
            // 'motorEndorsementAttachment'        => 'required',
            'transactionNo'                     => 'required_if:paymentType,3',
            'chequeDate'                        => 'required_if:paymentType,2',
            'chequeNo'                          => 'required_if:paymentType,2',
            'bankName'                          => 'required_if:paymentType,2',
        ], $messages);
        if($validator->fails()){
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            if(!blank($request)){
                $motor_endorsement = new Endorsement();
                $motor_endorsement->policy_id = $request->policyId;
                $motor_endorsement->details = $request->endorsementDetails;
                $motor_endorsement->supporting_documents = $request->supportingDocuments;
                $motor_endorsement->payment_type = $request->paymentType;
                if($request->has('chequeNo')){
                    $motor_endorsement->cheque_no = $request->chequeNo;
                }
                if($request->has('bankName')){
                    $motor_endorsement->bank_name = $request->bankName;
                }
                if($request->has('chequeDate')){
                    $motor_endorsement->cheque_date = $request->chequeDate;
                }
                if($request->has('transactionNo')){
                    $motor_endorsement->transaction_no = $request->transactionNo;
                }
                $motor_endorsement->save();
                if($request->has('healthEndorsementAttachment')){
                    foreach($request->healthEndorsementAttachment as $file){
                        if($file && $file != null){
                            $image_file = $file;
                            $destinationPath1 = 'public/endorsement_attachment/';
                            $rand1=rand(1,100);
                            $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment=$docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new EndorsementAttachment();
                        $attachment->endorsement_id     = $motor_endorsement->id;
                        $attachment->policy_id          = $request->policy_id;
                        $attachment->file               = $img_attachment;
                        $attachment->save();
                    }
                }
                return response()->json(['result'=>200,'status'=>1,'description'=>'Save successfully.','message'=> 'Success!.'],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }
    }

    public function editHealthEndorsement(Request $request){
        $messages = [
            'transactionNo.required_if' => 'The Transaction Number field is required when the Payment type is ONLINE.',
            'chequeDate.required_if' => 'The Cheque Date field is required when the Payment type is CHEQUE.',
            'bankName.required_if' => 'The Bank Name field is required when the Payment type is CHEQUE.',
            'chequeNo.required_if' => 'The Cheque Number field is required when the Payment type is CHEQUE.',
        ];
        $validator = Validator::make($request->all(), [
            'endorsementDetails'                => 'required',
            'policyId'                          => 'required',
            // 'motorEndorsementAttachment'        => 'required',
            'transactionNo'                     => 'required_if:paymentType,3',
            'chequeNo'                          => 'required_if:paymentType,2',
            'chequeDate'                        => 'required_if:paymentType,2',
            'bankName'                          => 'required_if:paymentType,2',
        ]);
        if($validator->fails()){
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            if(!blank($request)){
                $health_endorsement = Endorsement::where('id', $request->id)->first();
                $health_endorsement->policy_id = $request->policyId;
                $health_endorsement->details = $request->endorsementDetails;
                $health_endorsement->supporting_documents = $request->supportingDocuments;
                $health_endorsement->payment_type = $request->paymentType;
                if($request->has('chequeNo')){
                    $health_endorsement->cheque_no = $request->chequeNo;
                }
                if($request->has('bankName')){
                    $health_endorsement->bank_name = $request->bankName;
                }
                if($request->has('chequeDate')){
                    $health_endorsement->cheque_date = $request->chequeDate;
                }
                if($request->has('transactionNo')){
                    $health_endorsement->transaction_no = $request->transactionNo;
                }
                $insert             = $health_endorsement->save();
                if($request->has('healthEndorsementAttachment')){
                    foreach($request->healthEndorsementAttachment as $file){
                        if($file && $file != null){
                            $image_file = $file;
                            $destinationPath1 = 'public/endorsement_attachment/';
                            $rand1=rand(1,100);
                            $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment=$docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new EndorsementAttachment();
                        $attachment->endorsement_id     = $health_endorsement->id;
                        $attachment->policy_id          = $request->policyId;
                        $attachment->file               = $img_attachment;
                        $attachment->save();
                    }
                }
                return response()->json(['result'=>200,'status'=>1,'description'=>'Updated successfully.','message'=> 'Success!.'],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }
    }

    public function deleteHealthEndorsement(Request $request, $id){
        if(!blank($id)){
            $health_endorsement = Endorsement::where('id', $id)->first();
            $health_endorsement->delete();
            return response()->json(['result'=>200,'status'=>1,'description'=>'Deleted successfully.','message'=> 'Success!.'],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
        }
    }
}
