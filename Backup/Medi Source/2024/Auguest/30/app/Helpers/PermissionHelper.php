<?php

namespace App\Helpers;

use App\Models\Permission;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Auth;
class PermissionHelper
{

    public static function checkUserPermission($text)
    {
        if (Auth::guard('admin')->user() !== null) {
            $roleId = Auth::guard('admin')->user()->role;
            $userId = Auth::guard('admin')->user()->id;
            if ($roleId == 1) {
                return true;
            }
            $permission = Permission::where('permission_name', $text)->first();
            if (isset($permission)) {
                $per = UserPermission::where('permission_id', $permission->id)->where('user_id', $userId)->first();
                if (isset($per) && $per->status == 1) {
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }
}
