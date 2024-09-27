<?php

namespace App\Http\Controllers;
use Nnjeim\World\WorldHelper;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Log;
use App\Models\City;
use Nnjeim\World\World;

class SettingController extends Controller
{
    protected $world;
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function settings(){
        $settings   = Setting::first();
        $page       = 'Settings';
        $icon       = 'setting.png';

        if(Auth::user()->role == 1){
            return view('admin/settings/general_settings',compact('page','icon','settings'));
        }else{
            return view('quotation/settings/general_settings',compact('page','icon','settings'));
        }
    }
    public function company_settings(WorldHelper $world){
        $this->world = $world;
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);
        
        if ($state_action->success) {
            $states = $state_action->data;
        }
        $cities = City::where('state_id', 1650)->get();
        $settings   = Setting::first();
        $page       = 'Company Settings';
        $icon       = 'setting.png';

        if(Auth::user()->role == 1){
            return view('admin/settings/company_settings',compact('page','icon','settings','cities'));
        }else{
            return view('quotation/settings/company_settings',compact('page','icon','settings','cities'));
        }
    }
    public function email_settings(){
        $settings=Setting::first();
        $page       = 'Company Settings';
        $icon       = 'setting.png';

        if(Auth::user()->role == 1){
            return view('admin/settings/email_settings',compact('settings','page','icon'));
        }else{
            return view('quotation/settings/email_settings',compact('settings','page','icon'));
        }
    }
    public function save_general_setting(Request $req){
        $req->validate([
            'site_name'         => 'required',
            'site_url'          => 'required',
        ]);

        if($req->has('logo') && $req->file('logo') != null){
            $image = $req->file('logo');
            $destinationPath = 'public/settings/';
            $rand=rand(1,100);
            $docImage = date('YmdHis'). "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $docImage);
            $img=$docImage;
        }else{
            $img=$req->uploaded_logo;
        }
        if($req->has('favicon') && $req->file('favicon') != null){
            $image1 = $req->file('favicon');
            $destinationPath1 = 'public/settings/';
            $rand1=rand(1,100);
            $docImage1 = date('YmdHis'). "." . $image1->getClientOriginalExtension();
            $image1->move($destinationPath1, $docImage1);
            $img1=$docImage1;
        }else{
            $img1=$req->uploded_favicon;
        }
        $setting = Setting::first();
        $setting->site_name             = $req->site_name;
        $setting->site_url              = $req->site_url;
        $setting->logo                  = $img;
        $setting->favicon               = $img1;
        $setting->date_format           = $req->date_format;
        $setting->wa_message_sent           = $req->status == "on" ? 1 : 0;
        $insert=$setting->save();

        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Settings';
        $log->log       = 'General Settings Updated';
        $log->save();

        // $path = base_path('.env');
        // $key = 'APP_TIMEZONE';
        // if (file_exists($path)) {
        //     file_put_contents($path, str_replace(
        //         $key . '=' . env($key), $key . '=' . $req->timezone, file_get_contents($path)
        //     ));
        // }
        if(Auth::user()->role == 1){
            return redirect()->route('general.setting');
        }else{
            return redirect()->route('quotation_general.setting');
        }
    }
    public function save_company_setting(Request $req){
        $req->validate([
            'company_name'      => 'required',
            // 'address'           => 'required',
            // 'city'              => 'required',
            // 'state'             => 'required',
            // 'postal_code'       => 'required',
            // 'phone'             => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);

        $setting = Setting::first();
        $setting->company_name      = $req->company_name;
        $setting->address           = $req->address;
        $setting->city              = $req->city;
        $setting->state             = $req->state;
        $setting->postal_code       = $req->postal_code;
        $setting->phone             = $req->phone;
        $setting->gst_number        = $req->gst_number;
        $insert=$setting->save();

        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Settings';
        $log->log       = 'Company Settings Updated';
        $log->save();

        if(Auth::user()->role == 1){
            return redirect()->route('company.setting');
        }else{
            return redirect()->route('quotation_company.setting');
        }
    }
    public function save_email_setting(Request $req){
        $req->validate([
            'admin_email'      => 'required',
            'admin_password'   => 'required'
        ]);

        $setting = Setting::first();
        $setting->admin_email      = $req->admin_email;
        $setting->admin_password   = $req->admin_password;
        $insert=$setting->save();

        $path = base_path('.env');
        $key = 'MAIL_USERNAME';
        $key1 = 'MAIL_PASSWORD';
        $key2 = 'MAIL_FROM_ADDRESS';
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $key . '=' . env($key), $key . '=' . $req->admin_email, file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                $key1 . '=' . env($key1), $key1 . '=' . $req->admin_password, file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                $key2 . '=' . env($key2), $key2 . '=' . $req->admin_email, file_get_contents($path)
            ));
        }
        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Settings';
        $log->log       = 'Email Settings Updated';
        $log->save();

        if(Auth::user()->role == 1){
            return redirect()->route('email.setting');
        }else{
            return redirect()->route('quotation_email.setting');
        }
    }

    public function getUserById(Request $req){
        $user_id = $req->user_id;
        $user = User::where('id',$user_id)->first();
        return $user;
    }

    public function termsANDcondition(Request $request){
        $settings   = Setting::first();
        $page       = 'Company Settings';
        $icon       = 'setting.png';

        if(Auth::user()->role == 1){
            return view('admin/settings/termsandcondition',compact('settings','page','icon'));
        }else{
            return view('quotation/settings/termsandcondition',compact('settings','page','icon'));
        }
    }

    public function save_termsANDcondition(Request $request){
        $setting = Setting::first();
        $setting->terms_and_conditions      = $request->terms_and_conditions;
        $setting->save();

        
        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Settings';
        $log->log       = 'Terms and Conditions Updated';
        $log->save();

        if(Auth::user()->role == 1){
            return redirect()->route('termsANDcondition');
        }else{
            return redirect()->route('quotation_email.setting');
        }
    }

    public function privacyPolicies(Request $request){
        $settings   = Setting::first();
        $page       = 'Company Settings';
        $icon       = 'setting.png';

        if(Auth::user()->role == 1){
            return view('admin/settings/privacypolicy',compact('settings','page','icon'));
        }else{
            return view('quotation/settings/privacypolicy',compact('settings','page','icon'));
        }
    }

    public function save_privacyPolicies(Request $request){
        $setting = Setting::first();
        $setting->privacy_policy      = $request->privacy_policy;
        $setting->save();

        
        $user = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Settings';
        $log->log       = 'Privacy Policy Updated';
        $log->save();

        if(Auth::user()->role == 1){
            return redirect()->route('privacyPolicies');
        }else{
            return redirect()->route('privacyPolicies');
        }
    }
    
}
