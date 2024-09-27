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
                return redirect()->intended('admin/dashboard')->withSuccess('Signed in');
            } else {
                Auth::guard('admin')->logout();
                Session::flash('message', 'Invalid email or password');
                return redirect("admin")->withInput()->withSuccess('Login details are not valid');
            }
        }
    
        // Authentication failed
        Session::flash('message', 'Invalid email or password');
        return redirect("admin")->withInput()->withSuccess('Login details are not valid');
    }
    
    
    public function logout(Request $request) {
        if(Auth::guard('web')->check()) {
            $role = Auth::guard('web')->user()->role;
            if($role == 3) {
                Auth::guard('web')->logout();
            } 
        }
        return redirect('/');
    }
    
    public function adminLogout(Request $request) {
        if(Auth::guard('admin')->check()) {
            $role = Auth::guard('admin')->user()->role;
            if($role == 1 || $role == 2) {
                Auth::guard('admin')->logout();
            }
        }
        return redirect('/admin');
    }
    
}
