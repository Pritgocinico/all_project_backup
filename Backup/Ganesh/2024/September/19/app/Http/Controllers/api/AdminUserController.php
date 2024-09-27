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

class AdminUserController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }
    public function adminUser(Request $request)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $admins = User::where('role', 1)->whereNot('id', 1)->get();
        $array_push = array();
        if (!blank($admins)) {
            foreach ($admins as $admin) {
                $array = array();
                $array['id']            = $admin->id;
                $array['name']          = ($admin->name != NULL) ? $admin->name : "";
                $array['email']         = ($admin->email != NULL) ? $admin->email : "";
                $array['phone']         = ($admin->phone != NULL) ? $admin->phone : "";
                $array['status']        = ($admin->status != NULL) ? (int)$admin->status : 0;
                $array['created_at']    = ($admin->created_at != NULL) ? date('d/m/Y', strtotime($admin->created_at)) : "";
                array_push($array_push, $array);
            }
            return response()->json([
                'status' => 1,
                'admins' => $array_push
            ], 200);
        } else {
            return response()->json(['status' => 0, 'error' => 'Admins Not Found.'], 404);
        }
    }
    public function addAdminUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
            'user_id' => 'required'
        ]);
        if (!blank($request->user_id)) {
            $user = User::where('id', $request->user_id)->first();
            $role = $user->role;
        }
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $admin = new User();
            $admin->name     = $request->name;
            $admin->email    = $request->email;
            $admin->phone    = $request->phone;
            $admin->password = Hash::make($request->password);
            $admin->role     = 1;
            $admin->status   = 1;
            $admin->save();

            $role           = new RoleUser;
            $role->user_id  = $admin->id;
            $role->role_id  = 1;
            $ins = $role->save();

            if ($admin) {
                $role = Role::where('id', 1)->first();
                try {
                    $mobileNumber = $admin->phone;
                    $password = $request->password;
                    $message = "Your user id of " . $role->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $mobileNumber . " , Password: " . $password;
                    $setting = Setting::first();
                    $number = [];
                    if ($setting->wa_message_sent == 1) {
                        $number = $admin->phone;
                        $isSent = SmsHelper::sendSmsWithTemplate($number, $message, true);
                    } else {
                        $templateid = '1407171593745579639';
                        $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                    }
                } catch (Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                }
                $user = User::where('id', $request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Admin User';
                $log->log       = 'Admin (' . $request->name . ') Created.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Admin added successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
    public function updateAdminUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $request->admin_id,
            'user_id' => 'required'
        ]);
        if (!blank($request->user_id)) {
            $user = User::where('id', $request->user_id)->first();
            $role = $user->role;
        }
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $admin = User::where('id', $request->admin_id)->first();
            $admin->name     = $request->name;
            $admin->email    = $request->email;
            $admin->phone    = $request->phone;
            if ($request->has('password') && !blank($request->password) && $request->password != '') {
                $admin->password = Hash::make($request->password);
            }
            if ($request->has('status')) {
                $admin->status   = $request->status;
            }
            $admin->save();

            if ($admin) {
                $user = User::where('id', $request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Admin User';
                $log->log       = 'Admin (' . $request->name . ') Updated.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Admin updated successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
    public function deleteAdminUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        if (!blank($request->user_id)) {
            $user = User::where('id', $request->user_id)->first();
            $role = $user->role;
        }
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $admin = User::find($id);
            if (!blank($admin)) {
                $admin->delete();
                $user = User::where('id', $request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Admin';
                $log->log       = 'Admin (' . $user->name . ') deleted.';
                $log->save();
                return response()->json(["status" => 1, "message" => 'Admin Deleted Successfully.']);
            } else {
                return response()->json(["status" => 0, "error" => "Admin not found."]);
            }
        }
    }
}
