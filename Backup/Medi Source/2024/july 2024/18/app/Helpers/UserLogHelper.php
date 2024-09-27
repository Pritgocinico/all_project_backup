<?php

namespace App\Helpers;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class UserLogHelper
{

    public static function storeLog($module,$text)
    {
        if (Auth::guard('admin')->user() !== null) {
            $data = [
                'user_id' => Auth::guard('admin')->user()->id,
                'module' =>$module,
                'log' =>$text,
            ];
            Log::create($data);
        }
        return false;
    }
    public static function storeWebLog($module,$text)
    {
        if (Auth::guard('web')->user() !== null) {
            $data = [
                'user_id' => Auth::guard('web')->user()->id,
                'module' =>$module,
                'log' =>$text,
            ];
            Log::create($data);
        }
        return false;
    }
}

