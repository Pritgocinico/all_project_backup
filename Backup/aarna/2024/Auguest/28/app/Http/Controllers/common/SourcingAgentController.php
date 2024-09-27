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
use App\Models\SourcingAgent;
use App\Models\SourcingAgentPayout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SourcingAgentController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function sourcingAgents(){
        $team_leads = User::where('team_lead',1)->orderBy('id','Desc')->get();
        $agents = SourcingAgent::orderBy('id','Desc')->get();
        $page  = 'Sourcing Agents';
        $icon  = 'staff.png';
        // if(Auth::user()->role == 1){
            return view('admin.agents.agents',compact('team_leads','page','icon','agents'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addSourcingAgent(Request $request){
        $page  = 'Add Sourcing Agents';
        $icon  = 'staff.png';
        $team_leads = User::where('team_lead',1)->orderBy('id','Desc')->get();
        return view('admin.agents.add_agent',compact('page','icon','team_leads'));
    }
    public function addAgentData(Request $request){
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required',
            'email'         => 'required|unique:users'
        ]);
        if($request->customer == "on"){
            $customer = 1;
        }else{
            $customer = 0;
        }
        if($request->team_lead == "on"){
            $team_lead = 1;
        }else{
            $team_lead = 0;
        }
        $SourcingAgent = new SourcingAgent();
        $SourcingAgent->name        = $request->first_name.' '.$request->last_name;
        $SourcingAgent->first_name  = $request->first_name;
        $SourcingAgent->last_name   = $request->last_name;
        $SourcingAgent->phone       = $request->phone;
        $SourcingAgent->email       = $request->email;
        $SourcingAgent->team_lead   = $request->team_lead;
        $SourcingAgent->customer    = $customer;
        $SourcingAgent->status      = 1;
        $insert                     = $SourcingAgent->save();
        $randomNumber = Str::random(9);
        if($customer != 1){
            $user = new User();
            $user->name         = $request->first_name.' '.$request->last_name;
            $user->first_name   = $request->first_name;
            $user->last_name    = $request->last_name;
            $user->phone        = $request->phone;
            $user->email        = $request->email;
            $user->role         = 3;
            $user->team_lead    = $team_lead;
            $user->password     = Hash::make($randomNumber);
            $user->status       = 1;
            $insert             = $user->save();

            $role   =   new RoleUser;
            $role->user_id  = $user->id;
            $role->role_id  = 3;
            $ins            = $role->save();

            $SourcingAgent = SourcingAgent::where('id',$SourcingAgent->id)->first();
            $SourcingAgent->user_id = $user->id;
            $SourcingAgent->save();
            
            $email = $user->email;
            $data = [
                'username' => $user->name,
                'password' => $randomNumber,
                'email' => $user->email,
            ];
            Mail::send('emails.userwelcome', $data, function($message) use ($email) {
                $message->from('info@aarnainsuranceservices.com', 'Aarna Insurance Services')
                        ->to($email)
                        ->subject('Aarna Insurance Services : Welcome Email');
            });
            
        }else{

        }
        if($insert){
            return redirect()->route('admin.sourcing.agents')->with('success', 'Agent Added Successfully.');
        }else{
            Session::flash('alert','Something Went Wrong.');
            return redirect()->route('admin.add.agent');
        }
    }
    public function editAgent(Request $request,$id = NULL){
        $page  = 'Edit Sourcing Agents';
        $icon  = 'staff.png';
        $agent = SourcingAgent::where('id',$id)->first();
        $team_leads = User::where('team_lead',1)->orderBy('id','Desc')->get();
        return view('admin.agents.edit_agent',compact('page','icon','agent','team_leads'));
    }
    public function updateAgent(Request $request){
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required',
            'email'         => 'required',
        ]);
        // if($request->customer == "on"){
        //     $customer = 1;
        // }else{
        //     $customer = 0;
        // }
        if($request->customer){
            $customer = 1;
        }else{
            $customer = 0;
        }
        if($request->team_lead == "on"){
            $team_lead = 1;
        }else{
            $team_lead = 0;
        }
        if($request->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        $SourcingAgent = SourcingAgent::where('id',$request->id)->first();
        $SourcingAgent->name        = $request->first_name.' '.$request->last_name;
        $SourcingAgent->first_name  = $request->first_name;
        $SourcingAgent->last_name   = $request->last_name;
        $SourcingAgent->phone       = $request->phone;
        $SourcingAgent->email       = $request->email;
        $SourcingAgent->team_lead   = $request->team_lead;
        $SourcingAgent->customer    = $customer;
        $SourcingAgent->status      = $status;
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
            $user->status       = $status;
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
        if($insert){
            return redirect()->route('admin.sourcing.agents')->with('success', 'Agent Updated Successfully.');
        }else{
            Session::flash('alert','Something Went Wrong.');
            return redirect()->route('admin.edit.agent');
        }
    }
    public function deleteAgent($id){
        $agent = SourcingAgent::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Sourcing Agent';
        $log->log       = 'Sourcing Agent ('.$agent->name .') Deleted';
        $log->save();
        if($agent->customer == 0){
            $usr = User::where('id',$agent->user_id)->delete();
        }
        $agent->delete();
        return 1;
    }
    public function AgentPayout(Request $request, $id = NULL){
        $page  = 'Sourcing Agent Payout';
        $icon  = 'staff.png';
        $companies = Company::where('status',1)->get();
        $sub_categories = Category::where('parent','!=',0)->orderBy('id','Desc')->get();
        $payouts_data = SourcingAgentPayout::where('agent_id',$id)->with('companies')->get();
        $payouts = [];
        if(!blank($payouts_data)){
            foreach($payouts_data as $data){
                $payouts[$data->companies->name][] = $data;
                $payouts[$data->companies->name]['companies'] = $data->companies;
            }
        }
        return view('admin.agents.payout',compact('payouts_data','page','icon','companies','sub_categories','id','payouts'));
    }
}
