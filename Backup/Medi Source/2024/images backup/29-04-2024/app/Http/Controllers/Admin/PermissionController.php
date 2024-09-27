<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\Permission;
use App\Models\Setting;
use App\Models\Role;
use App\Models\UserPermission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }
    public function index(){
        $roleList = Role::where('id','!=',1)->get();
        return view('admin.permission.index',compact('roleList'));
    }

    public function create(Request $request){
        return view('admin.permission.create');
    }

    public function store(Request $request){
        $request->validate([
            'permission_name' => 'required',
        ]);
        $insert = Permission::create(['permission_name' => $request->permission_name]);
        if($insert){
            $roleList = Role::where('id','!=',1)->get();
            foreach($roleList as $role){
                $data['role_id'] = $role->id;
                $data['permission_id'] = $insert->id;
                UserPermission::create($data);
            }
            return redirect()->route('permission.index')->with('success', 'Permission Added successfully');
        }
        return redirect()->route('permission.create')->with('error', 'Something went to Wrong.');
    }
    public function edit($id = null){
        $role = Role::where('id',$id)->first();
        $userPermissionList = UserPermission::with('permissionName')->where('role_id',$id)->get();
        return view('admin.permission.edit',compact('userPermissionList','role'));
    }

    public function update(Request $request){
        $permissionList = $request->permission_list;
        $permissionsValue = array_values($permissionList);
        $userPermissionList = UserPermission::with('permissionName')->where('role_id',$request->role_id)->get();
        foreach($userPermissionList as $userPermission){
            if(in_array($userPermission->id,$permissionsValue)){
                $where['permission_id'] = $userPermission->permission_id;
                $where['role_id'] = $request->role_id;
                $update['status'] = '1';
            } else {
                $where['permission_id'] = $userPermission->permission_id;
                $where['role_id'] = $request->role_id;
                $update['status'] = '0';
            }
            UserPermission::where($where)->update($update);
        }
        return redirect()->route('permission.index')->with('success', 'Permission Updated successfully');
    }
    
}
