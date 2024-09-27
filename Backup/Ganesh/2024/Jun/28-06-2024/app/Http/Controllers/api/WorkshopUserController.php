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

class WorkshopUserController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function workshopUser(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
    
        $workshops = User::where('role',5)->whereNot('id',1)->get();
        $array_push = array();
        if(!blank($workshops)){
            foreach($workshops as $workshop){
                $array = array();
                $array['id']            = $workshop->id;
                $array['name']          = ($workshop->name != NULL)?$workshop->name:"";
                $array['email']         = ($workshop->email != NULL)?$workshop->email:"";
                $array['phone']         = ($workshop->phone != NULL)?$workshop->phone:"";
                $array['status']        = ($workshop->status != NULL)?(int)$workshop->status:0;
                $array['created_at']    = ($workshop->created_at != NULL)?date('d/m/Y',strtotime($workshop->created_at)):"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'workshop_users'=>$array_push
            ],200);
        }else{
                return response()->json(['status'=>0,'error'=> 'Workshop Users Not Found.'],404);
        }
    }
    public function addWorkshopUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
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
            $w_user = new User();
            $w_user->name     = $request->name;
            $w_user->email    = $request->email;
            $w_user->phone    = $request->phone;
            $w_user->password = Hash::make($request->password);
            $w_user->role     = 5;
            $w_user->status   = 1;
            $w_user->save();

            $role           = new RoleUser;
            $role->user_id  = $w_user->id;
            $role->role_id  = 5;
            $ins = $role->save();

            if($w_user){
                $role = Role::where('id', 5)->first();
                $mobileNumber = $w_user->phone;
                $message = "Your user id of ".$role->name." has been created in the CRM of Shree Ganesh Aluminum. Id: ".$mobileNumber.", Password: ".$request->password."";
                $templateid = '1407171593756486272';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Workshop User';
                $log->log       = 'Workshop User ('.$request->name.') Created.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Workshop User added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
            }
        }
    }
    public function updateWorkshopUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users,phone,'.$request->workshop_user_id,
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $w_user = User::where('id',$request->workshop_user_id)->first();
            if(!blank($w_user)){
                $w_user->name     = $request->name;
                $w_user->email    = $request->email;
                $w_user->phone    = $request->phone;
                if($request->has('password') && !blank($request->password) && $request->password != ''){
                    $w_user->password = Hash::make($request->password);
                }
                if($request->has('status')){
                    $w_user->status   = $request->status;
                }
                $w_user->save();
                if($w_user){
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Workshop User';
                    $log->log       = 'Workshop User ('.$request->name.') Updated.';
                    $log->save();
                    return response()->json(['status'=>1,'message'=>'Workshop User updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Workshop User Not Found.'],404);   
            }
        }
    }
    public function deleteWorkshopUser(Request $request, $id)
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
            $workshopUser = User::find($id);
            if(!blank($workshopUser)){
                    $workshopUser->delete();
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Workshop User';
                    $log->log       = 'Workshop User ('.$user->name.') deleted.';
                    $log->save();
                    return response()->json(["status"=>1,"message"=>'Workshop User Deleted Successfully.']);
            }else{
                return response()->json(["status"=>0,"error"=>"Workshop User not found."]);
            }
        }
    }
}
