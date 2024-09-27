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
use App\Models\Setting;
use App\Models\Category;
use App\Models\State;
use App\Models\City;
use App\Models\DealerCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\Notifications;

class UserController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        $notification = $user->unreadNotifications;
        view()->share('notifications', $notification);
        view()->share('setting', $setting);
    }
    public function users(){
        $page  = 'Dealers';
        $icon  = 'clients.png';
        if(Auth::user()->role == 1){
            $users = User::where('role',2)->where('active',1)->orderBy('id','Desc')->get();
            return view('admin.users.users',compact('users','page','icon'));
        }elseif(Auth::user()->role == 3){
            $users = User::where('role',2)->where('active',1)->orderBy('id','Desc')->where('agent',Auth::user()->id)->get();
            return view('agent.users.users',compact('users','page','icon'));
        }else{
            return redirect()->route('login');
        }
    }
    public function statement(Request $req){
        $statement  = $req->all();
        $page       = 'Statement';
        $icon       = 'clients.png';
        return redirect()->view('admin.users.view_statement',compact('statement','page','icon'));
    }
    public function addUser(){
        $users = User::orderBy('id', 'DESC')->orderBy('id','Desc')->get();
        $agents = User::where('role',3)->orderBy('id','Desc')->get();
        $states = State::where('country_id',105)->get();
        $categories = Category::all();
        $parent_categories = Category::where('parent','==',0)->get();
        $page  = 'Dealers';
        $icon  = 'clients.png';
        return view('admin.users.add_user',compact('users','agents','states','categories','parent_categories','page','icon'));
    }
    public function addUserData(Request $req){
        // echo '<pre>';
        // print_r($req->All());
        // exit;
        $req->validate([
            'name'                => 'required',
            'phone'               => 'required|unique:users,phone',
            // 'gst_number'          => 'required',
            'password'            => 'required',
            'agent'               => 'required|not_in:0'
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        $user = new User();
        $user->name         = $req->name;
        $user->phone        = $req->phone;
        $user->email        = $req->email;
        $user->gst_number   = $req->gst_number;
        $user->address      = $req->address;
        $user->floor_no     = $req->floor_no;
        $user->locality     = $req->locality;
        $user->city         = $req->city;
        $user->state        = $req->state;
        $user->country      = 'India';
        $user->role         = $req->role;
        $user->agent        = $req->agent;
        $user->password     = Hash::make($req->password);
        $user->status       = $status;
        $user->transport    = $req->transport;
        $insert             = $user->save();
        if($req->has('categories')){
            foreach($req->categories as $category){
                $category1 = new DealerCategory();
                $category1->user_id      = $user->id;
                $category1->category_id  = $category;
                $category1->save();
            }
        }
        $role   =   new RoleUser;
        $role->user_id  = $user->id;
        $role->role_id  = $req->role;
        $ins            = $role->save();

        try{
            //send notification to Admin
            $userSchema = User::first();
            $details = [
                'name'  => 'Dealer Created.',
                'type'  => 'Dealer',
                'body'  => $req->name.' '.'created.',
                'url'   => route('admin.users'),
            ];
            // Notification::send($userSchema, new Notifications($details));
        }catch(\Exception $e){

        }
        if($insert){
            $user = User::where('id',Auth::user()->id)->first();
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Dealers';
            $log->log       = 'Dealer ('.$req->name .') created by '.$user->name;
            $log->save();
            return redirect()->route('admin.users');
        }else{
            Session::flash('alert','Something Went Wrong.');
            return redirect()->route('admin.add_user');
        }
    }
    public function editUser($id)
    {
        $user           = User::where('id',$id)->first();
        $agents         = User::where('role',3)->get();
        $states         = State::where('country_id',105)->get();
        $state          = State::where('name',strtoupper($user->state))->first();
        if(!blank($state)){
            $cities         = City::where('state_id',$state->id)->get();
        }else{
            $cities = [];
        }
        $categories = Category::all();
        $parent_categories = Category::where('parent','==',0)->get();
        $dealer_categories = DealerCategory::where('user_id',$id)->get();
        $page           = 'Dealers';
        $icon           = 'clients.png';
        return view('admin/users/edit_user',compact('page','icon','user','agents','states','cities','categories','parent_categories','dealer_categories'));
    }
    public function updateUser(Request $req){
        $req->validate([
            'name'                => 'required',
            'phone'               => 'required|unique:users,phone,' . $req->user_id,
            'agent'               => 'required|not_in:0',
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        $user = User::where('id',$req->user_id)->first();
        $user->name         = $req->name;
        $user->phone        = $req->phone;
        $user->email        = $req->email;
        $user->role         = $req->role;
        $user->gst_number   = $req->gst_number;
        $user->transport    = $req->transport;
        $user->floor_no     = $req->floor_no;
        $user->locality     = $req->locality;
        $user->city         = $req->city;
        $user->state        = $req->state;
        $user->country      = 'India';
        $user->agent        = $req->agent;
        if($req->has('password') && $req->password != ''){
            $user->password = Hash::make($req->password);
        }
        $user->status   = $status;
        $insert         = $user->save();
        $d_cat = DealerCategory::where('user_id',$req->user_id)->delete();
        if($req->has('categories')){
            foreach($req->categories as $category){
                $category1 = new DealerCategory();
                $category1->user_id      = $user->id;
                $category1->category_id  = $category;
                $category1->save();
            }
        }
        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Dealers';
        $log->log       = 'Dealer ('.$req->name .') Updated by '.$user->name;
        $log->save();
        return redirect()->route('admin.edit_user',$req->user_id);
    }
    public function deleteUser($id){
        $user = User::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Dealers';
        $log->log       = 'Dealer ('.$user->name .') Deleted by '.$user1->name;
        $log->save();
        // $user->active = 0;
        $user->delete();
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
                'name'  => 'Dealer Verified.',
                'type'  => 'Dealer',
                'body'  => $status->name.' '.' verified by '. Auth::user()->name,
                'url'   => route('admin.users'),
            ];
            Notification::send($userSchema, new Notifications($details));
        }catch(\Exception $e){

        }
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Dealers';
        $log->log       = ' Dealer status ('.$user_status.') changed by '.Auth::user()->name;
        $log->save();
        return 1;
    }
    public function change_agent_status(Request $req){
        $status = User::where('id',$req->user)->first();
        $status->agent_status = $req->status;
        $status->save();
        if($req->status == 1){
            $user_status = 'Verified';
        }else{
            $user_status = 'Deverified';
        }
        try{
            //send notification to Admin
            $userSchema = User::first();
            $details = [
                'name'  => 'Dealer Verified.',
                'type'  => 'Dealer',
                'body'  => $status->name.' '.$user_status.' by '. Auth::user()->name,
                'url'   => route('admin.users'),
            ];
            Notification::send($userSchema, new Notifications($details));
        }catch(\Exception $e){

        }
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Dealer';
        $log->log       = 'User status ('.$user_status.') changed by Agent '.Auth::user()->name;
        $log->save();
        return 1;
    }
    public function viewUser($id = NULL){
        $user = User::where('id',$id)->first();
        $orders = Order::where('user_id',$id)->orderBy('id','Desc')->get();
        $cart_items = CartItem::where('user_id',$id)->orderBy('id','Desc')->get();
        $page = 'Dealers';
        $icon = 'clients.png';
        if(Auth::user()->role == 1){
            return view('admin.users.view_user',compact('user','orders','cart_items','page','icon'));
        }else{
          return view('agent.users.view_user',compact('user','orders','cart_items','page','icon'));
        }
    }
    public function getCities($id = NULL){
        $state = State::where('name',$id)->first();
        $cities = City::where('state_id',$state->id)->get();
        $html = '';
        if(!blank($cities)){
            foreach($cities as $city){
                $html .= '<option value="'.$city->city.'">'.$city->city.'</option>';
            }
        }
        return $html;
    }
}
