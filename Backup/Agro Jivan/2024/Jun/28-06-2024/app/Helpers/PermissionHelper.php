<?php

namespace App\Helpers;

use App\Models\UserPermission;

class PermissionHelper
{
    public static function checkPermission($name)
    {
        if(Auth()->user() == null){
            return false;
        }
        if(Auth()->user()->id == 1){
            return true;
        }
        $getPermission = UserPermission::where('user_id',Auth()->user()->id)->where('capability',$name)->first();
        if(isset($getPermission)){
            if($getPermission->value == 1){
                return true;
            }
            return false;
        }
        return false;
    }
    public static function checkPermissionById($id,$name)
    {
        if(Auth()->user() == null){
            return false;
        }
        if(Auth()->user()->id == 1){
            return true;
        }
        $getPermission = UserPermission::where('user_id',$id)->where('capability',$name)->first();
        if(isset($getPermission)){
            if($getPermission->value == 1){
                return true;
            }
            return false;
        }
        return false;
    }
}