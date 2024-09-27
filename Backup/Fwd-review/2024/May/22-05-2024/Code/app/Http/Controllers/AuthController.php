<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Carbon\Carbon;
use App\Models\RoleUser;
use App\Models\Setting;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function index(){
        if (auth()->user())
        {
            if(Auth::user()->role == 1){
                return redirect(route('admin.dashboard'));
            }
        }
        else
        {
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
                }elseif($role==2){
                    if ($user->sub_end_date !== null) {
                        $subscription_endDate = date('Y-m-d', strtotime($user->sub_end_date));
                        if($currentDate >= $subscription_endDate){
                            Session::flash('message','Your Subscription has ended. Please <a href="https://fwdreviews.com/#review_package_outer" style="color: red;">Subscribe to our Plan</a>');
                            return redirect("login")->withInput()->withSuccess('Your Subscription has ended. Please Subscribe to our Plans!');
                        }
                    }else{
                        return redirect()->intended('client/dashboard')
                    ->withSuccess('Signed in');
                    }
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

    public function subscription(Request $request){
        return redirect('/login');
    }

    public function webhook(Request $request){
        dd($request->all());
    }
}
