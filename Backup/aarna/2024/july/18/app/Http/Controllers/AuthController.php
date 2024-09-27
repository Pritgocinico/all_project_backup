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
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function index()
    {
        if (auth()->user())
        {
            if(Auth::user()->role == 1){
                return redirect('admin/dashboard');
            }elseif(Auth::user()->role == 3){
                return redirect('agent/dashboard');
            }
        }else
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
                'last_login_ip_address' => $request->getClientIp()
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
                            ->withSuccess('Logged in Successfully.');
                }elseif($role==2){
                    return redirect()->intended('staff/dashboard')
                            ->withSuccess('Logged in Successfully.');
                }elseif($role==3){
                    return redirect()->intended('agent/dashboard')
                            ->withSuccess('Logged in Successfully.');
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
         return view('auth.forgetPasswordLink', ['token' => $token]);
      }
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
          'email' => 'required|email|exists:users',
          'password' => 'required|string|min:6|confirmed',
          'password_confirmation' => 'required'
        ]);
        $updatePassword = DB::table('password_reset_tokens')
          ->where([
            'email' => $request->email,
            'token' => $request->token
          ])
          ->first();
        if(!$updatePassword){
            return back()->withInput()->with('message', 'Invalid token!');
        }
        $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();
        $user = User::where('email',$request->email)->first();
        $log = new Log();
        $log->user_id   = $user->name;
        $log->module    = 'Password Reset';
        $log->log       = $user->name.' password reset Successfully';
        $log->save();
        return redirect()->route('login');
        //   $request->validate([
        //     'token' => 'required',
        //     'email' => 'required|email',
        //     'password' => 'required|min:8|confirmed',
        // ]);
        // $status = Password::reset(
        //     $request->only('email', 'password', 'password_confirmation', 'token'),
        //     function ($user, $password) {
        //         $user->forceFill([
        //             'password' => bcrypt($password)
        //         ])->save();
        //     }
        // );
        // if ($status == Password::PASSWORD_RESET) {
        //     return redirect()->back()->with('status', __($status));
        // }
        // return back()->withErrors(['email' => [__($status)]]);
    }
    
    public function terms_and_conditions() {
         return view('terms_and_conditions');
    }
    public function privacy_and_policy() {
         return view('privacy_and_policy');
    }  
        public function accountdelete()
    {
           return view('account-delete');
    }  
    
     public function accountdel(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::validate(array('email' => $input['email'], 'password' => $input['password']))) {
            return Redirect::route('accountdelete')->with('success', 'Your account has been deactivated within 90 days.');
        } else {
            return Redirect::back()->withInput()->withErrors('Incorrect email or password. Please try again.');
        }
        Session::flash('message','Invalid email or password');
        return redirect("account-delete")->withSuccess('details are not valid');
    }
    
    public function contact_us(){
        return view('contat-us');
    }
    
    public function storeContactUs(Request $request){
        $request->validate([
            'name'    => 'required',
            'email_address'     => 'required',
            'phone'         => 'required',
        ]);
        
        $data= [
            'name' =>$request->name,
            'email_address' =>$request->email_address,
            'phone' =>$request->phone,
            'message' =>$request->message,
            ];
        $insert = ContactUs::create($data);
        if($insert){
            return Redirect::route('contact-us')->with('success', 'Contact Us created sucessfully..');
        }
        return Redirect::back()->withInput()->withErrors('Incorrect email or password. Please try again.');
        
    }  
}
