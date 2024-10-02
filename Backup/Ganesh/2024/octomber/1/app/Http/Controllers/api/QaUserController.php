<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SmsHelper;
use App\Models\AccessToken;
use App\Models\Role;
use App\Models\Log;
use App\Models\RoleUser;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class QaUserController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }

    public function index(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
    
        $qaUsers = User::where('role',10)->whereNot('id',1)->get();
        $array_push = array();
        if(!blank($qaUsers)){
            foreach($qaUsers as $qa){
                $array = array();
                $array['id']            = $qa->id;
                $array['name']          = ($qa->name != NULL)?$qa->name:"";
                $array['email']         = ($qa->email != NULL)?$qa->email:"";
                $array['phone']         = ($qa->phone != NULL)?$qa->phone:"";
                $array['status']        = ($qa->status != NULL)?(int)$qa->status:0;
                $array['created_at']    = ($qa->created_at != NULL)?date('d/m/Y',strtotime($qa->created_at)):"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'qa_users'=>$array_push
            ],200);
        }else{
                return response()->json(['status'=>0,'error'=> 'Fitting Users Not Found.'],404);
        }
    }


    public function store(Request $request)
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
            $f_user->role     = 10;
            $f_user->status   = 1;
            $f_user->save();

            $role           = new RoleUser();
            $role->user_id  = $f_user->id;
            $role->role_id  = 10;
            $ins = $role->save();

            if($f_user){
                try {
                    $role = Role::where('id', 6)->first();
                    $mobileNumber = $f_user->phone;
                    $password = $request->password;
                    $message = "Your user id of " . $role->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $mobileNumber . " , Password: " . $password;
                    $templateid = '1407171593745579639';
                    $setting = Setting::first();
                    if($setting->wa_message_sent == 1){                       
                        $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, true);
                    } else {
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
                return response()->json(['status'=>1,'message'=>'QA User added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
            }
        }
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'qa_user_id' => 'required',
            'phone' => [
                'required',
                Rule::unique('users', 'phone')->ignore($request->qa_user_id)->whereNull('deleted_at'),
            ],
            'user_id' => 'required',
        ]);
        if(!blank($request->user_id)){
            $user = User::where('id', $request->user_id)->first();
            $role = $user->role;
        }
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $qa_user = User::where('id',$request->qa_user_id)->first();
            if(!blank($qa_user)){
                $qa_user->name     = $request->name;
                $qa_user->email    = $request->email;
                $qa_user->phone    = $request->phone;
                if($request->has('password') && !blank($request->password) && $request->password != ''){
                    $qa_user->password = Hash::make($request->password);
                }
                if($request->has('status')){
                    $qa_user->status   = $request->status;
                }
                $qa_user->save();
                if($qa_user){
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Fitting User';
                    $log->log       = 'Fitting User ('.$request->name.') Updated.';
                    $log->save();
                    return response()->json(['status'=>1,'message'=>'QA User updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'QA User Not Found.'],404);   
            }
        }
    }

    public function delete(Request $request,$id){
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
            $qa_user = User::find($id);
            if(!blank($qa_user)){
                    $qa_user->delete();
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'QA User';
                    $log->log       = 'QA User ('.$user->name.') deleted.';
                    $log->save();
                    return response()->json(["status"=>1,"message"=>'QA User Deleted Successfully.']);
            }else{
                return response()->json(["status"=>0,"error"=>"QA User not found."]);
            }
        }
    }
}
