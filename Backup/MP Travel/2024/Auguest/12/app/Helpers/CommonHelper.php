<?php

namespace App\Helpers;

use App\Models\Role;

class CommonHelper
{
    public static function getUserRoleName($id){
        if($id == null){
            return "";
        }
        $roleData = Role::find($id);
        return $roleData->name;
    }
    public static function getImageUrl($image)
    {
        $imageUrl = asset('assets/img/user/user.jpg');
        if ($image !== null) {
            $imageUrl = asset('storage/' . $image);
        }
        return $imageUrl;
    }

    public static function getInitials($string){
        if($string == null){
            return "";
        }
        $words = explode(" ", $string);
        $initials = "";

        foreach ($words as $word) {
            $initials .= strtoupper($word[0]);
        }

        return $initials;
    }
}
