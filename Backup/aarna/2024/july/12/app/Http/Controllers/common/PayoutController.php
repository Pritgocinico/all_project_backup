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
use App\Models\Log;
use App\Models\Setting;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Policy;
use App\Models\SourcingAgent;
use App\Models\PayoutList;
use App\Models\PayoutListRecord;
use App\Models\PolicyParameter;
use App\Models\SourcingAgentPayout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\CustomerPayoutCsvExport;
use App\Exports\CustomerPayoutPDFExport;
use App\Exports\PayoutPolicyExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use PDF;
class PayoutController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function getPayoutCompanyCategory(Request $request, $id = NULL){
        $company_id = $id;
        $company = Company::where('id',$company_id)->first();
        $category = Category::where('parent','!=',0)->orderBy('id','Desc')->select('id','name')->get();
        $plans = Plan::where('company',$company_id)->select('id','name')->get();
        return response()->json(['category'=>$category,'plans'=>$plans,'company'=>$company], 200);
    }
    public function updatePayout(Request $request){
        // echo '<pre>';
        // print_r($request->all());
        // exit;
        if($request->has('company_category')){
            foreach($request->company_category as $key=>$cate){
                $cnt = 0;
                foreach($cate['category'] as $cat_key=>$cat){
                    foreach($cate['payout'] as $payout_key=>$payout){
                        if($cat_key == $payout_key){
                            $hidden_id = 0;
                            if(isset($cate['id'])){
                                foreach($cate['id'] as $key_id=>$h_id){
                                    if($cat_key == $key_id){
                                        $cnt++;
                                        $hidden_id = $h_id;
                                    }
                                }
                            }
                            if($cnt == 0){
                                    $agent_payout = new SourcingAgentPayout();
                            }else{
                                if($hidden_id != 0){
                                    $agent_payout = SourcingAgentPayout::where('id',$hidden_id)->first();
                                }else{
                                    $agent_payout = new SourcingAgentPayout();
                                }
                            }
                            // print_r($hidden_id);
                            // exit;
                            $agent_payout->agent_id = $request->id;
                            $agent_payout->company  = $key;
                            $agent_payout->category = $cat;
                            foreach($cate['type'] as $type_key=>$type){
                                    if($type_key == $cat_key){
                                        $agent_payout->type = $type;
                                    }
                            }
                            $agent_payout->value = $payout;
                            $agent_payout->created_by = Auth::user()->id;
                            $agent_payout->save();
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Payout Updated Successfully.');
    }
    public function deletePayoutRecord(Request $request,$id){
        $payout = SourcingAgentPayout::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Sourcing Agent Payout';
        $log->log       = 'Sourcing agent payout has been deleted';
        $log->save();
        $payout->delete();
        return 1;
    }
    public function payoutList(Request $request){
        $page  = 'Payout List';
        $icon  = 'staff.png';
        $payouts = PayoutList::with('agents')->orderBy('id','DESC')->get();
        return view('admin.agents.payout_list',compact('page','icon','payouts'));
    }
    public function deletePayout(Request $request,$id = NULL){
        $payout = PayoutList::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Payout';
        $log->log       = 'Payout has been deleted';
        $log->save();
        $payout_records = PayoutListRecord::where('payout_list_id',$id)->delete();
        $payout->delete();
        return 1;
    }
    public function createPayout(Request $request){
        $page = 'Create Payout';
        $icon = 'staff.png';
        $agents = SourcingAgent::where('status',1)->get();
        return view('admin.agents.payout_create',compact('page','icon','agents'));
    }
    public function GeneratePayout(Request $request){
        $agent = $request->agent;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $policies = Policy::where('agent',$request->agent)->with('customers')->whereBetween('risk_start_date', [$start_date, $end_date])->where('status','!=',2)->get();
        // print_r($policies);
        $html = '';
        $html .= '<div>';
        $html .= '<h3>Policies Payout</h3>';
        $html .= '<table class="table rwd-table mb-0 w-100 mt-3">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>#</th>';
        $html .= '<th>Policy No</th>';
        $html .= '<th>Insurance Type</th>';
        $html .= '<th>Customer</th>';
        $html .= '<th>Policy Date</th>';
        $html .= '<th>Net Premium</th>';
        $html .= '<th>OD</th>';
        $html .= '<th>TP</th>';
        $html .= '<th>Payout (%)</th>';
        $html .= '<th>Payout</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        if(!blank($policies)){
            $i = 0;
            foreach($policies as $policy){
                $i++;
                if($policy->insurance_type == 1){
                    $pay = SourcingAgentPayout::where('agent_id',$request->agent)->where('company',$policy->company)->where('category',$policy->sub_category)->first();
                }else{
                    $pay = SourcingAgentPayout::where('agent_id',$request->agent)->where('company',$policy->company)->where('category',$policy->health_plan)->first();
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
                if($policy->insurance_type == 1){
                    $insurance_type = 'Motor';
                }else{
                    $insurance_type = 'Health';
                }
                $html .= '<tr>';
                $html .= '<td>#</td>';
                $html .= '<td>'.$policy->policy_no.'<input type="hidden" name="payout['.$i.'][policy_id]" value="'.$policy->id.'"><input type="hidden" name="payout['.$i.'][policy_no]" value="'.$policy->policy_no.'"></td>';
                $html .= '<td>'.$insurance_type.'</td>';
                $html .= '<td>'.$policy->customers->name.'<input type="hidden" name="payout['.$i.'][customer]" value="'.$policy->customers->id.'"></td>';
                $html .= '<td>'.date('d-m-Y',strtotime($policy->risk_start_date)).'<input type="hidden" name="payout['.$i.'][policy_date]" value="'.$policy->risk_start_date.'"></td>';
                $html .= '<td>'.$policy->net_premium_amount.'<input type="hidden" name="payout['.$i.'][net_premium]" value="'.$policy->net_premium_amount.'"></td>';
                $html .= '<td>'.$policy->od.'<input type="hidden" name="payout['.$i.'][od]" value="'.$policy->od.'"></td>';
                $html .= '<td>'.$policy->tp.'<input type="hidden" name="payout['.$i.'][tp]" value="'.$policy->tp.'"></td>';
                $html .= '<td>'.$percentage.'%<input type="hidden" name="payout['.$i.'][percentage]" value="'.$percentage.'"></td>';
                $html .= '<td><input type="number" min="0" value="';
                $html .= $payout;
                $html .= '" class="form-control m-input" name="payout['.$i.'][payout]" step="0.01"></td>';
                $html .= '</tr>';
            }
        }else{
            $html .= '<tr>';
            $html .= '<td colspan="10" class="text-center">Records Not Found.</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $page = 'Payout';
        $icon = 'staff.png';
        $agents = SourcingAgent::where('status',1)->get();
        $data = $request->all();
        return view('admin.agents.payout_generate',compact('html','page','icon','agents','data'));
    }
    public function GeneratePayoutData(Request $request){
        // echo '<pre>';
        // print_r($request->All());
        // exit;
        $payout_list = new PayoutList();
        $payout_list->agent_id      = $request->agent;
        $payout_list->start_date    = date('Y-m-d',strtotime($request->start_date));
        $payout_list->end_date      = date('Y-m-d',strtotime($request->end_date));
        $payout_list->status        = 1;
        $payout_list->save();
        if($request->has('payout') && !blank($request->payout)){
            foreach($request->payout as $payout_data){
                $payout_record = new PayoutListRecord();
                $payout_record->payout_list_id  = $payout_list->id;
                $payout_record->policy_id       = $payout_data['policy_id'];
                $payout_record->policy_no       = $payout_data['policy_no'];
                $payout_record->policy_date     = $payout_data['policy_date'];
                $payout_record->customer        = $payout_data['customer'];
                $payout_record->net_premium     = $payout_data['net_premium'];
                $payout_record->od              = ($payout_data['od'] != '')?$payout_data['od']:0;
                $payout_record->tp              = ($payout_data['tp'] != '')?$payout_data['tp']:0;
                $payout_record->percentage      = $payout_data['percentage'];
                $payout_record->payout          = $payout_data['payout'];
                $payout_record->save();
            }
        }
        return redirect()->route('admin.payout.list');
    }
    public function editPayoutList(Request $request, $id = NULL){
        $page = 'Edit Payout';
        $icon = 'staff.png';
        $agents = SourcingAgent::where('status',1)->get();
        $payouts = PayoutList::where('id',$id)->with('agents')->where('status',1)->first();
        $payout_records = PayoutListRecord::where('payout_list_id',$payouts->id)->get();
        return view('admin.agents.payout_edit',compact('page','icon','payouts','payout_records','agents'));
    }
    public function editPayoutData(Request $request){
        // echo '<pre>';
        // print_r($request->All());
        // exit;
        if($request->has('payout') && !blank($request->payout)){
            foreach($request->payout as $key=>$payout_data){
                $payout_record = PayoutListRecord::where('id',$key)->first();
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
        return redirect()->route('admin.payout.list')->with('success', 'Payout Updated Successfully.');
    }
    public function ViewPayout(Request $request, $id = NULL){
        $page = 'View Payout';
        $icon = 'staff.png';
        $agents = SourcingAgent::where('status',1)->get();
        $payouts = PayoutList::where('id',$id)->with('agents')->where('status',1)->first();
        $payout_records = PayoutListRecord::where('payout_list_id',$payouts->id)->get();
        return view('admin.agents.payout_view',compact('page','icon','payouts','payout_records','agents'));
    }
    public function PayoutReports(Request $request){
        $page = 'Payout Report';
        $icon = 'staff.png';
        $agents = SourcingAgent::where('status',1)->get();
        return view('admin.payout.payout_reports',compact('page','icon','agents'));
    }
    public function downloadCustomerPayoutCSV(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $agent = $request->agent;
        $type = $request->type;
        if($type == 'csv'){
            return Excel::download(new CustomerPayoutCsvExport($start_date, $end_date, $agent), 'payout-report.xlsx');
        }else{
            $data = [];
            $payouts = PayoutList::with('agents')->where('agent_id',$agent)->whereBetween('created_at', [$request->start_date, $request->end_date])->get();
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
            $pdf = PDF::loadView('admin.payout.payout_pdf_report', $records);
            // return view('admin.payout.payout_pdf_report');
            return $pdf->download('payout-report.pdf');
        }
    }
    public function disbursePayout(Request $request){
        $payoutList = PayoutList::where('id',$request->id)->first();
        $payoutList->disbursement_amount    = $request->amount;
        $payoutList->payment_type           = $request->payment_type;
        $payoutList->comment                = $request->comment;
        $payoutList->disbursement_date      = Carbon::now();
        $payoutList->save();
        return redirect()->back();
    }
    public function getPayoutAmount(Request $request, $id = NULL){
        $amount = PayoutListRecord::where('payout_list_id',$id)->sum('payout');
        return response()->json($amount, 200);
    }
    public function downloadPolicyPayout(Request $request){
        $payout_id = $request->payout_id;
        // echo $payout_id;
        // exit;
        $type = $request->type;
        if($type == 'csv'){
            return Excel::download(new PayoutPolicyExport($payout_id), 'payout-report.xlsx');
        }else{
            $data = [];
            $payout_data = PayoutList::with('agents')->where('id',$payout_id)->first();
            $payouts = PayoutListRecord::with('customers')->where('payout_list_id',$payout_id)->get();
            $records = [];
            $i = 0;
            foreach($payouts as $policy){
                $i++;
                $policy_data = Policy::where('id',$policy->policy_id)->first();
                if($policy_data->insurance_type == 1){
                    $i_type = 'Motor Insurance';
                }else{
                    $i_type = 'Health Insurance';
                }
                $records[] = array(
                    'policy_no'=>$policy->policy_no,
                    'type'=>$i_type,
                    'customer'=>$policy->customers->name,
                    'date'=>date('d/m/Y',strtotime($policy->policy_date)),
                    'net_premium'=>$policy->net_premium,
                    'od'=>$policy->od,
                    'tp'=>$policy->tp,
                    'payout'=>$policy->payout
                );
            }
            view()->share(['records'=>$records,'payout_data'=>$payout_data]);
            $pdf = PDF::loadView('admin.payout.payout_policies_pdf_report', $records);
            // return view('admin.payout.payout_policies_pdf_report');
            return $pdf->download('payout-policies-report.pdf');
        }
    }
}
