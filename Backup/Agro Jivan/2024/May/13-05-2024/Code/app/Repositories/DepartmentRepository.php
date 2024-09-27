<?php

namespace App\Repositories;

use App\Interfaces\DepartmentRepositoryInterface;
use App\Models\Department;
use App\Models\UserDepartment;

class DepartmentRepository implements DepartmentRepositoryInterface 
{
    public function store($data){
        return Department::create($data);
    }

    public function getAllDepartment($search){
        return Department::when($search,function($query) use($search){
            $query->where('department_name', 'like', '%'.$search.'%');
        })->latest()->paginate(15);
    }

    public function getDetailById($id){
        return Department::where('id',$id)->first();
    }

    public function update($data,$where){
        return Department::where($where)->update($data);
    }

    public function delete($id){
        return Department::where('id',$id)->delete();
    }

    public function getAllDepartmentWithPaginate(){
        return Department::latest()->get();
    }

    public function storeDepartmentUser($data){
        return UserDepartment::create($data);
    }

    public function deleteUserDepartment($id){
        return UserDepartment::where('user_id',$id)->delete();

    }
}