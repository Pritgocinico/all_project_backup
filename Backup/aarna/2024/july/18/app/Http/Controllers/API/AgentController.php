<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Category;
use App\Models\Plan;
use App\Models\SourcingAgent;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;

class AgentController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function SourcingAgentsList(Request $request){
        $query = SourcingAgent::orderBy('id','Desc')->where('status',1);
        if($request->has('sourcingAgentId') && $request->sourcingAgentId != ''){
            $query->where('id',$request->sourcingAgentId);
        }
        if($request->has('Name') && $request->Name != ''){
            $query->where('name', 'like', '%'.$request->Name.'%');
        }
        if($request->has('Email') && $request->Email != ''){
            $query->where('email',$request->Email);
        }
        if($request->has('Phone') && $request->Phone != ''){
            $query->where('phone',$request->Phone);
        }
        $agents = $query->get();
        $array_push = array();
        if(!blank($agents)){
            foreach($agents as $agent){
                $array = array();
                $array['sourcingAgentId']           = $agent->id;
                $array['Name']                      = ($agent->name != NULL)?$agent->name:"";
                $array['Email']                     = ($agent->email != NULL)?$agent->email:"";
                $array['Phone']                     = ($agent->phone != NULL)?$agent->phone:"";
                $array['status']                    = ($agent->status != NULL)?(int)$agent->status:0;
                $array['created_at']                = ($agent->created_at != NULL)?$agent->created_at:"";
                array_push($array_push,$array);
            }
            return response()->json([
                'result'            => 200,
                'description'       => 'Sourcing Agent List',
                'status'            => 1,
                'agentListJson'  => json_encode($array_push),
                'agentListCount' => count($agents)
            ],200);
        }else{
             return response()->json(['result'=>200,'description'=>'SourcingAgent Not Found.','status'=>0,'error'=> 'Sourcing Agent Not Found.'],200);
        }
    }
    public function storeSourcingAgent(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name'                 => 'required',
            'last_name'                  => 'required',
            'email'                     => 'required|unique:sourcing_agents,email',
            'phone'                     => 'required|unique:sourcing_agents,phone',
        ]);
        if ($validator->fails()) {
            return response()->json(['result'=>200,'status'=>0,'description'=>'error','message'=>'error','error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $user_id = 0;
            if($request->customer != 1){
                $user = new User();
                $user->name         = $request->first_name.' '.$request->last_name;
                $user->first_name   = $request->first_name;
                $user->last_name    = $request->last_name;
                $user->phone        = $request->phone;
                $user->email        = $request->email;
                $user->role         = 3;
                $user->team_lead    = $team_lead;
                // if(blank($usr)){
                    if($request->has('password') && $request->password != ''){
                        $user->password     = Hash::make($request->password);
                    }
                // }
                $user->status       = 1;
                $insert             = $user->save();
    
                if(blank($usr)){
                    $role   =   new RoleUser;
                    $role->user_id  = $user->id;
                    $role->role_id  = 3;
                    $ins            = $role->save();
                }
                $user_id = $user->id;
            }
            $agent = new SourcingAgent();
            $agent->first_name      = $request->first_name;
            $agent->last_name       = $request->last_name;
            $agent->name            = $request->first_name.' '.$request->last_name;
            $agent->email           = $request->email;
            $agent->phone           = $request->phone;
            $agent->customer        = $request->customer;
            $agent->team_lead       = $request->team_lead;
            $agent->status          = 1;
            $agent->save();

            if($agent){
               
                return response()->json(
                    [
                        'result'        => 200,
                        'description'   => 'Sourcing Agent added successfully.',
                        'status'        => 1,
                        'message'       => 'Success!'
                    ],
                    200
                );
            }else{
                return response()->json(['result'=> 200,'description'=>'Something went wrong.','status'=>0,'error'=>'Something went wrong.'],200);
            }
        }
    }
    public function deleteSourcingAgent(Request $request, $id){
        if(!blank($id)){
            $agent = SourcingAgent::where('id',$id)->first();
            if(!blank($agent)){
                $user = User::first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Sourcing Agent';
                $log->log       = 'Sourcing Agent ('.$agent->name.') Deleted.';
                $log->save();
                $agent->delete();
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
            }
            return response()->json(['result'=>200,'status'=>1,'message'=>'Sourcing Agent deleted successfully!'],200);
        }else{
           return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
        }
    }
    public function updateSourcingAgent(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name'                => 'required',
            'last_name'                 => 'required',
            'email'                     => 'required|unique:sourcing_agents,email',
            'phone'                     => 'required|unique:sourcing_agents,phone',
        ]);
        if ($validator->fails()) {
            return response()->json(['result'=>200,'status'=>0,'description'=>'error','message'=>'error','error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $SourcingAgent = SourcingAgent::where('id',$request->id)->first();
            $SourcingAgent->name        = $request->first_name.' '.$request->last_name;
            $SourcingAgent->first_name  = $request->first_name;
            $SourcingAgent->last_name   = $request->last_name;
            $SourcingAgent->phone       = $request->phone;
            $SourcingAgent->email       = $request->email;
            $SourcingAgent->team_lead   = $request->team_lead;
            $SourcingAgent->customer    = $request->customer;
            $SourcingAgent->status      = 1;
            $insert                     = $SourcingAgent->save();

            if($customer != 1){
                $usr = User::where('id',$SourcingAgent->user_id)->first();
                if(!blank($usr)){
                    $user = User::where('id',$SourcingAgent->user_id)->first();
                }else{
                    $user = new User();
                }
                $user->name         = $request->first_name.' '.$request->last_name;
                $user->first_name   = $request->first_name;
                $user->last_name    = $request->last_name;
                $user->phone        = $request->phone;
                $user->email        = $request->email;
                $user->role         = 3;
                $user->team_lead    = $team_lead;
                // if(blank($usr)){
                    if($request->has('password') && $request->password != ''){
                        $user->password     = Hash::make($request->password);
                    }
                // }
                $user->status       = 1;
                $insert             = $user->save();

                if(blank($usr)){
                    $role   =   new RoleUser;
                    $role->user_id  = $user->id;
                    $role->role_id  = 3;
                    $ins            = $role->save();
                }
                $SourcingAgent = SourcingAgent::where('id',$SourcingAgent->id)->first();
                $SourcingAgent->user_id = $user->id;
                $SourcingAgent->save();
            }else{

            }
            return response()->json(['status'=>1,'message'=>'Sourcing agent updated successfully.'],200);
        }
    }
    public function agentPayoutData(Request $request, $id = NULL){
        $company = Company::where('id',$id)->first();
        $category = Category::where('parent','!=',0)->orderBy('id','Desc')->select('id','name')->get();
        $plans = Plan::where('company',$id)->select('id','name')->get();
        $data = [
            'category'  => $category,
            'plans'     => $plans,
            'company'   => $company
        ];
        return response()->json([
            'result'            => 200,
            'description'       => 'Sourcing Agent Payout Initial List',
            'status'            => 1,
            'agentPayoutInitialData'  => json_encode($data)
        ],200);
    }
    public function updateAgentPayout(Request $request){
        if(!blank($request->payouts)){
            foreach($request->payouts as $payout){
                if(array_key_exists('hidden_id')){
                    $agent_payout = SourcingAgentPayout::where('id',$hidden_id)->first();
                    $agent_payout->agent_id     = $payout->id;
                    $agent_payout->company      = $payout->company;
                    $agent_payout->category     = $payout->category;
                    $agent_payout->type         = $payout->type;
                    $agent_payout->value        = $payout->payout;
                    $agent_payout->save();
                }else{
                    $agent_payout = new SourcingAgentPayout();
                    $agent_payout->agent_id     = $payout->id;
                    $agent_payout->company      = $payout->company;
                    $agent_payout->category     = $payout->category;
                    $agent_payout->type         = $payout->type;
                    $agent_payout->value        = $payout->payout;
                    $agent_payout->created_by   = $request->user_id;
                    $agent_payout->save();
                }
                return response()->json(
                    [
                        'result'        => 200,
                        'description'   => 'Sourcing Agent Payouts added successfully.',
                        'status'        => 1,
                        'message'       => 'Success!'
                    ],
                    200
                );
            }
        }else{
            return response()->json(['result'=>200,'description'=>'SourcingAgent Payouts Not Found.','status'=>0,'error'=> 'Payouts Not Found.'],200);
        }
    }
}
