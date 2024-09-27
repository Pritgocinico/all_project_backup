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

class QuotationUserController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function quotationUser(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
    
        $quotations = User::where('role',4)->whereNot('id',1)->get();
        $array_push = array();
        if(!blank($quotations)){
            foreach($quotations as $quotation){
                $array = array();
                $array['id']            = $quotation->id;
                $array['name']          = ($quotation->name != NULL)?$quotation->name:"";
                $array['email']         = ($quotation->email != NULL)?$quotation->email:"";
                $array['phone']         = ($quotation->phone != NULL)?$quotation->phone:"";
                $array['status']        = ($quotation->status != NULL)?(int)$quotation->status:0;
                $array['created_at']    = ($quotation->created_at != NULL)?date('d/m/Y',strtotime($quotation->created_at)):"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'quotation_users'=>$array_push
            ],200);
        }else{
                return response()->json(['status'=>0,'error'=> 'Quotation Users Not Found.'],404);
        }
    }
    public function addQuotationUser(Request $request)
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
            $q_user = new User();
            $q_user->name     = $request->name;
            $q_user->email    = $request->email;
            $q_user->phone    = $request->phone;
            $q_user->password = Hash::make($request->password);
            $q_user->role     = 4;
            $q_user->status   = 1;
            $q_user->save();

            $role           = new RoleUser;
            $role->user_id  = $q_user->id;
            $role->role_id  = 4;
            $ins = $role->save();

            if($q_user){
                $role = Role::where('id', 4)->first();
                $mobileNumber = $q_user->phone;
                $message = "Your user id of ".$role->name." has been created in the CRM of Shree Ganesh Aluminum. Id: ".$mobileNumber.", Password: ".$request->password."";
                $templateid = '1407171593756486272';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Quotation User';
                $log->log       = 'Quotation User ('.$request->name.') Created.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Quotation User added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
            }
        }
    }
    public function updateQuotationUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users,phone,'.$request->quotation_user_id,
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $q_user = User::where('id',$request->quotation_user_id)->first();
            if(!blank($q_user)){
                $q_user->name     = $request->name;
                $q_user->email    = $request->email;
                $q_user->phone    = $request->phone;
                if($request->has('password') && !blank($request->password) && $request->password != ''){
                    $q_user->password = Hash::make($request->password);
                }
                if($request->has('status')){
                    $q_user->status   = $request->status;
                }
                $q_user->save();
                if($q_user){
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Quotation User';
                    $log->log       = 'Quotation User ('.$request->name.') Updated.';
                    $log->save();
                    return response()->json(['status'=>1,'message'=>'Quotation User updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Quotation User Not Found.'],404);   
            }
        }
    }
    public function deleteQuotationUser(Request $request, $id)
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
            $quotationUser = User::find($id);
            if(!blank($quotationUser)){
                    $quotationUser->delete();
                    return response()->json(["status"=>1,"message"=>'Quotation User Deleted Successfully.']);
            }else{
                return response()->json(["status"=>0,"error"=>"Quotation User not found."]);
            }
        }
    }
}
