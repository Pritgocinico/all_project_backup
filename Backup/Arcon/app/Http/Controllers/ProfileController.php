<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\Log;
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\MatchOldPassword;
use Redirect;
use Notification;
use App\Notifications\Notifications;
class ProfileController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        $notification = $user->unreadNotifications;
        view()->share('notifications', $notification);
        view()->share('setting', $setting);
    }
    
    public function save_profile(Request $req){
        $req->validate([
            'name'          => 'required',
            'email'         => 'required|unique:users,email,' . $req->hidden_id,
            // 'phone'         => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);
       
        if($req->has('image') && $req->file('image') != null){
            $image = $req->file('image');
            $destinationPath = 'public/settings/';
            $rand=rand(1,100);
            $docImage = date('YmdHis'). "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $docImage);
            $img=$docImage;
        }else{
            $img=$req->uploded_image;
        }
        
        $user = User::where('id',$req->hidden_id)->first();
        $user->name             = $req->name;
        $user->email            = $req->email;
        $user->phone            = $req->phone;
        // $user->address          = $req->address;
        $user->image            = $img;
        $insert=$user->save();
        
        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Profile';
        $log->log       = 'Profile Updated';
        $log->save();
        
        // if($user->role==3){
        //     $customer = Customer::where('user_id',$req->hidden_id)->first();
        //     $customer->company      = $req->name;
        //     $customer->email        = $req->email;
        //     $customer->phone        = $req->phone;
        //     $insert=$customer->save();
        // }
        return Redirect::back();
        // return redirect()->route('edit.profile');
    }
    public function change_password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        $user1 = User::where('id',$request->user_id)->first();
        $user1->password = Hash::make($request->new_password);
        $user1->save();
        // User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Change Password';
        $log->log       = 'Password Changed Successfully.';
        $log->save();
        Auth::logout();
        return redirect('login')->with('message', 'Your password has been changed!');
    }
}
