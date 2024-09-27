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
use App\Models\SourcingAgentPayout;
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
use App\Models\PayoutList;
use App\Models\PayoutListRecord;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CancelPolicyExport;
use App\Exports\CustomerPayoutCsvExport;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use PDF;
use Response;
use File;
use Storage; 
class PayoutController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function getPayoutInitialData(Request $request){
        $agents = SourcingAgent::where('status',1)->orderBy('name','ASC')->get();
        $array_push = array();
        foreach($agents as $agent){
            $array = array();
            $array['sourcingAgentId']           = $agent->id;
            $array['sourcingAgentName']         = $agent->name;
            $array['teamLeadId']                = $agent->team_lead;
            $team_lead = User::where('id',$agent->team_lead)->first();
            if(!blank($team_lead)){
                $array['teamLeadName']              = $team_lead->first_name.' '.$team_lead->last_name;
            }else{
                $array['teamLeadName']              = "";
            }
            array_push($array_push,$array);
        }
        return response()->json([
            'result'                    =>  200,
            'description'               =>  'Payout initial data',
            'status'                    =>  1,
            'sourcingAgentListJson'     =>  json_encode($array_push),
            'message'                   =>  'Success!'
        ],200);
    }
    public function getPayoutList(Request $request){
        $query = PayoutList::orderBy('id','DESC');
        if($request->has('agentId') && $request->agentId != 0 || $request->agentId != NULL){
            $query->where('agent_id',$request->agent_id);
        }
        if($request->has('timePeriod') && $request->timePeriod != 0 || $request->timePeriod != NULL){
            if($request->timePeriod == '3'){
                $query->where('created_at', '>', (new \Carbon\Carbon)->submonths(3) );
            }elseif($request->timePeriod == '6'){
                $query->where('created_at', '>', (new \Carbon\Carbon)->submonths(6) );
            }elseif($request->timePeriod == '9'){
                $query->where('created_at', '>', (new \Carbon\Carbon)->submonths(9) );
            }elseif($request->timePeriod == '12'){
                $query->where('created_at', '>', (new \Carbon\Carbon)->submonths(12) );
            }
        }
        if($request->has('perPage') && $request->perPage != '' && $request->has('pageNo') && $request->pageNo != '' ){
            $payouts = $query->paginate($request->perPage, ['*'], 'page', $request->pageNo);
        }else{
            $payouts = $query->get();
        }
        if(!blank($payouts)){
            $array_push = array();
            foreach($payouts as $payout){
                $array = array();
                $array['payoutAgId']                            =   $payout->id;
                $array['payoutAgentId']                         =   $payout->agent_id;
                $sourcingAgentBean                              = array();
                $agent = SourcingAgent::where('id',$payout->agent_id)->first();
                if(!blank($agent)){
                    $sourcingAgentBean['sourcingAgentId']           = $agent->id;
                    $sourcingAgentBean['sourcingAgentName']         = $agent->name;
                    $sourcingAgentBean['sourcingAgentMobile']       = $agent->phone;
                }
                $array['sourcingAgentBean']                     = $sourcingAgentBean;
                $array['payout_total']                          = PayoutListRecord::where('payout_list_id',$payout->id)->sum("payout");
                $array['start_date']                            = date('d-m-Y',strtotime($payout->start_date));
                $array['end_date']                              = date('d-m-Y',strtotime($payout->end_date));
                $array['IsDisbursed']                           = ($payout->disbursement_date != NULL)?true:false;
                if(!blank($payout->disbursement_date)){
                    $array['DisbursementDate']                  = date('d-m-Y',strtotime($payout->disbursement_date));
                    $array['DisbursementBy']                    = $payout->disbursement_by;
                    $array['DisbursementAmount']                = $payout->disbursement_amount;
                    $array['comment']                           = $payout->comment;
                    $array['payment_type']                      = $payout->payment_type;
                }                 
                $array['created_at']                            = date('d-m-Y',strtotime($payout->created_at));

                array_push($array_push,$array);
            }
            return response()->json([
                'result'            => 200,
                'description'       => "Payout data",
                'payoutBeanJson'    => json_encode($array_push),
                'payoutCount'       => count($array_push),
                'status'	        => 1,
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Covernotes Not Found.'],200);
        }
    }
    public function getPayoutPolicyList(Request $request){
        if($request->has('payoutListId')){
            $query = PayoutListRecord::orderBy('id','DESC');
            $query->whereHas('policy', function($query1) use ($request){
                $query1->where('insurance_type', 1);
            });
            if($request->has('payoutlistId') && $request->payoutListId != ''){
                $query->where('payout_list_id',$request->payoutListId);
            }
            $payout_policies = $query->get();
            $array_push = array();
            $totalCount = 0;
            foreach($payout_policies as $policies){
                $array = array();
                $array['id']                        = $policies->id;
                $array['motorInsurancePolicyId']    = $policies->policy->id;
                $array['netPremiumAmount']          = $policies->policy->net_premium_amount;
                $array['riskStartDate']             = $policies->policy->risk_start_date;
                $array['policyNo']                  = $policies->policy->policy_no;
                $array['tp']                        = $policies->tp;
                $array['od']                        = $policies->od;
                $array['payout']                    = $policies->payout;
                $array['payoutPercentage']          = $policies->percentage;
                array_push($array_push,$array);
                $totalCount++;
            }
            $query_health = PayoutListRecord::orderBy('id','DESC');
            $query_health->whereHas('policy', function($query1) use ($request){
                $query1->where('insurance_type', 2);
            });
            if($request->has('payoutlistId') && $request->payoutListId != ''){
                $query_health->where('payout_list_id',$request->payoutListId);
            }
            $payout_health_policies = $query_health->get();
            $array_push_health = array();
            foreach($payout_health_policies as $policies){
                $array = array();
                $array['id']                        = $policies->id;
                $array['healthInsurancePolicyId']   = $policies->policy->id;
                $array['netPremiumAmount']          = $policies->policy->net_premium_amount;
                $array['riskStartDate']             = $policies->policy->risk_start_date;
                $array['policyNo']                  = $policies->policy->policy_no;
                $array['tp']                        = $policies->tp;
                $array['od']                        = $policies->od;
                $array['payout']                    = $policies->payout;
                $array['payoutPercentage']          = $policies->percentage;
                array_push($array_push_health,$array);
            }
            if(!blank($array_push) && !blank($array_push_health)){
                return response()->json([
                    'result'                            => 200,
                    'description'                       => "Payout data",
                    'status'	                        => 1,
                    'payoutPolicyBeanListJson'          => json_encode($array_push),
                    'healthPayoutPolicyBeanListJson'    => json_encode($array_push_health),
                    'totalCount'                        => count($payout_policies),
                    'healthTotalCount'                  => count($payout_health_policies)
                ],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Payout policy Not Found.'],200);
            }
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Payout policy Not Found.'],200);
        }
    }
    public function updatePayoutPolicyList(Request $request){
        if($request->has('payouts') && !blank($request->payouts)){
            foreach($request->payouts as $payout_data){
                $payout_record = PayoutListRecord::where('id',$payout_data['payoutRecordID'])->first();
                $payout_record->payout          = $payout_data['payout'];
                $payout_record->save();
            }
            return response()->json(['result'=>200,'status'=>1,'description'=>'Save successfully.','message'=> 'Success!.'],200);
        }else{
            return response()->json(['status'=>0,'error'=>'Records Not Found.'], 200);
        }
    }
    public function getPayoutCreationData(Request $request){
        $query = Policy::with('customers')->where('status','!=',2)->where('insurance_type',1);
        if($request->has('agentId') && $request->agentId != ''){
            $query->where('agent',$request->agentId);
        }
        if($request->has('start_date') && $request->has('end_date') && $request->start_date != '' && $request->end_date != ''){
            $start_date = \DateTime::createFromFormat('d/m/Y',$request->start_date);
            $end_date = \DateTime::createFromFormat('d/m/Y',$request->end_date);
            $query->whereBetween('risk_start_date', [$start_date, $end_date]);
        }
        $policies = $query->get();
        $array_push = array();
        if(!blank($policies)){
            foreach($policies as $policy){
                if($policy->insurance_type == 1){
                    $pay = SourcingAgentPayout::where('agent_id',$request->agentId)->where('company',$policy->company)->where('category',$policy->sub_category)->first();
                }else{
                    $pay = SourcingAgentPayout::where('agent_id',$request->agentId)->where('company',$policy->company)->where('category',$policy->health_plan)->first();
                }
                if(!blank($pay)){
                    $percentage = $pay->value;
                    $payout = ($policy->net_premium_amount*$percentage)/100;
                }else{
                    $percentage = 10;
                    $payout = ($policy->net_premium_amount*$percentage)/100;
                }
                $tp = 0;
                $tp_param = PolicyParameter::where('policy_id',$policy->id)->get();
                foreach($tp_param as $param){
                    $tp += (float)$param->value;
                }
                $od = $policy->net_premium_amount - $tp;
                $array = array();
                $array['motorInsurancePolicyId']    = $policy->id;
                $array['netPremiumAmount']          = $policy->net_premium_amount;
                $array['riskStartDate']             = $policy->risk_start_date;
                $array['policyNo']                  = $policy->policy_no;
                $array['tp']                        = $policy->tp;
                $array['od']                        = $policy->od;
                $array['payout']                    = $payout;
                $array['payoutPercentage']          = $percentage;
                array_push($array_push,$array);
            }
        }
        $query = Policy::with('customers')->where('status','!=',2)->where('insurance_type',2);
        if($request->has('agentId') && $request->agentId != ''){
            $query->where('agent',$request->agentId);
        }
        if($request->has('start_date') && $request->has('end_date') && $request->start_date != '' && $request->end_date != ''){
            $start_date =  \DateTime::createFromFormat('d/m/Y',$request->start_date);
            $end_date =  \DateTime::createFromFormat('d/m/Y',$request->end_date);
            $query->whereBetween('risk_start_date', [$start_date, $end_date]);
        }
        $health_policies = $query->get();
        $array_push_health = array();
        if(!blank($health_policies)){
            foreach($health_policies as $policy){
                if($policy->insurance_type == 1){
                    $pay = SourcingAgentPayout::where('agent_id',$request->agentId)->where('company',$policy->company)->where('category',$policy->sub_category)->first();
                }else{
                    $pay = SourcingAgentPayout::where('agent_id',$request->agentId)->where('company',$policy->company)->where('category',$policy->health_plan)->first();
                }
                if(!blank($pay)){
                    $percentage = $pay->value;
                    $payout = ($policy->net_premium_amount*$percentage)/100;
                }else{
                    $percentage = 10;
                    $payout = ($policy->net_premium_amount*$percentage)/100;
                }
                $tp = 0;
                $tp_param = PolicyParameter::where('policy_id',$policy->id)->get();
                foreach($tp_param as $param){
                    $tp += (float)$param->value;
                }
                $od = $policy->net_premium_amount - $tp;
                $array = array();
                $array['healthInsurancePolicyId']   = $policy->id;
                $array['netPremiumAmount']          = $policy->net_premium_amount;
                $array['riskStartDate']             = $policy->risk_start_date;
                $array['policyNo']                  = $policy->policy_no;
                $array['tp']                        = $tp;
                $array['od']                        = $od;
                $array['payout']                    = $payout;
                $array['payoutPercentage']          = $percentage;
                array_push($array_push_health,$array);
            }
        }
        if(!blank($array_push) && !blank($array_push_health)){
            return response()->json([
                'result'                            => 200,
                'description'                       => "Payout Creation data",
                'status'	                        => 1,
                'payoutPolicyBeanListJson'          => json_encode($array_push),
                'healthPayoutPolicyBeanListJson'    => json_encode($array_push_health),
                'totalCount'                        => count($policies),
                'healthTotalCount'                  => count($health_policies)
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Payout policy Not Found.'],200);
        }
    }
    public function generatePayout(Request $request){
        $payout_list = new PayoutList();
        $payout_list->agent_id      = $request->agent;
        $payout_list->start_date    =  \DateTime::createFromFormat('d/m/Y',$request->start_date);
        $payout_list->end_date      =  \DateTime::createFromFormat('d/m/Y',$request->end_date);
        $payout_list->status        = 1;
        $payout_list->save();
        if($payout_list){
            if($request->has('payout') && !blank($request->payout)){
                foreach($request->payout as $payout_data){
                    $payout_record = new PayoutListRecord();
                    $payout_record->payout_list_id  = $payout_list->id;
                    $payout_record->policy_id       = $payout_data['policy_id'];
                    $payout_record->policy_no       = $payout_data['policy_no'];
                    $payout_record->policy_date     = $payout_data['policy_date'];
                    $payout_record->customer        = $payout_data['customer'];
                    $payout_record->net_premium     = $payout_data['net_premium'];
                    $payout_record->od              = $payout_data['od'];
                    $payout_record->tp              = $payout_data['tp'];
                    $payout_record->percentage      = $payout_data['percentage'];
                    $payout_record->payout          = $payout_data['payout'];
                    $payout_record->save();
                }
            }
            return response()->json([
                'result'                            => 200,
                'description'                       => "Payou generated successfully",
                'status'	                        => 1,
                'message'                           => 'Success!',
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Payout Not Found.'],200);
        }
    }
    public function disbursePayout(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        if(!blank($accessToken)){
            $user = User::where('id',$accessToken->user_id)->first();
            $disbursement_by = $user->name;
        }else{
            $disbursement_by = '';
        }
        if(!blank($request->payout_id)){
            $payoutList = PayoutList::where('id',$request->payout_id)->first();
            $payoutList->disbursement_amount    = $request->disbursement_amount;
            $payoutList->disbursement_by        = $disbursement_by;
            $payoutList->payment_type           = $request->payment_type;
            $payoutList->comment                = $request->comment;
            $payoutList->disbursement_date      = Carbon::now();
            $payoutList->save();
            return response()->json([
                'result'                            => 200,
                'description'                       => "Disbursed successfully",
                'status'	                        => 1,
                'message'                           => 'Success!',
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Payout Not Found.'],200);
        }
    }
    public function deletePayout(Request $request, $id = NULL){
        if(!blank($request->id)){ 
            $payout_list_records = PayoutListRecord::where('payout_list_id',$id)->delete();
            $payout_list = PayoutList::where('id',$id)->delete();
            return response()->json([
                'result'                            => 200,
                'description'                       => "Payout deleted successfully",
                'status'	                        => 1,
                'message'                           => 'Success!',
            ],200);
        }else{
            return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Payout Not Found.'],200);
        }
    }
    public function getPayoutReport(Request $request){
        $validator = Validator::make($request->all(), [
           'start_date' => 'required',
           'end_date'   => 'required',
           'agent'      => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $type = 'CSV';
            if($request->has('fileFormat') && $request->fileFormat != ''){
                $type = $request->fileFormat;
            }
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $agent = $request->agent;
            if($type == 'CSV'){
                $path = 'public/downloads/';
                $rand = File::allFiles(public_path('/downloads')); 
                Excel::store(new CustomerPayoutCsvExport($start_date, $end_date, $agent), 'payout-report-'.(count($rand)+1).'.xlsx','storageDisk');
                $file= url('/').'/public/downloads/payout-report-'.(count($rand)+1).'.csv';
                return response()->json([
                    'status'=> 200,
                    'file'=> $file,
                    'file_name' => 'payout_report.csv',
                ]);
            }else{
                $data = [];
                $payouts = PayoutList::with('agents')->where('agent_id',$request->agent)->whereBetween('created_at', [$request->start_date, $request->end_date])->get();
                $records = [];
                foreach($payouts as $payout){
                    if(!blank($payout->disbursement_amount)){
                        $status = 'Paid';
                    }else{
                        $status = 'Unpaid';
                    }
                    $records[] = [
                        'date'=>date('d/m/Y',strtotime($payout->created_at)),
                        'agent'=>$payout->agents->name,
                        'policy'=>PayoutListRecord::where('payout_list_id',$payout->id)->count(),
                        'amount'=>PayoutListRecord::sum('payout'),
                        'status'=> $status
                    ];
                }
                view()->share(['records'=>$records,'start_date'=>$request->start_date,'end_date'=>$request->end_date]);
                $pdf = PDF::loadView('admin.payout.payout_pdf_report', $records)->setPaper('a4', 'landscape');
                // return view('admin.payout.payout_pdf_report');
                // return $pdf->download('payout-report.pdf');
                // $pdf = PDF::loadView('admin.reports.claim_pdf_report', $records)->setPaper('a4', 'landscape');
                // return view('admin.payout.payout_pdf_report');
                $path = 'public/downloads/';
                // $rand = rand(1,100);
                $rand = File::allFiles(public_path('/downloads')); 
                $pdf->save($path  . 'payout-report-'.(count($rand)+1).'.pdf');
                $file= url('/').'/public/downloads/payout-report-'.(count($rand)+1).'.pdf';
                return response()->json([
                    'status'=> 200,
                    'file'=> $file,
                    'file_name' => 'payout_report.pdf',
                ]);
            }
        }
    }
}
