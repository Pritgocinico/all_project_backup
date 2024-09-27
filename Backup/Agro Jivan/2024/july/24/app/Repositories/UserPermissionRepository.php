<?php

namespace App\Repositories;

use App\Interfaces\UserPermissionRepositoryInterface;
use App\Models\UserPermission;

class UserPermissionRepository implements UserPermissionRepositoryInterface 
{
    
    public function storeEmployeePermission($data){
        return UserPermission::create($data);
    }

    public function getAllUserPermission($id){
        return UserPermission::with(['permissionDetail'=> function($query) use($id){
            $query->where('user_id',$id);
        }])->select('feature')->groupBy('feature')->get();
    }

    public function getDetailByUserId($id){
        return UserPermission::where('user_id',$id)->get();
    }

    public function update($where,$update){
        return UserPermission::where($where)->update($update);
    }
}