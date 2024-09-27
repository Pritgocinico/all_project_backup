<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use App\Models\Role;
use App\Models\RoleUser;

class RoleRepository implements RoleRepositoryInterface 
{
    public function getAllRole(){
        return Role::latest()->get();
    }

    public function storeRoleUser($data){
        return RoleUser::create($data);
    }
    public function update($role,$whereRole){
        return RoleUser::where($whereRole)->update($role);
    }
    
    public function getDetailById($id){
        return Role::where('id',$id)->first();
    }
}