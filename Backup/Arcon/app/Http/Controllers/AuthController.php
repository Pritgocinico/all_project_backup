<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\Log;
use Carbon\Carbon;
use App\Models\RoleUser;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Notification;
use App\Notifications\Notifications;

class AuthController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        $notification = $user->unreadNotifications;
        view()->share('notifications', $notification);
        view()->share('setting', $setting);
    }
    public function index()
    {
        if (auth()->user())
        {
            if(Auth::user()->role == 1){
                return redirect(route('admin.dashboard'));
            }elseif(Auth::user()->role == 3){
                return redirect(route('agent.dashboard'));
            }
        }
        else
        {
           return view('auth.login');
        }
    }
    public function adminLogin(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = $request->only($fieldType, 'password');

        if (Auth::attempt(array($fieldType => $input['email'], 'password' => $input['password']))) {

            $role = Auth::user()->role;
            $user=Auth::user();
            $user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp()
            ]);
            if($user->status==1){
                    Auth::login($user, true);
                    $log = new Log();
                    $log->user_id   = Auth::user()->name;
                    $log->module    = 'Login';
                    $log->log       = $user->name.' Logged in Successfully';
                    $log->save();
                if($role==1){
                    return redirect()->intended('admin/dashboard')
                            ->withSuccess('Signed in');
                }elseif($role==3){

                    return redirect()->intended('agent/dashboard')
                            ->withSuccess('Signed in');
                }else{
                    Session::flash('message','Invalid email or password');
                    return redirect("login")->withInput()->withSuccess('Login details are not valid');
                }
            }else{
                Session::flash('message','Your account has been deactivated. Please contact with administrator.');
                return redirect("login")->withInput()->withSuccess('Your account has been deactivated. Please contact with administrator.');
            }
        }
        Session::flash('message','Invalid email or password');
        return redirect("login")->withInput()->withSuccess('Login details are not valid');
    }
    public function logout(Request $request) {

        Auth::logout();
        return redirect('/login');
    }
    public function showResetPasswordForm($token) {
         return view('auth.reset-password', ['token' => $token]);
      }
    public function submitResetPasswordForm(Request $request)
    {
          $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->back()->with('status', __($status));
        }
        return back()->withErrors(['email' => [__($status)]]);
    }
}
