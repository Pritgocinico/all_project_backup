<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoleUser;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use App\Models\Setting;


class AuthController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
   
        view()->share('setting', $setting);
    }


    // public function __construct() {
    //     $setting=Setting::first();
    //     $user = User::first();
    //     view()->share('setting', $setting);
    // }
    public function index()
    {
        if (auth()->user())
        {
            if(Auth::user()->role == 1){
                return redirect()->route('admin.dashboard');
            }else{
                return view('auth.login');
            }
        }else
        {
           return view('auth.login');
        }
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = $request->only($fieldType, 'password');

        // Check if the "Remember Me" checkbox is checked
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $role = Auth::user()->role;

            if ($role == 1) {
                return redirect()->intended('admin/dashboard')->withSuccess('Signed in');
            } else {
                Auth::logout();
                Session::flash('message', 'Invalid email or password');
                return redirect("admin")->withInput()->withSuccess('Login details are not valid');
            }
        }

        Session::flash('message', 'Invalid email or password');
        return redirect("admin")->withInput()->withSuccess('Login details are not valid');
    }
    
    public function logout(Request $request) {
        $role = Auth()->user()->role;
        Auth::logout();
        if($role == 1){
            return redirect('/admin');
        }
        return redirect('/');
    }
}
