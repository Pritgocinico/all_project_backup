<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Setting;

class AdminController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
   
        view()->share('setting', $setting);
    }

    public function dashboard(Request $req){
        $page = 'Admin Dashboard';
        $icon = 'dashboard.png';
        return view('admin.dashboard',compact('page','icon'));
    }
    public function logs(){
        $logs = Log::orderBy('id','Desc')->get();
        $page       = 'Logs';
        $icon       = 'logs.png';
        return view('admin.logs.logs',compact('logs','page','icon'));
    }
    public function edit_profile(){
        $userId = Auth::check() ? Auth::id() : true;
        $user=User::where('id',$userId)->first();
        $page       = 'Profile';
        $icon       = 'profile.png';
        if(Auth::user()->role == 1){
            return view('admin/profile/edit_profile',compact('user','page','icon'));
        }else{
            return view('agent/profile/edit_profile',compact('user','page','icon'));
        }
    }
    public function view_profile(){
        $userId     = Auth::check() ? Auth::id() : true;
        $user       = User::where('id',$userId)->first();
        $page       = 'Profile';
        $icon       = 'profile.png';
        if(Auth::user()->role == 1){
            return view('admin/profile/view_profile',compact('user','page','icon'));
        }else{
            return view('agent/profile/view_profile',compact('user','page','icon'));
        }
    }
}
