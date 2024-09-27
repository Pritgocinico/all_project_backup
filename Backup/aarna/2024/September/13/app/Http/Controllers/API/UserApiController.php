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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;

class UserApiController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function Users(){
        $users = User::where('role',1)->orWhere('role',2)->orderBy('id','DESC')->get();
        $array_push = array();
        if(!blank($users)){
            foreach($users as $user){
                $array = array();
                $array['id']            = $user->id;
                $array['first_name']    = ($user->first_name != NULL)?$user->first_name:"";
                $array['last_name']     = ($user->last_name != NULL)?$user->last_name:"";
                $array['email']         = $user->email;
                $array['phone']         = ($user->phone != NULL)?$user->phone:"";
                $array['role']          = $user->role;
                $array['status']        = $user->status;
                $array['team_lead']     = $user->team_lead;
                $array['created_at']    = ($user->created_at != NULL)?$user->created_at:"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'users'=>$array_push
            ],200);
        }else{
             return response()->json(['status'=>0,'error'=> 'Users Not Found.'],200);
        }
    }
    public function storeUser(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required|unique:users',
            'email'         => 'required',
            'password'      => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $user = new User();
            $user->name         = $request->first_name.' '.$request->last_name;
            $user->first_name   = $request->first_name;
            $user->last_name    = $request->last_name;
            $user->phone        = $request->phone;
            $user->mobile       = $request->alt_phone;
            $user->email        = $request->email;
            $user->role         = $request->role;
            $user->team_lead    = $request->team_lead;
            $user->password     = Hash::make($request->password);
            $user->status       = 1;
            $insert             = $user->save();

            $role   =   new RoleUser;
            $role->user_id  = $user->id;
            $role->role_id  = $request->role;
            $ins            = $role->save();
            if($insert){
                $user1 = User::where('id',$user->id)->first();
                $log = new Log();
                $log->user_id   = $user1->name;
                $log->module    = 'User';
                $log->log       = 'User ('.$user1->name .') Added.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'User added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'], 200);
            }
        }
    }
    public function deleteUser(Request $request, $id){
        if(!blank($id)){
            $user = User::where('id',$id)->first();
            if(!blank($user)){
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'User';
                $log->log       = 'User ('.$user->name .') Deleted.';
                $log->save();
                $user->delete();
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
            }
            return response()->json(['status'=>1,'message'=>'User deleted successfully!'],200);
        }else{
           return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
        }
    }
    public function updateUser(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name'          => 'required',
            'last_name'           => 'required',
            'email'               => 'required',
            'phone'               => 'required|unique:users,phone,' . $request->user_id,
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $user = User::where('id',$request->user_id)->first();
            $user->name         = $request->first_name.' '.$request->last_name;
            $user->first_name   = $request->first_name;
            $user->last_name    = $request->last_name;
            $user->phone        = $request->phone;
            $user->mobile       = $request->alt_phone;
            $user->email        = $request->email;
            $user->role         = $request->role;
            $user->team_lead    = $request->team_lead;
            if($request->has('password') && $request->password != ''){
                $user->password = Hash::make($request->password);
            }
            $user->status       = $request->status;
            $insert             = $user->save();
            if($insert){
                $user1 = User::where('id',$user->id)->first();
                $log = new Log();
                $log->user_id   = $user1->name;
                $log->module    = 'User';
                $log->log       = 'User ('.$user1->name .') Updated.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'User updated successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'], 200);
            }
        }
    }
    public function updateProfileImage(Request $request){
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            if(!blank($accessToken)){
                $user = User::where('id',$accessToken->user_id)->first();
            
            $validator = Validator::make($request->all(), [
                'image'    => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
            }else{
                 if($request->has('image') && $request->file('image') != null){
                    $image = $request->file('image');
                    $destinationPath = 'public/settings/';
                    $rand=rand(1,100);
                    $docImage = date('YmdHis'). "." . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $docImage);
                    $img=$docImage;
                }
                $user->image  = $img;
                $insert             = $user->save();
                if($insert){
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'User';
                    $log->log       = 'User ('.$user->name .') Profile Updated.';
                    $log->save();
                    return response()->json(['status'=>1,'image'=>url('/').'/public/settings/'.$img, 'message'=>'User profile updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something Went Wrong.'], 200);
                }
            }
        }else{
                return response()->json(['status'=>0,'error'=>'Unauthorized Request'], 200);
       }
    }
    public function updateCoverImage(Request $request){
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            if(!blank($accessToken)){
                $user = User::where('id',$accessToken->user_id)->first();
            
            $validator = Validator::make($request->all(), [
                'image'    => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
            }else{
                 if($request->has('image') && $request->file('image') != null){
                    $image = $request->file('image');
                    $destinationPath = 'public/settings/';
                    $rand=rand(1,100);
                    $docImage = date('YmdHis'). "." . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $docImage);
                    $img=$docImage;
                }
                $user->cover_image  = $img;
                $insert             = $user->save();
                if($insert){
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'User';
                    $log->log       = 'User ('.$user->name .') Cover Profile Updated.';
                    $log->save();
                    return response()->json(['status'=>1,'image'=>url('/').'/public/settings/'.$img, 'message'=>'User cover profile updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something Went Wrong.'], 200);
                }
            }
        }else{
                return response()->json(['status'=>0,'error'=>'Unauthorized Request'], 200);
       }
    }
}
