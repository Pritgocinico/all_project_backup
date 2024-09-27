<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Category;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Company;
use App\Models\SourcingAgent;
use App\Models\Policy;
use App\Models\Covernote;
use App\Models\Claim;
use App\Models\PayoutList;
use App\Models\SourcingAgentPayout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use App\Rules\MatchOldPassword;
use Mail;
use Notification;
use App\Notifications\Notifications;
class AuthController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    
    // User Login
    public function loginUser(Request $req){
        $input = $req->all();
        $validator = Validator::make($req->all(), [
           'username'               => 'required',
           'password'            => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error_type'=>4,'error'=>$validator->errors()], 200);
        }else{
            $fieldType = filter_var($req->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            $credentials = $req->only($fieldType, 'password');
            if (Auth::attempt(array($fieldType => $input['username'], 'password' => $input['password']))) {
               $role = Auth::user()->role;
               $user=Auth::user();
                if($user->role == 1){
                    if($user->status == 1){
                        $user->update([
                            'last_login_at' => Carbon::now()->toDateTimeString(),
                            'last_login_ip' => $req->getClientIp()
                        ]);
                        $log = new Log();
                        $log->user_id   = $user->name;
                        $log->module    = 'Login';
                        $log->log       = $user->name.' Logged in Successfully';
                        $log->save();
                        
                        if(!blank($user)){
                            $array = array();
                            $array['userId'] = $user->id;
                            if(!blank($user->image)){
                                $array['image'] = url('/').'/public/settings/'.$user->image;
                            }else{
                                $array['image'] = '';
                            }
                            if($user->status == 1){
                                $status = 'Active';
                            }else{
                                $status = 'Deactive';
                            }
                            $motor_policies = Policy::where('created_by',$user->id)->where('insurance_type',1)->where('status','!=',2)->count();   
                            $health_policies = Policy::where('created_by',$user->id)->where('insurance_type',2)->where('status','!=',2)->count();   
                            $array['displayName']       = $user->name;
                            $array['username']          = $user->email;
                            $array['email']             = $user->email;
                            $array['phone']             = $user->phone;
                            $array['profileImage']      = $user->image;
                            $array['profileCoverImage'] = $user->cover_image;
                            $array['passwordHash']      = $user->password;
                            $array['userStatus']        = $status;
                            $accessToken = AccessToken::create([
                                'user_id' => $user->id,
                                'access_token' => Str::random(255)
                            ]);
                            $isAdmin = true;
                            return response()->json(['result'=>200,'description'=>'Login Validated','status'=>1,'access_token' =>  $accessToken->access_token ,'userJson'=>json_encode($array),'isAdmin'=>$isAdmin,'vehiclePolicyCount'=>$motor_policies,'healthPolicyCount'=>$health_policies],200);
                        }else{
                            return response()->json(['status'=>0,'error_type'=>1,'error'=>'User Not Found.'],200);
                        }
                    }else{
                        return response()->json(['status'=>0,'error_type'=>2,'error'=>'User Not Verified.'],200);
                    }
                }elseif($user->role == 3){
                    if($user->status == 1){
                        $user->update([
                            'last_login_at' => Carbon::now()->toDateTimeString(),
                            'last_login_ip' => $req->getClientIp()
                        ]);
                        $log = new Log();
                        $log->user_id   = $user->name;
                        $log->module    = 'Login';
                        $log->log       = $user->name.' Logged in Successfully';
                        $log->save();
                        
                        if(!blank($user)){
                            $array = array();
                            $array['userId'] = $user->id;
                            if(!blank($user->image)){
                                $array['image'] = url('/').'/public/settings/'.$user->image;
                            }else{
                                $array['image'] = '';
                            }
                            if($user->status == 1){
                                $status = 'Active';
                            }else{
                                $status = 'Deactive';
                            }
                            $array['displayName']       = $user->name;
                            $array['username']          = $user->email;
                            $array['email']             = $user->email;
                            $array['phone']            = $user->phone;
                            $array['profileImage']      = $user->image;
                            $array['profileCoverImage'] = $user->cover_image;
                            $array['passwordHash']      = $user->password;
                            $array['userStatus']        = $status;
                            $accessToken = AccessToken::create([
                                'user_id' => $user->id,
                                'access_token' => Str::random(255)
                            ]);
                            $agent = SourcingAgent::where('user_id',$user->id)->first();
                            $motor_policies = Policy::where('agent',$agent->id)->where('insurance_type',1)->where('status','!=',2)->count();  
                            $health_policies = Policy::where('agent',$agent->id)->where('insurance_type',2)->where('status','!=',2)->count();   
                            $isAdmin = false;
                            return response()->json(['result'=>200,'description'=>'Login Validated','status'=>1,'access_token' =>  $accessToken->access_token ,'userJson'=>json_encode($array),'isAdmin'=>$isAdmin,'vehiclePolicyCount'=>$motor_policies,'healthPolicyCount'=>$health_policies],200);
                        }else{
                            return response()->json(['status'=>0,'error_type'=>1,'error'=>'User Not Found.'],200);
                        }
                    }else{
                        return response()->json(['status'=>0,'error_type'=>2,'error'=>'User Not Verified.'],200);
                    }
                }
            }else{
               return response()->json(['result'=>200,'status'=>0,'error_type'=>3,'description'=>'Wrong Username or Password.','error'=>'Wrong Username or Password.'],200);
            }
        }
    }

    //Admin Dashboard
    public function DashboardData(Request $req){
        if($req->has('from_date') && $req->has('to_date')){
            $from_date = date("Y-m-d", strtotime($req->from_date));
            $to_date = date("Y-m-d", strtotime($req->to_date));
            $covernotes = Covernote::orderBy('id','Desc')->whereBetween('risk_start_date', [$from_date, $to_date])->count();
            $renewal = Policy::where('business_type',2)->orderBy('id','Desc')->whereBetween('risk_start_date', [$from_date, $to_date])->count();
            $agents = SourcingAgent::where('status',1)->whereBetween('created_at', [$from_date, $to_date])->count();
            $health_policy = Policy::where('insurance_type',2)->where('status',1)->whereBetween('created_at', [$from_date, $to_date])->count();
            $vehicle_policy = Policy::where('insurance_type',1)->where('status',1)->whereBetween('created_at', [$from_date, $to_date])->count();
            $payout_list = PayoutList::where('status',1)->whereBetween('created_at', [$from_date, $to_date])->count();
        }else{
            $covernotes = Covernote::orderBy('id','Desc')->whereMonth('risk_start_date', Carbon::now()->month)->count();
            $renewal = Policy::where('business_type',2)->orderBy('id','Desc')->whereMonth('risk_start_date', Carbon::now()->month)->count();
            $agents = SourcingAgent::where('status',1)->whereMonth('created_at', Carbon::now()->month)->count();
            $health_policy = Policy::where('insurance_type',2)->where('status',1)->whereMonth('created_at', Carbon::now()->month)->count();
            $vehicle_policy = Policy::where('insurance_type',1)->where('status',1)->whereMonth('created_at', Carbon::now()->month)->count();
            $payout_list = PayoutList::where('status',1)->whereMonth('created_at', Carbon::now()->month)->count();
        }
        if($req->has('lob_month') && $req->has('lob_year')){
            $lob = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', $req->lob_month)->whereYear('policies.risk_start_date', $req->lob_year)->get();
        }else{
            $lob = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', Carbon::now()->month)->whereYear('policies.risk_start_date', 2019)->get();
            // $lob = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', Carbon::now()->month)->whereYear('policies.risk_start_date', Carbon::now()->year)->get();
        }
        if($req->has('p_lob_month') && $req->has('p_lob_year')){
            $last_month_lob = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', $req->p_lob_month)->whereYear('policies.risk_start_date', $req->p_lob_year)->get();
        }else{
            $last_month_lob = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', Carbon::now()->subMonth())->whereYear('policies.risk_start_date', 2018)->get();
            // $last_month_lob = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', Carbon::now()->subMonth())->whereYear('policies.risk_start_date', date('Y')-1)->get();

        }
        // Year-to-date (YTD)
        $year_start = now()->month >= 4 ? now()->startOfYear()->addMonths(3) : now()->startOfYear()->subMonths(9);
        $ytod = Policy::where('status', 1)->where('created_at', '>=', $year_start)->count();
        $ytod_renew = Policy::where('business_type', 2)->where('status', 1)->where('created_at', '>=', $year_start)->count();
        $ytod_percentage = $ytod > 0 ?  number_format(($ytod_renew / $ytod) * 100, 2) : 0;
        
        // Month-to-date (MTD)
        $mtod = Policy::where('status', 1)->where('created_at', '>=', now()->startOfMonth())->count();
        $mtod_renew = Policy::where('business_type', 2)->where('status', 1)->where('created_at', '>=', now()->startOfMonth())->count();
        $mtod_percentage = $mtod > 0 ? number_format(($mtod_renew / $mtod) * 100, 2) : 0;
        
        // Assigning values to policy_data array
        $policy_data = [
            'year_to_date' => [
                'total' => $ytod,
                'renew' => $ytod_renew,
                'percentage' => $ytod_percentage
            ],
            'month_to_date' => [
                'total' => $mtod,
                'renew' => $mtod_renew,
                'percentage' => $mtod_percentage
            ]
        ];

        
        $claims = Claim::orderBy('id','Desc')->with('policy')->get();
        $stacked = array();
        for($i = 1; $i<=12; $i++){
            if($req->has('current_year')){
                $policy = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', '=', $i)->whereYear('policies.risk_start_date',$req->current_year)->get();
            }else{
                $policy = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', '=', $i)->whereYear('policies.risk_start_date', 2019)->get();
                // $policy = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', '=', $i)->whereYear('policies.risk_start_date', date('Y'))->get();
            }
            if(!blank($policy)){
                $j = 0;
                foreach($policy as $policyData){
                    // $stacked[$i][$j]['category'] = $policyData->name;
                    $stacked[$i][$policyData->name]    = $policyData->total; 
                    $j++;
                }
            }else{
                $stacked[$i]['null'] = 0;
            }
        }
        $cat = array();
        foreach($stacked as $key=>$stack){
            if($stack != 0){
                foreach($stack as $k=>$sk){
                    if (!in_array($k, $cat)) { 
                        array_push($cat,$k);
                    }
                }
            }
        }
        $ar = [];
        foreach($stacked as $key=>$stack){
            if($stack != 0){
                foreach($cat as $ca){
                    // echo $ca;
                    if(array_key_exists($ca,$stack)){
                        $ar[$ca][$key] = $stack[$ca];
                    }else{
                        $ar[$ca][$key] = 0;
                    }
                }
            }
        }
        //second
        $stacked1 = array();
        for($i = 1; $i<=12; $i++){
            if($req->has('previous_year')){
                $policy1 = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', $i)->whereYear('policies.risk_start_date',$req->previous_year)->get();
            }else{
                $policy1 = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', $i)->whereYear('policies.risk_start_date', 2018)->get();
                // $policy1 = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', $i)->whereYear('policies.risk_start_date', date('Y')-1)->get();
            }
            if(!blank($policy1)){
                $j = 0;
                foreach($policy1 as $policyData1){
                    // $stacked[$i][$j]['category'] = $policyData->name;
                    $stacked1[$i][$policyData1->name]    = $policyData1->total; 
                    $j++;
                }
            }else{
                $stacked1[$i]['null'] = 0;
            }
        }
        $cat1 = array();
        foreach($stacked1 as $key=>$stack){
            if($stack != 0){
                foreach($stack as $k=>$sk){
                    if (!in_array($k, $cat1)) { 
                        array_push($cat1,$k);
                    }
                }
            }
        }
        $ar1 = [];
        foreach($stacked1 as $key=>$stack){
            if($stack != 0){
                foreach($cat1 as $ca){
                    // echo $ca;
                    if(array_key_exists($ca,$stack)){
                        $ar1[$ca][$key] = $stack[$ca];
                    }else{
                        $ar1[$ca][$key] = 0;
                    }
                }
            }
        }
        $chart = array();
        foreach($ar as $key=>$data1){
            $clr = str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
            $chart1 = array();
            if($req->has('current_year')){
                $label = $req->current_year;
            }else{
                $label = date('Y'); 
            }
            $chart1['label'] = $label.' - '.$key;
            $chart1['backgroundColor'] = '#'.$clr;
            $chart1['borderWidth'] = 0;
            $chart1['stack'] = 'current';
            // $data11 = array_values($data);
            $chart1['data'] = array_values($data1);
            array_push($chart,$chart1);
        }
        foreach($ar1 as $key=>$data1){
            $clr = str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
            $chart1 = array();
            if($req->has('previous_year')){
                $label = $req->previous_year;
            }else{
                $label = date('Y'); 
            }
            $chart1['label'] = $label.' - '.$key;
            $chart1['backgroundColor'] = '#'.$clr;
            $chart1['borderWidth'] = 0;
            $chart1['stack'] = 'previous';
            // $data11 = array_values($data);
            $chart1['data'] = array_values($data1);
            array_push($chart,$chart1);
        }
        return response()->json([
            'result'                => 200,
            'description'           => 'Dashboard Data',
            'status'                => 1,
            'covernote_count'       => $covernotes,
            'renewal_policy_count'  => $renewal,
            'health_policy_count'   => $health_policy,
            'motor_policy_count'    => $vehicle_policy, 
            'agents_count'          => $agents,
            'payouts_count'         => $payout_list,
            'policy_data'           => $policy_data,
            'previous_month_chart'  => $last_month_lob,
            'current_month_lob'     => $lob,
            'claim_data'            => $claims,
            'stacked_chart'         => $chart,
        ],200);
    }
    
    // Change Password
    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required',
            'newPassword' => ['required'],
            'confirmPassword' => ['same:newPassword'],
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            if($request->has('user_id')){
                $user1 = User::where('id',$request->user_id)->first();
                if (Hash::check($request->currentPassword, $user1->password)) {
                    $user1->password = Hash::make($request->newPassword);
                    $user1->save();
                    
                    // User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Change Password';
                    $log->log       = 'Password Changed Successfully.';
                    $log->save();
                    return response()->json(['result'=>200,'status'=>1,'description'=>'Password change successfully.'],200);
                }else{
                    return response()->json(['result'=>200,'status'=>0,'description'=>'Old password is Incorrect!!!'],200);
                }
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.'],200);
            }
        }
    }

    // forgot Password
    public function submitForgetPasswordForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
         ]);
         if ($validator->fails()) {
             return response()->json(['result'=>200,'status'=>0,'error'=>$validator->errors()], 200);
         }else{
            $token = Str::random(64);

            DB::table('password_reset_tokens')->insert([
                'email' => $request->email, 
                'token' => $token, 
                'created_at' => Carbon::now()
            ]);

            Mail::send('emails.forgotpassword', ['token' => $token], function($message) use($request){
                $message->to($request->email);
                $message->subject('Reset Password');
            });
            return response()->json(['result'=>200,'message' => 'We have e-mailed your password reset link!'], 200);
         }
    }
    //Reset Password
    public function submitResetPasswordForm(Request $request){
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
        
        // return response()->json(['result'=>200,'message' => 'Your password has been changed!'], 200);
    }
    public function readNotification(Request $req){
        $notificationId = $req->id;
        $user = User::where('id',$req->user_id)->first();
        $userUnreadNotification = $user
                                    ->unreadNotifications
                                    ->where('id', $notificationId)
                                    ->first();

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }
        return response()->json([
            'result'                    =>  200,
            'description'               =>  'Notification',
            'status'                    =>  1,
            'message'                   =>  'Success!'
        ],200);
    }
    public function Notifications(Request $req){
        $user = User::where('id',$req->user_id)->first();
        $notifications = $user->unreadNotifications;
        return response()->json([
            'result'                    =>  200,
            'description'               =>  'Notifications',
            'status'                    =>  1,
            'notifications'            =>  json_encode($notifications),
            'message'                   =>  'Success!'
        ],200);
    }
}
