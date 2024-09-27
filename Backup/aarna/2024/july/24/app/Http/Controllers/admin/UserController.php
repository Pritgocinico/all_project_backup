<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\Log;
use Carbon\Carbon;
use App\Models\RoleUser;
use App\Models\Setting;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function users(Request $request){
        $page = 'Users';
        $icon = 'user.png';
        $users = User::whereIn('role',[2,1])->whereNotIn('id',[Auth::user()->id,1])->get();
        return view('admin.users.users',compact('icon','page','users'));
    }
    public function addUser(Request $request){
        $page = 'Add New User';
        $icon = 'user.png';
        return view('admin.users.add_user',compact('page','icon'));
    }
    public function addUserData(Request $request){
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required|unique:users,phone',
            'email'         => 'required|unique:users,email',
            'password'      => 'required',
        ]);
        if($request->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        if($request->team_lead == "on"){
            $team_lead = 1;
        }else{
            $team_lead = 0;
        }
        $user = new User();
        $user->name         = $request->first_name.' '.$request->last_name;
        $user->first_name   = $request->first_name;
        $user->last_name    = $request->last_name;
        $user->phone        = $request->phone;
        $user->mobile       = $request->mobile;
        $user->email        = $request->email;
        $user->role         = $request->user_type;
        $user->team_lead    = $team_lead;
        $user->password     = Hash::make($request->password);
        $user->status       = 1;
        $insert             = $user->save();

        $role   =   new RoleUser;
        $role->user_id  = $user->id;
        $role->role_id  = $request->user_type;
        $ins            = $role->save();

        $user_permission = [
            [
                'feature'       =>  'insurance_company',
                'capability'    =>  'company-view-global',
                'value'         =>  '1'
            ],
            [
                'feature'       =>  'insurance_company',
                'capability'    =>  'company-create-global',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'insurance_company',
                'capability'    =>  'company-edit-global',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'customer',
                'capability'    =>  'customer-global-view',
                'value'         =>  '1'
            ],
            [
                'feature'       =>  'customer',
                'capability'    =>  'customer-create',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'customer',
                'capability'    =>  'customer-edit',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'sourcing_agent',
                'capability'    =>  'agent-own-view',
                'value'         =>  '1'
            ],
            [
                'feature'       =>  'sourcing_agent',
                'capability'    =>  'agent-create',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'sourcing_agent',
                'capability'    =>  'agent-edit',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'health_plan',
                'capability'    =>  'plan-global-view',
                'value'         =>  '1'
            ],
            [
                'feature'       =>  'health_plan',
                'capability'    =>  'plan-create',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'health_plan',
                'capability'    =>  'plan-edit',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'covernote',
                'capability'    =>  'covernote-own-view',
                'value'         =>  '1'
            ],
            [
                'feature'       =>  'covernote',
                'capability'    =>  'covernote-create',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'covernote',
                'capability'    =>  'covernote-edit',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'covernote',
                'capability'    =>  'covernote-delete',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'policy',
                'capability'    =>  'policy-own-view',
                'value'         =>  '1'
            ],
            [
                'feature'       =>  'policy',
                'capability'    =>  'policy-create',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'policy',
                'capability'    =>  'policy-edit',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'policy',
                'capability'    =>  'policy-delete',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'policy',
                'capability'    =>  'renew-to-covernote',
                'value'         =>  '0'
            ],
            [
                'feature'       =>  'cancel_policy',
                'capability'    =>  'cancel_policy-own-view',
                'value'         =>  '1'
            ],
            [
                'feature'       =>  'reports',
                'capability'    =>  'reports-view',
                'value'         =>  '0'
            ],
        ];

        if($insert){
            foreach($user_permission as $data){
                $perm = new UserPermission();
                $perm->feature      = $data['feature'];
                $perm->capability   = $data['capability'];
                $perm->value        = $data['value'];
                $perm->user_id  = $user->id;
                $perm->save();
            }
            return redirect()->route('admin.users')->with('success', 'User Added Successfully.');
        }else{
            Session::flash('alert','Something Went Wrong.');
            return redirect()->route('admin.add.user');
        }
    }
    public function editUser(Request $request, $id = NULL){
        $page = 'Edit User';
        $icon = 'user.png';
        $user = User::where('id',$id)->first();
        return view('admin.users.edit_user',compact('page','icon','user'));
    }
    public function updateUser(Request $req){
        $req->validate([
            'first_name'          => 'required',
            'last_name'           => 'required',
            'email'               => 'required',
            'phone'               => 'required',
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        if($req->team_lead == "on"){
            $team_lead = 1;
        }else{
            $team_lead = 0;
        }
        $user = User::where('id',$req->user_id)->first();
        $user->name         = $req->first_name.' '.$req->last_name;
        $user->first_name   = $req->first_name;
        $user->last_name    = $req->last_name;
        $user->phone        = $req->phone;
        $user->mobile       = $req->mobile;
        $user->email        = $req->email;
        $user->role         = $req->user_type;
        $user->team_lead    = $team_lead;
        if($req->has('password') && $req->password != ''){
            $user->password = Hash::make($req->password);
        }
        $user->status   = $status;
        $insert         = $user->save();
        return redirect()->route('admin.users')->with('success', 'User Updated Successfully.');
    }
    public function deleteUser($id){
        $user = User::where('id',$id)->first();
        $user->delete();
        return 1;
    }
    public function userPermission(Request $request, $id = NULL){
        $user = User::where('id',$id)->first();
        $user_permissions    = UserPermission::where('user_id',$id)->get();
        $page = 'Users Permissions';
        return view('admin.users.permissions',compact('user_permissions','user', 'page'));
    }
    public function updatePermission(Request $req){
        $permissions = UserPermission::where('user_id',$req->user_id)->get();
        if(count($permissions) > 0){
            $permission=array();
            if(count($permissions) > 0){
                foreach($permissions as $per){
                    $permission[$per->capability]=$per->value;
                }
            }
            foreach($req->permission as $key=>$value){
                foreach($value as $key1=>$val1){
                    $i = 0;
                    foreach($permission as $per_key=>$per_val){
                        if($key1 == $per_key){
                            $perm = UserPermission::where(['capability'=>$key1,'user_id'=>$req->user_id])->first();
                            if($val1 == "on"){
                                $val1 = 1;
                            }
                            $perm->value          = $val1;
                            $perm->save();
                            $i++;
                        }
                    }
                    if($i == 0){
                        if($val1 == "on"){
                            $val1 = 1;
                        }
                        $permission1 = new UserPermission();
                        $permission1->user_id        = $req->user_id;
                        $permission1->feature        = $key;
                        $permission1->capability     = $key1;
                        $permission1->value          = $val1;
                        $permission1->save();
                    }
                }
            }

        }
        else{
            foreach($req->permission as $key=>$value){
                foreach($value as $key1=>$val1){
                    if($val1 == "on"){
                        $val1 = 1;
                    }
                    $permission = new UserPermission;
                    $permission->user_id    = $req->user_id;
                    $permission->feature        = $key;
                    $permission->capability     = $key1;
                    $permission->value          = $val1;
                    $permission->save();
                }
            }
        }
        return redirect()->route('user.permission',$req->user_id)->with('success', 'Permission Updated Successfully.');
    }
}
