<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\Role;
use App\Models\User;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Project;
use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Feedbackuploads;
use App\Models\Measurement;
use App\Models\TaskManagement;
use App\Models\Quotation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;

class AuthController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }
    // User Login
    public function loginUser(Request $req)
    {
        $input = $req->all();
        $validator = Validator::make($req->all(), [
            'email'               => 'required',
            'password'            => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error_type' => 4, 'error' => $validator->errors()], 404);
        } else {
            $fieldType = filter_var($req->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            if ($fieldType === 'email') {
                $fieldTypeData = 'email';
            } elseif (is_numeric($req->email)) {
                $fieldTypeData = 'phone';
            } else {
                $fieldTypeData = 'user_id';
            }
            // Check Admin

            if ($fieldTypeData == "user_id") {
                $user = Customer::where($fieldTypeData, $req->email)->first();
                $type = 'customer';
                $loginColumn = 'last_login_at';
                $loginColumnIp = 'last_login_ip_address';
                $status = isset($user) ? $user->app_user_active !== 0 : true;
                $status1 = true;
                $role = true;
            } else {
                $user = User::where($fieldTypeData, $req->email)->first();
                $type = 'user';
                $loginColumn = 'last_login_at';
                $loginColumnIp = 'last_login_ip';
                $status = isset($user) ? $user->app_user_active !== 0 : true;
                $status1 = isset($user) ? $user->status == 1 : true;
                $role = isset($user) ? in_array($user->role, [1, 3, 5, 6, 8]) : true;
            }
            if ($user && Hash::check($req->password, $user->password)) {
                if ($role && $status1 && $status) {
                    $user->update([
                        $loginColumn => Carbon::now()->toDateTimeString(),
                        $loginColumnIp => $req->getClientIp()
                    ]);

                    $log = new Log();
                    $log->user_id = $user->id;
                    $log->module = 'Login';
                    $log->log = $user->name . ' Logged in Successfully';
                    $log->save();

                    $array = [
                        'id' => $user->id,
                        'image' => $user->image ? url('/') . '/public/users/' . $user->image : '',
                        'role' => $user->role,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'status' => $user->status,
                    ];

                    $accessToken = new AccessToken();
                    $accessToken->user_id = $user->id;
                    $accessToken->access_token = Str::random(255);
                    $accessToken->type = $type;
                    $accessToken->save();

                    // Additional logic for role 1 (e.g., handling projects, measurements, etc.)
                    if ($user->role == 1) {
                        // Your existing logic here
                    }

                    return response()->json(['status' => 1, 'access_token' => $accessToken->access_token, 'user_details' => $array], 200);
                } else {
                    return response()->json(['status' => 0, 'error_type' => 2, 'error' => 'User Not Verified.'], 404);
                }
            } else {
                return response()->json(['status' => 0, 'error_type' => 3, 'error' => 'Wrong Username or Password.'], 200);
            }
        }
    }

    //Admin Dashboard
    public function Dashboard(Request $request)
    {
        echo "Dashboard";
    }

    public function storeFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id'    => 'required',
            'customer_id'   => 'required',
            'name'          => 'required',
            'email'         => 'required',
            'phone'         => 'required',
            'comment'       => 'required',
            'rating'        => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $project = Project::where('id', $request->project_id)->first();
            if (!blank($project)) {
                $feedback = new Feedback();
                $feedback->customer_id = $request->customer_id;
                $feedback->project_id = $request->project_id;
                $feedback->customer_name = $request->name;
                $feedback->email = $request->email;
                $feedback->phone = $request->phone;
                $feedback->comments = $request->comment;
                $feedback->rating = $request->rating;
                $feedback->save();

                if ($request->hasFile('feedbackfile')) {
                    $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx'];
                    $files = $request->file('feedbackfile');

                    foreach ($files as $file) {
                        $filename = $file->getClientOriginalName();
                        $destinationPath = 'public/feedback/';
                        $extension = date('dmYHis') . "." . $file->getClientOriginalExtension();
                        $file_name = $filename;
                        $file->move($destinationPath, $file_name);

                        $feedback_upload = new Feedbackuploads();
                        $feedback_upload->feedback_id          = $feedback->id;
                        $feedback_upload->project_id           = $request->project_id;
                        $feedback_upload->file                 = $extension;
                        $feedback_upload->file_name            = $file_name;
                        $feedback_upload->save();
                    }
                }
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Feedback';
                $log->log       = 'Feedback submitted by Customer.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Feedback has been added successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function getRoleList()
    {
        $role = Role::select('id', 'name')->whereIn('id', ['3', '5', '6', '8'])->get();
        return response()->json(['status' => 1, 'data' => $role], 200);
    }

    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'password'       => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $projectCount = Customer::all()->last()->id;
            $projectCount++;
            $projectIdPadding = str_pad($projectCount, 5, '0', STR_PAD_LEFT);
            $customerId = 'SGA_CUS' . '_' . $projectIdPadding;
            $user = new Customer();
            $user->role = 9;
            $user->name = $request->name;
            $user->customer_id = $customerId;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->original_password = $request->password;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->zipcode = $request->zip_code;
            $user->app_user_active = 0;
            $user->password = Hash::make($request->password);
            $insert = $user->save();
            if ($insert) {
                return response()->json(['status' => 1, 'message' => 'Your account should be processed and active within 24 hours. Once you receive your "Account Approval Notification" SMS, you can start use app.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 200);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            if (isset($user)) {
                if (Hash::check($request->old_password, $user->password)) {
                    $user->password = Hash::make($request->new_password);
                    $user->save();

                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Change Password';
                    $log->log       = 'Password Changed Successfully.';
                    $log->save();
                    return response()->json(['result' => 200, 'status' => 1, 'description' => 'Password change successfully.'], 200);
                } else {
                    return response()->json(['result' => 200, 'status' => 0, 'description' => 'Old password is Incorrect!!!'], 200);
                }
            } else {
                return response()->json(['result' => 200, 'status' => 0, 'description' => 'Something went wrong.'], 200);
            }
        }
    }
    public function deleteAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            if ($accessToken->type == 'customer') {
                $user = Customer::where('id', $accessToken->user_id)->first();
            }
            if (isset($user)) {
                $delete = $user->delete();
                if ($delete) {
                    return response()->json(['status' => 1, 'message' => 'Your account has been deleted successfully.'], 200);
                } else {
                    return response()->json(['status' => 0, 'error' => 'User Not Found!'], 404);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            if ($accessToken->type == 'customer') {
                $user = Customer::where('id', $accessToken->user_id)->first();
            }
            if (isset($user)) {
                $user->email = $request->email;
                if ($request->password) {
                    $user->password = Hash::make($request->password);
                    $user->original_password = $request->password;
                }
                $update = $user->save();
                if ($update) {
                    return response()->json(['status' => 1, 'message' => 'Profile updated successfully.'], 200);
                }
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
            return response()->json(['status' => 0, 'error' => 'User Not Found.'], 404);
        }
    }
}
