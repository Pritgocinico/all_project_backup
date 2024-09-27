<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Business;
use App\Models\UserEmailSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Nnjeim\World\World;

class EmailSettingsController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function emailSettings(Request $request){
        $page = 'Email Settings';
        $icon = '';
        if(Auth::user()->business != 0){
            $business = Business::where('id',Auth::user()->business)->first();
        }else{
            $business = Business::where('client_id',Auth::user()->id)->where('status',1)->first();
        }
        $emails = UserEmailSetting::where('user_id',$business->id)->get();
        return view('client.email_settings.email_settings',compact('icon','page','emails','business'));
    }
    public function addEmail(Request $request){
        $page = 'Email Settings';
        $icon = '';
        $action_states = World::timezones	();
        if ($action_states->success) {
            $timezones	 = $action_states->data;
        }else{
            $timezones	 = '';
        }
        if(Auth::user()->business != 0){
            $business = Business::where('id',Auth::user()->business)->first();
        }else{
            $business = Business::where('client_id',Auth::user()->id)->where('status',1)->first();
        }
        return view('client.email_settings.add_email',compact('page','icon','timezones','business'));
    }
    public function addEmailData(Request $request){
        $request->validate([
            'name'          => 'required',
            'delay_days'    => 'required',
            'subject'       => 'required',
            'email_html'    => 'required',
            'from_name'     => 'required',
            'from_email'    => 'required',
            'timezone'      => 'required',
        ]);

        $email = new UserEmailSetting();
        $email->user_id     = Auth::user()->business;
        $email->email_name  = $request->name;
        $email->delay_days  = $request->delay_days;
        $email->subject     = $request->subject;
        $email->email_html  = $request->email_html;
        $email->from_name   = $request->from_name;
        $email->from_email  = $request->from_email;
        $email->reply_to    = $request->reply_to;
        $email->timezone    = $request->timezone;
        $email->save();

        return redirect()->route('client.email.settings');
    }
    public function editEmail(Request $request, $id = NULL){
        $page = 'Email Settings';
        $icon = '';
        $action_states = World::timezones	();
        if ($action_states->success) {
            $timezones	 = $action_states->data;
        }else{
            $timezones	 = '';
        }
        $email = UserEmailSetting::where('id',$id)->first();
        if(Auth::user()->business != 0){
            $business = Business::where('id',Auth::user()->business)->first();
        }else{
            $business = Business::where('client_id',Auth::user()->id)->where('status',1)->first();
        }
        return view('client.email_settings.edit_email',compact('page','icon','email','timezones','business'));
    }
    public function updateEmail(Request $request){
        $request->validate([
            'name'          => 'required',
            'delay_days'    => 'required',
            'subject'       => 'required',
            'email_html'    => 'required',
            'from_name'     => 'required',
            'from_email'    => 'required',
            'timezone'      => 'required',
        ]);

        $email = UserEmailSetting::where('id',$request->id)->first();
        $email->email_name  = $request->name;
        $email->delay_days  = $request->delay_days;
        $email->subject     = $request->subject;
        $email->email_html  = $request->email_html;
        $email->from_name   = $request->from_name;
        $email->from_email  = $request->from_email;
        $email->reply_to    = $request->reply_to;
        $email->timezone    = $request->timezone;
        $email->save();

        return redirect()->route('client.email.settings');
    }
    public function deleteEmail($id){
        $user = UserEmailSetting::where('id',$id)->first();
        $user->delete();
        return redirect()->route('client.email.settings');
    }
}
