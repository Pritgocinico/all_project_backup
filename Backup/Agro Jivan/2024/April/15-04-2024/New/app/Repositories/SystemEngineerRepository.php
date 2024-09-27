<?php

namespace App\Repositories;

use App\Interfaces\SystemEngineerRepositoryInterface;
use App\Models\User;

class SystemEngineerRepository implements SystemEngineerRepositoryInterface 
{
    public function store($data){
        return User::create($data);
    }

    public function getAllData($status,$role,$search,$type){
        $query = $this->getQuery($status,$role,$search)->latest();
        if($type == 'paginate'){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }
    public function getQuery($status,$role,$search){
        return User::with('roleDetail')->where('role_id',3)
        ->when($status != "",function($query) use($status){
            $query->where('status',$status);
        })
        ->when($role != "",function($query) use($role){
            $query->where('role_id',$role);
        })->when($search,function($query) use($search){
            $query->where('name', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%')
            ->orWhere('phone_number', 'like', '%'.$search.'%');
        });
    }

    public function getDetailById($id){
        return User::where('id',$id)->first();
    }

    public function update($data,$where){
        return User::where($where)->update($data);
    }

    public function delete($id){
        return User::where('id',$id)->delete();
    }

    public function getUserByMobileOrPhone($name){
        return User::where('phone_number', $name)->orWhere('email',$name)->first();
    }

}