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
use App\Models\Role;
use App\Models\Setting;
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

class MeasurementUserController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function measurementUser(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
    
        $measurements = User::where('role',3)->whereNot('id',1)->get();
        $array_push = array();
        if(!blank($measurements)){
            foreach($measurements as $measurement){
                $array = array();
                $array['id']            = $measurement->id;
                $array['name']          = ($measurement->name != NULL)?$measurement->name:"";
                $array['email']         = ($measurement->email != NULL)?$measurement->email:"";
                $array['phone']         = ($measurement->phone != NULL)?$measurement->phone:"";
                $array['status']        = ($measurement->status != NULL)?(int)$measurement->status:0;
                $array['created_at']    = ($measurement->created_at != NULL)?date('d/m/Y',strtotime($measurement->created_at)):"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'measurement_users'=>$array_push
            ],200);
        }else{
                return response()->json(['status'=>0,'error'=> 'Measurement Users Not Found.'],404);
        }
    }
    public function addMeasurementUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users,phone,NULL,id,deleted_at,NULL',
            'password' => 'required',
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $m_user = new User();
            $m_user->name     = $request->name;
            $m_user->email    = $request->email;
            $m_user->phone    = $request->phone;
            $m_user->password = Hash::make($request->password);
            $m_user->role     = 3;
            $m_user->status   = 1;
            $m_user->save();

            $role           = new RoleUser;
            $role->user_id  = $m_user->id;
            $role->role_id  = 3;
            $ins = $role->save();

            if($m_user){
                $role = Role::where('id', 3)->first();
                $setting = Setting::first();
                $mobileNumber = $m_user->phone;
                $message = "Your user id of ".$role->name." has been created in the CRM of Shree Ganesh Aluminum. Id: ".$mobileNumber.", Password: ".$request->password."";
                $templateid = '1407171593756486272';
                if($setting->wa_message_sent == 1){
                    $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $templateid, false);
                }
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Measurement User';
                $log->log       = 'Measurement User ('.$request->name.') Created.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Measurement User added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
            }
        }
    }
    public function updateMeasurementUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => [
                'required',
                Rule::unique('users', 'phone')->ignore($request->measurement_user_id)->whereNull('deleted_at'),
            ],
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $m_user = User::where('id',$request->measurement_user_id)->first();
            $m_user->name     = $request->name;
            $m_user->email    = $request->email;
            $m_user->phone    = $request->phone;
            if($request->has('password') && !blank($request->password) && $request->password != ''){
                $m_user->password = Hash::make($request->password);
            }
            if($request->has('status')){
                $m_user->status   = $request->status;
            }
            $m_user->save();

            if($m_user){
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Measurement User';
                $log->log       = 'Measurement User ('.$request->name.') Updated.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Measurement User updated successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
            }
        }
    }
    public function deleteMeasurementUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $measurementUser = User::find($id);
            if(!blank($measurementUser)){
                    $measurementUser->delete();
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Measurement User';
                    $log->log       = 'Measurement User ('.$user->name.') deleted.';
                    $log->save();
                    return response()->json(["status"=>1,"message"=>'Measurement User Deleted Successfully.']);
            }else{
                return response()->json(["status"=>0,"error"=>"Measurement User not found."]);
            }
        }
    }
}
