<?php

namespace App\Helpers;
use App\Models\Permission;
use App\Models\UserPermission;
use Rmunate\Utilities\SpellNumber;

class NumberFormatHelper
{
    public static function convertToWords($amount)
    {
        $textWord =  SpellNumber::value($amount)
            ->locale('en')
            ->currency('Dollars')
            ->fraction('cents')
            ->toMoney();

            // Capitalize all words
            $capitalizedString = ucwords($textWord);
            
            // Define words to exclude
            $excludedWords = ['And'];
            
            // Convert excluded words back to lowercase
            foreach ($excludedWords as $word) {
                $capitalizedString = str_replace(ucwords($word), strtolower($word), $capitalizedString);
            }
            
            return $capitalizedString;
    }

    public static function checkUserPermission($text){
        $roleId = Auth()->user()->role;
        if($roleId == 1){
            return true;
        }
        $permission = Permission::where('permission_name',$text)->first();
        if(isset($permission)){
            $per = UserPermission::where('permission_id',$permission->id)->where('role_id',$roleId)->first();
            if(isset($per) && $per->status == 1){
                return true;
            }
            return false;
        }
        return false;
    }
}
