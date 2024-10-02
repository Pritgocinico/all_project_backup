<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Nnjeim\World\World;
use Nnjeim\World\WorldHelper;

class UserController extends Controller
{
    protected $world;
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function users(WorldHelper $world ,Request $req){
        $this->world = $world;
        $action = $this->world->cities([
            'filters' => [
                'state_id' => 1650,
            ],
        ]);
        if ($action->success) {
            $cities = $action->data;
        }   
        $page = 'Users';
        $icon = 'user.png';
        $users = User::where('role','!=',1)->get();
        // print_r($cities);
        // exit;
        return view('admin.users.users',compact('page','icon','users','cities'));
    }
    public function addUser( Request $request){
       
        $page = 'Users';
        $icon = 'user.png';
        $users = User::where('role','!=',1)->get();
        return view('admin.users.add_user',compact('page','icon','users'));
    }
    public function addUserData(Request $request, $id = NULL){
        // print_r($request->all());exit;
        $request->validate([
            'name'          => 'required',
            // 'phone'         => 'required',
            // 'email'         => 'required',
            // 'address'       => 'required',
            'city'          => 'required',
            // 'zipcode'       => 'required',
            // 'password'      => 'required',
            // 'role'          => 'required|not_in:0',
        ]);
        
        $user = new User();
        $user->name         = $request->name;
        $user->phone        = $request->phone;
        $user->email        = $request->email;
        $user->address      = $request->address;
        $user->city         = $request->city;
        $user->zipcode      = $request->zipcode;
        $user->role         = $request->role;
        $user->password     = Hash::make($request->password);
        $user->status       = 1;
        $insert             = $user->save();

        $role   =   new RoleUser;
        $role->user_id  = $user->id;
        $role->role_id  = $request->role;
        $ins            = $role->save();
        if($insert){
            if(!blank($id) && $id != ''){
                return response()->json([
                    'user' => $user,
                    'success'=>true,
                ],200);
            }else{
                return redirect()->route('admin.users');
            }
        }else{
            Session::flash('alert','Something Went Wrong.');
            return redirect()->route('admin.add_user');
        }
    }
    public function editUser(WorldHelper $world, Request $request, $id = NULL){
        $this->world = $world;
        $action = $this->world->cities([
            'filters' => [
                'state_id' => 1650,
            ],
        ]);
        if ($action->success) {
            $cities = $action->data;
        }   
        
        $page = 'Users';
        $icon = 'user.png';
        $user = User::where('id', $id)->first();
        return view('admin.users.edit_user', compact('page', 'icon', 'user','cities'));
    }    
    public function updateUser(WorldHelper $world, Request $request, $id){

        $request->validate([
            'name'  => 'required',
            
            'city'    => 'required',
            
        ]);
        
     
        $user = User::find($id);
        $user->name  = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->role  = $request->role;
        $user->address = $request->address;
        $user->city  = $request->city;
        $user->zipcode = $request->zipcode;
    
        // if ($request->has('password')) {
        //     $user->password = Hash::make($request->password);
        // }
        $user->save();

        return response()->json([
            'user' => $user,
            'success'=>true,
        ],200);
    
        // return redirect()->route('admin.users');
    }
    public function getUser(WorldHelper $world ,Request $request){
        $this->world = $world;
       
        $user = User::where('id',$request->id)->first();
        $action = $this->world->cities([
            'filters' => [
                'state_id' => 1650,
            ],
        ]);
        if ($action->success) {
            $cities = $action->data;
        }   
        return response()->json($user, 200);
    }
    public function deleteuser($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json("success", 200);
    }
    
}
