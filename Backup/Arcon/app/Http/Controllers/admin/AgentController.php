<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\Log;
use App\Models\State;
use App\Models\City;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\Notifications;

class AgentController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        $notification = $user->unreadNotifications;
        view()->share('notifications', $notification);
        view()->share('setting', $setting);
    }
    public function agents(){
        $users = User::where('role',3)->where('active',1)->orderBy('id','Desc')->get();
        $page  = 'Super Agents';
        $icon  = 'agent.png';
        return view('admin.agent.agents',compact('users','page','icon'));
    }
    public function addAgent(){
        $users  = User::orderBy('id','DESC')->orderBy('id','Desc')->get();
        $states = State::all();
        $page   = 'Super Agents';
        $icon   = 'agent.png';
        return view('admin.agent.add_agent',compact('users','states','page','icon'));
    }
    public function addAgentData(Request $req){
        $req->validate([
            'name'                => 'required',
            'agent_name'          => 'required',
            // 'email'               => 'required|unique:users,email',
            'phone'               => 'required|unique:users,phone',
            // 'state'               => 'required|not_in:0',
            'password'            => 'required',
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        $user = new User();
        $user->name         = $req->name;
        $user->agent_name   = $req->agent_name;
        $user->phone        = $req->phone;
        $user->email        = $req->email;
        $user->headquarter  = $req->headquarter;
        // $user->floor_no     = $req->floor_no;
        // $user->locality     = $req->locality;
        // $user->address      = $req->address;
        // $user->city         = $req->city;
        // $user->state        = $req->state;
        // $user->country      = 'India';
        $user->role         = $req->role;
        $user->password     = Hash::make($req->password);
        $user->status       = $status;
        $insert             = $user->save();

        $role   =   new RoleUser;
        $role->user_id  = $user->id;
        $role->role_id  = $req->role;
        $ins            = $role->save();

        try{
            //send notification to Admin
            $userSchema = User::first();
            $details = [
                'name'  => 'Agent Created.',
                'type'  => 'Agent',
                'body'  => $req->name.' '.'created.',
                'url'   => route('admin.agents'),
            ];
            // Notification::send($userSchema, new Notifications($details));
        }catch(\Exception $e){

        }
        if($insert){
            $user = User::where('id',Auth::user()->id)->first();
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Agents';
            $log->log       = ' Agent ('.$req->name .') created by '.$user->name;
            $log->save();
            return redirect()->route('admin.agents');
        }else{
            Session::flash('alert','Something Went Wrong.');
            return redirect()->route('admin.add_agent');
        }
    }
    public function editAgent($id)
    {
        $user           = User::where('id',$id)->first();
        $page           = 'Super Agents';
        $icon           = 'agent.png';
        return view('admin/agent/edit_agent',compact('page','icon','user'));
    }
    public function updateAgent(Request $req){
        $req->validate([
            'name'                => 'required',
            'phone'               => 'required|unique:users,phone,' . $req->user_id,
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        $user = User::where('id',$req->user_id)->first();
        $user->name         = $req->name;
        $user->agent_name   = $req->agent_name;
        $user->phone        = $req->phone;
        $user->email        = $req->email;
        $user->headquarter  = $req->headquarter;
        $user->role         = $req->role;
        if($req->has('password') && $req->password != ''){
            $user->password = Hash::make($req->password);
        }
        $user->status   = $status;
        $insert         = $user->save();

        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Agents';
        $log->log       = 'Agent ('.$req->name .') Updated by '.$user->name;
        $log->save();
        return redirect()->route('admin.edit_agent',$req->user_id);
    }
    public function deleteAgent($id){
        $user = User::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Agents';
        $log->log       = 'Agent ('.$user->name .') Deleted by '.$user1->name;
        $log->save();
        $user->active = 0;
        $user->save();
        return 1;
    }
    public function change_status(Request $req){
        $status = User::where('id',$req->user)->first();
        $status->status = $req->status;
        $status->save();
        if($req->status == 1){
            $user_status = 'Verified';
        }else{
            $user_status = 'Not Verified';
        }
        try{
            //send notification to Admin
            $userSchema = User::first();
            $details = [
                'name'  => 'User Verified.',
                'type'  => 'User',
                'body'  => $status->name.' '.' verified by '. Auth::user()->name,
                'url'   => route('admin.users'),
            ];
            Notification::send($userSchema, new Notifications($details));
        }catch(\Exception $e){

        }
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Agetns';
        $log->log       = 'Agent status ('.$user_status.') changed by '.Auth::user()->name;
        $log->save();
        return 1;
    }
    public function viewAgent($id = NULL){
        $user = User::where('id',$id)->first();
        $users = User::where('agent',$id)->orderBy('id','Desc')->get();
        $orders = Order::
            join('users','users.id','=','orders.user_id')
            ->where('users.agent', $id)
            ->where('users.role',2)
            ->orderBy('orders.id','Desc')
            ->get('orders.*');
        $page = 'Super Agents';
        $icon = 'agent.png';
        return view('admin.agent.view_agent',compact('user','users','orders','page','icon'));
    }

    public function getStateCities(Request $req){
        $id = $req->id;
        $locations = City::where('state_id',$id)->get();
        // $locations = GeoLocation::where('location_type','DISTRICT')->where('parent_id',$id)->get();
        $html = '';
        if($req->has('select')){
            $select = $req->select;
        }else{
            $select = 0;
        }
        $html .= '<option value="">Select City...</option>';
        foreach($locations as $location)
        {
            if($select == $location->state_id){
                $selected = 'selected';
            }else{
                $selected = '';
            }
            $html .= '<option value="'.$location->id.'" '.$selected.'>'.$location->city.'</option>';
        }
        return $html;
    }
}
