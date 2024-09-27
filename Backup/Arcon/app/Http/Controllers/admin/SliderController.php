<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Setting;
use App\Models\SliderImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\Notifications;

class SliderController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        $notification = $user->unreadNotifications;
        view()->share('notifications', $notification);
        view()->share('setting', $setting);
    }
    public function banners(){
        $banners = SliderImage::orderBy('id','Desc')->get();
        $page = 'Banners';
        $icon = 'banner.png';
        return view('admin.slider.banners',compact('banners','page','icon'));
    }
    public function store(Request $request)
    {
        $image = $request->file('file');
        $avatarName = $image->getClientOriginalName();
        $destinationPath = 'public/banners/';
        $rand=rand(1,100);
        $docImage = date('YmdHis') . $rand . "." . $image->getClientOriginalExtension();
        $image->move($destinationPath, $docImage);
        $img=$docImage;
        $banner = new SliderImage();
        $banner->image = $img;
        $banner->status = 1;
        $banner->save();
        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Banner';
        $log->log       = ' Banner added by '.$user->name;
        $log->save();
    }
    public function deleteBanner($id){
        $banner = SliderImage::where('id',$id);
       	$banner->delete();
       	$user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Banner';
        $log->log       = ' Banner deleted by '.$user->name;
        $log->save();
        return 1;
    }
}
