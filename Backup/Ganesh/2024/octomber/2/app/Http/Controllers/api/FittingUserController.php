<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use App\Http\Helpers\SmsHelper;
use Illuminate\Validation\Rule;

class FittingUserController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function fittingUser(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
    
        $fittings = User::where('role',6)->whereNot('id',1)->get();
        $array_push = array();
        if(!blank($fittings)){
            foreach($fittings as $fitting){
                $array = array();
                $array['id']            = $fitting->id;
                $array['name']          = ($fitting->name != NULL)?$fitting->name:"";
                $array['email']         = ($fitting->email != NULL)?$fitting->email:"";
                $array['phone']         = ($fitting->phone != NULL)?$fitting->phone:"";
                $array['status']        = ($fitting->status != NULL)?(int)$fitting->status:0;
                $array['created_at']    = ($fitting->created_at != NULL)?date('d/m/Y',strtotime($fitting->created_at)):"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'fitting_users'=>$array_push
            ],200);
        }else{
                return response()->json(['status'=>0,'error'=> 'Fitting Users Not Found.'],404);
        }
    }
    public function addFittingUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users,phone,NULL,id,deleted_at,NULL',
            'password' => 'required',
            'user_id' => 'required'
        ]);
        if(!blank($request->user_id)){
            $user = User::where('id', $request->user_id)->first();
            $role = $user->role;
        }
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $f_user = new User();
            $f_user->name     = $request->name;
            $f_user->email    = $request->email;
            $f_user->phone    = $request->phone;
            $f_user->password = Hash::make($request->password);
            $f_user->role     = 6;
            $f_user->status   = 1;
            $f_user->save();

            $role           = new RoleUser;
            $role->user_id  = $f_user->id;
            $role->role_id  = 6;
            $ins = $role->save();

            if($f_user){
                try {
                    $setting = Setting::first();
                    $role = Role::where('id', 6)->first();
                    $mobileNumber = $f_user->phone;
                    $password = $request->password;
                    $message = "Your user id of " . $role->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $mobileNumber . " , Password: " . $password;
                    if($setting->wa_message_sent == 1){
                        SmsHelper::sendSmsWithTemplate($mobileNumber,$message,true);
                    } else {
                        
                        $templateid = '1407171593745579639';
                        $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                    }
                } catch (Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                }
                
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Fitting User';
                $log->log       = 'Fitting User ('.$request->name.') Created.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Fitting User added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
            }
        }
    }
    public function updateFittingUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => [
                'required',
                Rule::unique('users', 'phone')->ignore($request->fitting_user_id)->whereNull('deleted_at'),
            ],
            'user_id' => 'required'
        ]);
        if(!blank($request->user_id)){
            $user = User::where('id', $request->user_id)->first();
            $role = $user->role;
        }
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $f_user = User::where('id',$request->fitting_user_id)->first();
            if(!blank($f_user)){
                $f_user->name     = $request->name;
                $f_user->email    = $request->email;
                $f_user->phone    = $request->phone;
                if($request->has('password') && !blank($request->password) && $request->password != ''){
                    $f_user->password = Hash::make($request->password);
                }
                if($request->has('status')){
                    $f_user->status   = $request->status;
                }
                $f_user->save();
                if($f_user){
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Fitting User';
                    $log->log       = 'Fitting User ('.$request->name.') Updated.';
                    $log->save();
                    return response()->json(['status'=>1,'message'=>'Fitting User updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Fitting User Not Found.'],404);   
            }
        }
    }
    public function deleteFittingUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        if(!blank($request->user_id)){
            $user = User::where('id', $request->user_id)->first();
            $role = $user->role;
        }
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $fittingUser = User::find($id);
            if(!blank($fittingUser)){
                    $fittingUser->delete();
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Fitting User';
                    $log->log       = 'Fitting User ('.$user->name.') deleted.';
                    $log->save();
                    return response()->json(["status"=>1,"message"=>'Fitting User Deleted Successfully.']);
            }else{
                return response()->json(["status"=>0,"error"=>"Fitting User not found."]);
            }
        }
    }
}
