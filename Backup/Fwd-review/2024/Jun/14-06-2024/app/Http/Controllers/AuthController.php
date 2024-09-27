<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Carbon\Carbon;
use App\Models\RoleUser;
use App\Models\Setting;
use App\Models\Business;
use App\Models\Plan;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Notification;
use App\Notifications\OffersNotification;

class AuthController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function index()
    {
        if (auth()->user()) {
            if (Auth::user()->role == 1) {
                return redirect(route('admin.dashboard'));
            }
        } else {
            return view('auth.login');
        }
        return view('auth.login');
    }
    public function adminLogin(Request $request)
    {
        $currDate = Carbon::now();
        $currentDate = $currDate->format('Y-m-d');

        $input = $request->all();
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = $request->only($fieldType, 'password');
        if (Auth::attempt(array($fieldType => $input['email'], 'password' => $input['password']))) {
            $role = Auth::user()->role;
            $user = Auth::user();
            $user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp()
            ]);
            if ($user->status == 1) {
                Auth::login($user, true);
                $log = new Log();
                $log->user_id = Auth::user()->name;
                $log->module = 'Login';
                $log->log = $user->name . ' Logged in Successfully';
                $log->save();
                if ($role == 1) {
                    return redirect()->intended('admin/dashboard')
                        ->withSuccess('Signed in');
                } elseif ($role == 2) {
                        return redirect()->intended('client/dashboard')->withSuccess('Signed in');

                } else {
                    Session::flash('message', 'Invalid email or password');
                    return redirect("login")->withInput()->withSuccess('Login details are not valid');
                }
            } else {
                Session::flash('message', 'Your account has been deactivated. Please contact with administrator.');
                return redirect("login")->withInput()->withSuccess('Your account has been deactivated. Please contact with administrator.');
            }
        }
        Session::flash('message', 'Invalid email or password');
        return redirect("login")->withInput()->withSuccess('Login details are not valid');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

    public function subscription(Request $request)
    {
        return redirect('/login');
    }

    public function webhook(Request $request)
    {
        dd($request->all());
    }
    public function forgetPassword()
    {
        return view('auth.forget');
    }
    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = str::random(64);
            $resetToken = DB::table('password_reset_tokens')->where('email', $request->email)->first();
            if ($resetToken) {
                DB::table('password_reset_tokens')->where(['email' => $request->email])->update([
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
            } else {
                DB::table('password_reset_tokens')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
            }
            $content = "";
            $email = $user->email;
            $content .= 'Hi ' . $user->first_name . " " . $user->last_name . ',<br><br>';
            $content .= 'You have requested the password reset from FWD Reviews. Please find below password Reset Link to change your password.<br><br><a style="color:blue" href=';
            $content .= url('reset-password/' . $token . "/" . $email);
            $content .= '>Click Here</a><br><br>';
            $content .= 'Best Regards, <br> Fwd Reveiws';
            Mail::send([], [], function ($message) use ($email, $content) {
                $message->from('reviews@reviewmgr.com', 'FWD Reviews')
                    ->to($email)
                    ->subject("Forget Password Mail")
                    ->html($content);
            });
            session()->flash('sent', 'We have e-mailed your password reset link!');
            return back()->with('message', 'We have e-mailed your password reset link!');
        }
    }
    public function resetPassword($token, $email)
    {
        return view('auth.reset', compact('token', 'email'));
    }

    public function submitResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('message', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
        if ($user) {
            DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
        }
        return redirect('/login')->with('message', 'Your password has been changed!');
    }

    public function settingPage(){
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->where('sub_end_date', '>=', date('Y-m-d'))->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('sub_end_date', '>=', date('Y-m-d'))->where('status', 1)->first();
        }
        $page= "Setting";
        $icon= "setting.png";
        $settings = Setting::first();
        $planDetail = Plan::get();
        return view('admin.setting.setting',compact('planDetail','page','icon','business','settings'));
    }

    public function saveSetting(Request $request){
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'api_key' => 'required|exists:settings,api_key',
        ]);
        $settings = Setting::first();
        if($request ->hasFile('logo')){
            $imageName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads/logo'), $imageName);
            $imagePath = 'uploads/logo/' . $imageName;
            $settings->logo = $imagePath;
        }
        if($request ->hasFile('favicon')){
            $imageName = time() . '.' . $request->favicon->extension();
            $request->favicon->move(public_path('uploads/favicon'), $imageName);
            $imagePath = 'uploads/favicon/' . $imageName;
            $settings->favicon = $imagePath;
        }
        $settings->site_name = $request->site_name;
        $settings->site_url = $request->site_url;
        $settings->api_key = $request->api_key;
        $settings->save();
        if($settings) {
            Session::flash('success','Setting Updated Successfully.');
            return redirect()->back(); 
        }
        Session::flash('error','Something Went to Wrong.');
        return redirect()->back(); 
    }
}
