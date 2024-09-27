<?php

namespace App\Http\Controllers;

use App\Helpers\UserLogHelper;
use App\Models\User;
use App\Models\RoleUser;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();

        view()->share('setting', $setting);
    }


    // public function __construct() {
    //     $setting=Setting::first();
    //     $user = User::first();
    //     view()->share('setting', $setting);
    // }
    public function index()
    {
        if (auth()->user()) {
            if (Auth::user()->role == 1) {
                return redirect()->route('admin.dashboard');
            } else {
                return view('auth.login');
            }
        } else {
            return view('auth.login');
        }
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Prepare credentials for role 1
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentialsRole1 = $request->only($fieldType, 'password');
        $credentialsRole1['role'] = 1;

        // Prepare credentials for role 2
        $credentialsRole2 = $credentialsRole1; // Copy credentials
        $credentialsRole2['role'] = 2;

        // Check if the "Remember Me" checkbox is checked
        $remember = $request->has('remember');

        // Attempt authentication for role 1
        $attemptRole1 = Auth::guard('admin')->attempt($credentialsRole1, $remember);

        // Attempt authentication for role 2
        $attemptRole2 = Auth::guard('admin')->attempt($credentialsRole2, $remember);

        // Check if either attempt succeeds
        if ($attemptRole1 || $attemptRole2) {
            // Authentication successful
            $role = Auth::guard('admin')->user()->role;
            if ($role == 1 || $role == 2) {
                $log = "Admin logged Successfully";
                UserLogHelper::storeLog('login', $log);
                return redirect()->intended('admin/dashboard')->withSuccess('Signed in');
            } else {
                Auth::guard('admin')->logout();
                $log = "Admin Log Out Successfully";
                UserLogHelper::storeLog('Logout', $log);
                Session::flash('message', 'Invalid email or password');
                return redirect("admin")->withInput()->withSuccess('Login details are not valid');
            }
        }

        // Authentication failed
        Session::flash('message', 'Invalid email or password');
        return redirect("admin")->withInput()->withSuccess('Login details are not valid');
    }


    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $role = Auth::guard('web')->user()->role;
            $name = Auth::guard('web')->user()->first_name . " " . Auth::guard('web')->user()->last_name;
            if ($role == 3) {
                Auth::guard('web')->logout();
            }
        }
        $log = $name." Log Out Successfully";
        UserLogHelper::storeWebLog('Logout', $log);
        return redirect('/');
    }

    public function adminLogout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            $role = Auth::guard('admin')->user()->role;
            if ($role == 1 || $role == 2) {
                Auth::guard('admin')->logout();
            }
        }
        $log = "Admin Log Out Successfully";
        UserLogHelper::storeLog('Logout', $log);
        return redirect('/admin');
    }
    public function forgotPassword()
    {
        return view('frontend.forget_password');
    }

    public function submitForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $user = User::where('email', $request->email)->where('role', 3)->first();
        if ($user) {
            $token = Str::random(64);
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
            $content .= 'You have requested the password reset from MedisourcRX. Please find below password Reset Link to change your password.<br><br><a style="color:blue" href=';
            $content .= url('reset-password/' . $token . "/" . $email);
            $content .= '>Click Here</a><br><br>';
            $content .= 'Best Regards, <br> MedisourceRX';
            Mail::send([], [], function ($message) use ($email, $content) {
                $message->from('info@medisourcerx.com', 'MedisourceRX')
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
        return view('frontend.reset_password', compact('token', 'email'));
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
}
