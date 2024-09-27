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
use App\Models\Parameter;
use App\Models\Policy;
use App\Models\PolicyParameter;
use App\Models\PolicyAttachment;
use App\Models\PolicyDocument;
use App\Models\PolicyPayment;
use App\Models\Covernote;
use App\Models\CovernoteParameter;
use App\Models\CovernoteAttachment;
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

class CovernoteController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function getCovernoteList(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $currentUser = User::where('id',$accessToken->user_id)->first();
        $query = Covernote::orderBy('id','Desc');
        if($request->has('id') && $request->id != ''){
            $query->where('id',$request->id);
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
        if(!blank($policies)){
            $array_push = array();
            foreach($policies as $policy){
                $array = array();
                $array['covernoteId']                               = $policy->id;
                $array['covernoteNo']                               = ($policy->covernote_no != NULL)?$policy->covernote_no:"";
                $array['InsuranceType']                             = ($policy->insurance_type != NULL)?$policy->insurance_type:"";
                $array['policyNo']                                  = ($policy->policy_no != NULL)?$policy->policy_no:"";
                $array['category']                                  = ($policy->category != NULL)?(int)$policy->category:"";
                $CategoryBean                                       = [];
                $category = Category::where('id',$policy->category)->first();
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
                $parameters = CovernoteParameter::where('covernote_id',$policy->id)->get();
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
                $array['covernoteParameter'] = $policyParameter;
                $array['isPolicyIndividual']                = ($policy->policy_type != NULL)?$policy->policy_type:"";
                $array['policyIndividualRate']              = ($policy->policy_individual_rate != NULL)?$policy->policy_individual_rate:0;
                $array['pypNo']                             = ($policy->pyp_no != NULL)?$policy->pyp_no:"";
                $array['pypInsuranceCompany']               = ($policy->pyp_insurance_company != NULL)?$policy->pyp_insurance_company:"";
                $array['pypExpiryDate']                     = ($policy->pyp_expiry_date != NULL)?date('d-m-Y',strtotime($policy->pyp_expiry_date)):"";
                $array['paymentType']                       = ($policy->payment_type != NULL)?$policy->payment_type:1;
                if($policy->payment_type == 2){
                    $array['cheque_no']                     = ($policy->cheque_no != NULL)?$policy->cheque_no:"";
                    $array['cheque_date']                   = ($policy->cheque_date != NULL)?date('d-m-Y',strtotime($policy->cheque_date)):"";
                    $array['bank_name']                     = ($policy->bank_name != NULL)?$policy->bank_name:"";
                }
                if($policy->payment_type == 3){
                    $array['transaction_no']                = ($policy->transaction_no != NULL)?$policy->transaction_no:"";
                }
                $array['remarks']                          = ($policy->remarks != NULL)?$policy->remarks:"";
                $array['status']                           = ($policy->status != NULL)?$policy->status:0;
                $array['created_at']                       = ($policy->created_at != NULL)?date('d-m-Y',strtotime($policy->created_at)):"";
                $policy_attachments = CovernoteAttachment::where('covernote_id',$policy->id)->get();
                if(!blank($policy_attachments)){
                    $attachments = array();
                    $i = 0;
                    foreach($policy_attachments as $attachment){
                        $atta['file']        =   url('/')."/public/covernote_attachment/".$attachment->file;
                        $atta['name']        =   $attachment->file;
                        $atta['type']        =   pathinfo($attachment->file, PATHINFO_EXTENSION);
                        $atta['created_at']  =   date('d-m-Y',strtotime($attachment->created_at));
                        $i++;
                        array_push($attachments,$atta);
                    }
                    $array['covernoteAttachments']                  = $attachments;
                }
                array_push($array_push,$array);
            }
            return response()->json([
                'result'        => 200,
                'description'   => 'Covernote',
                'status' => 1,
                'covernoteListJson'=>json_encode($array_push),
                'covernoteListCount'=>count($policies),
                'totalCount'=>count($policies),
                'message'       => 'Success!'
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Covernotes Not Found.'],200);
        }
    }
    public function addCovernote(Request $request){
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            if(!blank($accessToken)){
                $currentUser = User::where('id',$accessToken->user_id)->first();
                $agent = SourcingAgent::where('user_id',$currentUser->id)->first();
            }else{
                $currentUser = User::where('id',1)->first();
            }
            
        $messages = [
            'pypNo.required_if' => 'The PYP number field is required when the Business type is Renew or Rollover.',
            'pypInsuranceCompany.required_if' => 'The PYP number field is required when the Business type is Renew or Rollover.',
            'pypExpiryDate.required_if' => 'The PYP number field is required when the Business type is Renew or Rollover.',
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
            'BusinessType'                  => 'required',
            'sourcingAgentId'               => 'required|not_in:0',
            'grossPremiumAmount'            => 'required',
            'netPremiumAmount'              => 'required',
            'riskStartDate'                 => 'required',
            'riskEndDate'                   => 'required',
            'policyType'                    => 'required_if:categoryId,1',
            'pypNo'                         => 'required_if:BusinessType,2|required_if:BusinessType,3',
            'pypInsuranceCompany'           => 'required_if:BusinessType,2|required_if:BusinessType,3',
            'pypExpiryDate'                 => 'required_if:BusinessType,2|required_if:BusinessType,3',
        ], $messages);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $policy = new Covernote();
            $policy->insurance_type                 = 1;
            $policy->category                       = $request->categoryId;
            $policy->policy_type                    = $request->policyType;
            $policy->policy_individual_rate         = $request->policyIndividualRate;
            $policy->sub_category                   = $request->subCategoryId;
            $policy->company                        = $request->insuranceCompanyId;
            $policy->covernote_no                   = $request->covernoteNo;
            $policy->customer                       = $request->customerId;
            $policy->vehicle_model                  = $request->vehicleModel;
            // $policy->vehicle_engine                 = $request->vehicleEngine;
            $policy->vehicle_chassis_no             = $request->vehicleChassisNo;
            $policy->idv_amount                     = $request->idvAmount;
            $policy->net_premium_amount             = $request->netPremiumAmount;
            $policy->pyp_no                         = ($request->has('pypNo'))?$request->pypNo:"";
            $policy->pyp_insurance_company          = ($request->has('pypInsuranceCompany'))?$request->pypInsuranceCompany:"";
            $policy->pyp_expiry_date                = $request->pypExpiryDate !== null? \DateTime::createFromFormat('d/m/Y',$request->pypExpiryDate):"";
            $policy->agent                          = $agent->id ?? 2;
            $policy->vehicle_make                   = $request->vehicleMake;
            $policy->vehicle_registration_no        = $request->vehicleRegistrationNo;
            $policy->year_of_manufacture            = $request->yearOfManufacture;
            $policy->gross_premium_amount           = $request->grossPremiumAmount;
            $policy->risk_start_date                = \DateTime::createFromFormat('d-m-Y',$request->riskStartDate);
            $policy->risk_end_date                  = \DateTime::createFromFormat('d-m-Y',$request->riskEndDate);
            $policy->business_type                  = $request->BusinessType;
            $policy->remarks                        = $request->remarks;
            $policy->created_by                     = $request->userId;
            $policy->payment_type                   = $request->paymentType;
            if($request->paymentType == 2){
                $policy->cheque_no                  = ($request->chequeNo != '')?$request->chequeNo:"";
                $policy->cheque_date                = ($request->chequeDate != '')? \DateTime::createFromFormat('d/m/Y',$request->chequeDate):"";
                $policy->bank_name                  = ($request->bankName != '')?$request->bankName:"";
            }
            if($request->paymentType == 3){
                $policy->transaction_no             = $request->transactionNo;
            }
            $policy->status                         = 1;
            $policy->save();
            if($policy){
                if($request->has('covernoteAttachments')){
                    foreach($request->covernoteAttachments as $file){
                        if($file && $file != null){
                            $image_file = $file;
                            $destinationPath1 = 'public/covernote_attachment/';
                            $rand1=rand(1,100);
                            $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                            $image_file->move($destinationPath1, $docImage1);
                            $img_attachment=$docImage1;
                        }else{
                            $img_attachment='';
                        }
                        $attachment = new CovernoteAttachment();
                        $attachment->covernote_id   = $policy->id;
                        $attachment->file           = $img_attachment;
                        $attachment->save();
                    }
                }
               if($request->has('covernoteParameters')){
                    foreach($request->covernoteParameters as $param){
                        if($param->typeid == 1 || $param->typeid == 2){
                            $parameter = new CovernoteParameter();
                            $parameter->type                    = $param->typeid;
                            $parameter->covernote_id            = $policy->id;
                            $parameter->sub_category_id         = $request->subcategory;
                            $parameter->parameter_id            = $param->tpCalculationParameterId;
                            $parameter->value                   = $param->tpCalculationParameterId;
                            $parameter->save();
                        }elseif($param->typeid == 3){
                            $parameter = new CovernoteParameter();
                            $parameter->type                    = $param->typeid;
                            $parameter->covernote_id            = $policy->id;
                            $parameter->sub_category_id         = $request->subcategory;
                            $parameter->parameter_id            = $param->tpCalculationParameterId;
                            $parameter->taxi_cc                 = $param->value;
                            $parameter->save();
                        }elseif($param->typeid == 4){
                            $parameter = new CovernoteParameter();
                            $parameter->type                    = $param->typeid;
                            $parameter->covernote_id            = $policy->id;
                            $parameter->sub_category_id         = $request->subcategory;
                            $parameter->parameter_id            = $param->tpCalculationParameterId;
                            $parameter->value                   = $param->tpCalculationParameterId;
                            $parameter->save();
                        }elseif($param->typeid == 5 || $param->typeid == 6){
                            $parameter = new CovernoteParameter();
                            $parameter->type                    = $param->typeid;
                            $parameter->covernote_id            = $policy->id;
                            $parameter->sub_category_id         = $request->subcategory;
                            $parameter->parameter_id            = $param->tpCalculationParameterId;
                            $parameter->value                   = $param->tpCalculationParameterId;
                            $parameter->save();
                        }elseif($param->typeid == 7){
                            $parameter = new CovernoteParameter();
                            $parameter->type                    = $param->typeid;
                            $parameter->covernote_id            = $policy->id;
                            $parameter->sub_category_id         = $request->subcategory;
                            $parameter->parameter_id            = $param->tpCalculationParameterId;
                            $parameter->value                   = $param->value;
                            $parameter->save();
                        }
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
                // if($request->has('policyPayments')){
                //     foreach($request->policyPayments as $key=>$payment){
                //         $payment_type = $payment;
                //         $chq_no = '';
                //         $chq_date = '';
                //         $bak_name = '';
                //         $transaction = '';
                //         $date = '';
                //         if($request->has('cheque_no')){
                //             foreach($request->cheque_no as $cheque_no_key=>$cheque_no){
                //                 if($cheque_no_key == $key){
                //                     $chq_no = $cheque_no;
                //                 }
                //             }
                //         }
                //         if($request->has('cheque_date')){
                //             foreach($request->cheque_date as $cheque_date_key=>$cheque_date){
                //                 if($cheque_date_key == $key){
                //                     $chq_date = $cheque_date;
                //                 }
                //             }
                //         }
                //         if($request->has('bank_name')){
                //             foreach($request->bank_name as $bank_name_key=>$bank_name){
                //                 if($bank_name_key == $key){
                //                     $bak_name = $bank_name;
                //                 }
                //             }
                //         }
                //         if($request->has('transaction_no')){
                //             foreach($request->transaction_no as $transaction_no_key=>$transaction_no){
                //                 if($transaction_no_key == $key){
                //                     $transaction = $transaction_no;
                //                 }
                //             }
                //         }
                //         if($request->has('payment_date')){
                //             foreach($request->payment_date as $payment_date_key=>$payment_date){
                //                 if($payment_date_key == $key){
                //                     $date = $payment_date;
                //                 }
                //             }
                //         }
                //         $policy_payment = new PolicyPayment();
                //         $policy_payment->policy_id      = $policy->id;
                //         $policy_payment->payment_type   = $payment_type;
                //         $policy_payment->cheque_no      = $chq_no;
                //         $policy_payment->cheque_date    = $chq_date;
                //         $policy_payment->bank_name      = $bak_name;
                //         $policy_payment->transaction_no = $transaction;
                //         $policy_payment->payment_date   = $date;
                //         $policy_payment->status         = 1;
                //         $policy_payment->save();
                //     }
                // }
                return response()->json(['result'=>200,'status'=>1,'description'=>'Save successfully.','message'=> 'Success!.'],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
            }
        }
    }
    public function deleteCovernote(Request $request, $id = NULL){
        $covernote_attachments = CovernoteAttachment::where('covernote_id',$id)->delete();
        $covernote_parameter = CovernoteParameter::where('covernote_id',$id)->delete();
        $covernote = Covernote::where('id',$id)->delete();
        if($covernote){
            return response()->json(['result'=>200,'status'=>1,'description'=>'Deleted successfully.','message'=> 'Success!.'],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
        }
    }
public function updateCovernote(Request $request)
{
    $authorization = $request->header('Authorization');
    $accessToken = AccessToken::where('access_token', $authorization)->first();
    if (!blank($accessToken)) {
        $currentUser = User::where('id', $accessToken->user_id)->first();
        $agent = SourcingAgent::where('user_id', $currentUser->id)->first();
    } else {
        $currentUser = User::where('id', 1)->first();
    }

    $messages = [
        'pypNo.required_if' => 'The PYP number field is required when the Business type is Renew or Rollover.',
        'pypInsuranceCompany.required_if' => 'The PYP number field is required when the Business type is Renew or Rollover.',
        'pypExpiryDate.required_if' => 'The PYP number field is required when the Business type is Renew or Rollover.',
    ];

    $validator = Validator::make($request->all(), [
        // Other validations...
        'riskStartDate' => 'required',
        'riskEndDate' => 'required',
        'pypExpiryDate' => 'required_if:BusinessType,2|required_if:BusinessType,3',
        'chequeDate' => 'sometimes|nullable',
        // Other validations...
    ], $messages);

    if ($validator->fails()) {
        return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 200);
    } else {
        $policy = Covernote::where('id', $request->id)->first();
        $policy->insurance_type = 1;
        $policy->category = $request->categoryId;
        $policy->policy_type = $request->policyType;
        $policy->policy_individual_rate = $request->policyIndividualRate;
        $policy->sub_category = $request->subCategoryId;
        $policy->company = $request->insuranceCompanyId;
        $policy->covernote_no = $request->covernoteNo;
        $policy->customer = $request->customerId;
        $policy->vehicle_model = $request->vehicleModel;
        // $policy->vehicle_engine = $request->vehicleEngine;
        $policy->vehicle_chassis_no = $request->vehicleChassisNo;
        $policy->idv_amount = $request->idvAmount;
        $policy->net_premium_amount = $request->netPremiumAmount;
        $policy->pyp_no = $request->has('pypNo') ? $request->pypNo : "";
        $policy->pyp_insurance_company = $request->has('pypInsuranceCompany') ? $request->pypInsuranceCompany : "";
        $policy->pyp_expiry_date = $request->has('pypExpiryDate') ? \DateTime::createFromFormat('d/m/Y', $request->pypExpiryDate) : null;
        $policy->agent = $agent->id ?? 2;
        $policy->vehicle_make = $request->vehicleMake;
        $policy->vehicle_registration_no = $request->vehicleRegistrationNo;
        $policy->year_of_manufacture = $request->yearOfManufacture;
        $policy->gross_premium_amount = $request->grossPremiumAmount;
        $policy->risk_start_date = \DateTime::createFromFormat('d-m-Y', $request->riskStartDate);
        $policy->risk_end_date = \DateTime::createFromFormat('d-m-Y', $request->riskEndDate);
        $policy->business_type = $request->BusinessType;
        $policy->remarks = $request->remarks;
        $policy->created_by = $request->userId;
        $policy->payment_type = $request->paymentType;

        if ($request->paymentType == 2) {
            $policy->cheque_no = $request->chequeNo != '' ? $request->chequeNo : "";
            $policy->cheque_date = $request->chequeDate != '' ? \DateTime::createFromFormat('d/m/Y', $request->chequeDate) : null;
            $policy->bank_name = $request->bankName != '' ? $request->bankName : "";
        }

        if ($request->paymentType == 3) {
            $policy->transaction_no = $request->transactionNo;
        }

        $policy->status = 1;
        $policy->save();

        if ($policy) {
            if ($request->has('covernoteAttachments')) {
                foreach ($request->covernoteAttachments as $file) {
                    if ($file && $file != null) {
                        $image_file = $file;
                        $destinationPath1 = 'public/covernote_attachment/';
                        $rand1 = rand(1, 100);
                        $docImage1 = date('YmdHis') . $rand1 . "." . $image_file->getClientOriginalExtension();
                        $image_file->move($destinationPath1, $docImage1);
                        $img_attachment = $docImage1;
                    } else {
                        $img_attachment = '';
                    }
                    $attachment = new CovernoteAttachment();
                    $attachment->covernote_id = $policy->id;
                    $attachment->file = $img_attachment;
                    $attachment->save();
                }
            }
            $parameterDelete = CovernoteParameter::where('covernote_id', $request->id)->delete();
            if ($request->has('covernoteParameters')) {
                foreach ($request->covernoteParameters as $param) {
                    $parameter = new CovernoteParameter();
                    $parameter->type = $param->typeid;
                    $parameter->covernote_id = $policy->id;
                    $parameter->sub_category_id = $request->subcategory;
                    $parameter->parameter_id = $param->tpCalculationParameterId;
                    $parameter->value = $param->tpCalculationParameterId;

                    if ($param->typeid == 3) {
                        $parameter->taxi_cc = $param->value;
                    } else {
                        $parameter->value = $param->tpCalculationParameterId;
                    }

                    $parameter->save();
                }
            }

            return response()->json(['result' => 200, 'status' => 1, 'description' => 'Updated successfully.', 'message' => 'Success!'], 200);
        } else {
            return response()->json(['result' => 200, 'status' => 0, 'description' => 'Something went wrong.', 'error' => 'Something went wrong.'], 200);
        }
    }
}


    public function convertCovernote(Request $request, $id = NULL){
        if(!blank($id)){
            $covernote = Covernote::where('id',$id)->first();
            if(!blank($covernote)){
                $policy = new Policy();
                $policy->insurance_type          = $covernote->insurance_type;
                $policy->category                = $covernote->category;
                $policy->policy_type             = $covernote->policy_type;
                $policy->policy_individual_rate  = $covernote->policy_individual_rate;
                $policy->category                = $covernote->category;
                $policy->sub_category            = $covernote->sub_category;
                $policy->company                 = $covernote->company;
                $policy->covernote_no            = $covernote->covernote_no;
                $policy->policy_no               = $covernote->covernote_no;
                $policy->customer                = $covernote->customer;
                $policy->vehicle_model           = $covernote->vehicle_model;
                $policy->vehicle_chassis_no      = $covernote->vehicle_chassis_no;
                $policy->health_category         = $covernote->health_category;
                $policy->health_plan             = $covernote->health_plan;
                $policy->cc                      = $covernote->cc;
                $policy->paid_driver             = $covernote->paid_driver;
                $policy->idv_amount              = $covernote->idv_amount;
                $policy->net_premium_amount      = $covernote->net_premium_amount;
                $policy->sum_insured_amount      = $covernote->sum_insured_amount;
                $policy->business_amount         = $covernote->business_amount;
                $policy->pyp_no                  = $covernote->pyp_no;
                $policy->pyp_insurance_company   = $covernote->pyp_insurance_type;
                $policy->pyp_expiry_date         = $covernote->pyp_expiry_date;
                $policy->agent                   = $covernote->agent;
                $policy->vehicle_make            = $covernote->vehicle_make;
                $policy->vehicle_registration_no = $covernote->vehicle_registration_no;
                $policy->year_of_manufacture     = $covernote->year_of_manufacture;
                $policy->seating_capacity        = $covernote->seating_capacity;
                $policy->pa_to_passenger         = $covernote->pa_to_passenger;
                $policy->gross_premium_amount    = $covernote->gross_premium_amount;
                $policy->risk_start_date         = $covernote->risk_start_date;
                $policy->risk_end_date           = $covernote->risk_end_date;
                $policy->payment_type            = $covernote->payment_type;
                $policy->business_type           = $covernote->business_type;
                $policy->cheque_no               = $covernote->cheque_no;
                $policy->cheque_date             = $covernote->cheque_date;
                $policy->bank_name               = $covernote->bank_name;
                $policy->transaction_no          = $covernote->transaction_no;
                $policy->policy_document         = '';
                $policy->remarks                 = $covernote->remarks;
                $policy->created_by              = $covernote->created_by;
                $policy->status                  = 1;
                $policy->save();
                $attachments = CovernoteAttachment::where('covernote_id',$id)->get();
                foreach($attachments as $attachment){
                    $oldPath = 'public/covernote_attachment/'.$attachment->file;
                    $rand = rand(1,100);
                    $fileExtension = \File::extension($oldPath);
                    $newName = date('YmdHis').$rand.'.'.$fileExtension;
                    $newPathWithName = "public/policy_attachment/".$newName;
                    if (\File::move($oldPath , $newPathWithName)) {
                        $policy_attachment = new PolicyAttachment();
                        $policy_attachment->policy_id    = $policy->id;
                        $policy_attachment->file         = $newName;
                        $policy_attachment->save();
                    }
                }
                $parameters = CovernoteParameter::where('covernote_id',$id)->get();
                if(!blank($parameters)){
                    foreach($parameters as $param){
                        if($param->type == 3){
                            $parameter = new PolicyParameter();
                            $parameter->policy_id               = $policy->id;
                            $parameter->type                    = 3;
                            $parameter->sub_category_id         = $param->sub_category_id;
                            $parameter->parameter_id            = $param->parameter_id;
                            $parameter->taxi_cc                 = $param->taxi_cc;
                            $parameter->seating_capacity        = $param->seating_capacity;
                            $parameter->paid_driver             = $param->paid_driver;
                            $parameter->save();
                        }elseif($param->type == 5){
                            $parameter = new PolicyParameter();
                            $parameter->policy_id               = $policy->id;
                            $parameter->type                    = 5;
                            $parameter->sub_category_id         = $param->sub_category_id;
                            $parameter->parameter_id            = $param->parameter_id;
                            $parameter->save();
                        }elseif($param->type == 6){
                            $parameter = new PolicyParameter();
                            $parameter->policy_id               = $policy->id;
                            $parameter->type                    = 6;
                            $parameter->sub_category_id         = $param->sub_category_id;
                            $parameter->parameter_id            = $param->parameter_id;
                            $parameter->save();
                        }elseif($param->type == 1){
                            $parameter = new PolicyParameter();
                            $parameter->type                    = 1;
                            $parameter->policy_id               = $policy->id;
                            $parameter->sub_category_id         = $param->sub_category_id;
                            $parameter->parameter_id            = $param->parameter_id;
                            $parameter->value                   = $param->value;
                            $parameter->save();
                        }elseif($param->type == 2){
                            $parameter = new PolicyParameter();
                            $parameter->type                    = 2;
                            $parameter->policy_id               = $policy->id;
                            $parameter->sub_category_id         = $param->sub_category_id;
                            $parameter->parameter_id            = $param->parameter_id;
                            $parameter->value                   = $param->parameter_id;
                            $parameter->save();
                        }elseif($param->type == 7){
                            $parameter = new PolicyParameter();
                            $parameter->type                    = 7;
                            $parameter->policy_id               = $policy->id;
                            $parameter->sub_category_id         = $param->sub_category_id;
                            $parameter->parameter_id            = $param->parameter_id;
                            $parameter->value                   = $param->value;
                            $parameter->save();
                        }elseif($param->type == 4){
                            $parameter = new PolicyParameter();
                            $parameter->type                    = 4;
                            $parameter->policy_id               = $policy->id;
                            $parameter->sub_category_id         = $param->sub_category_id;
                            $parameter->parameter_id            = $param->parameter_id;
                            $parameter->value                   = $param->value;
                            $parameter->save();
                        }
                    }
                }
                $covernote_delete = Covernote::where('id',$id)->delete();
                return response()->json(['result'=>200,'status'=>1,'description'=>'Converted successfully.','message'=> 'Success!.'],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Covernote not found.','message'=> 'Success!.'],200);  
            }
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Something went wrong.'],200);
        }
    }
}
