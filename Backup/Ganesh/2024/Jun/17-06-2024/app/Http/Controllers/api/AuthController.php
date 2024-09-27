<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
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
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    // User Login
    public function loginUser(Request $req){
        $input = $req->all();
        $validator = Validator::make($req->all(), [
           'email'               => 'required',
           'password'            => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error_type'=>4,'error'=>$validator->errors()], 404);
        }else{
            $fieldType = filter_var($req->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            $credentials = $req->only($fieldType, 'password');
            if (Auth::attempt(array($fieldType => $input['email'], 'password' => $input['password']))) {
               $role = Auth::user()->role;
               $user=Auth::user();
                if($user->role == 1 || $user->role == 3 || $user->role == 5 || $user->role == 6 || $user->role == 8){
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
                            $array['id'] = $user->id;
                            if(!blank($user->image)){
                                $array['image'] = url('/').'/public/users/'.$user->image;
                            }else{
                                $array['image'] = '';
                            }
                            $array['role']          = $user->role;
                            $array['name']          = $user->name;
                            $array['email']         = $user->email;
                            $array['phone']         = $user->phone;
                            $array['status']        = $user->status;
                            // $accessToken = AccessToken::updateOrCreate(
                            //     [ 'user_id' => $user->id ],
                            //     [ 'access_token' => Str::random(255) ]
                            // );
                            $accessToken = new AccessToken();
                            $accessToken->user_id = $user->id;
                            $accessToken->access_token = Str::random(255);
                            $accessToken->save();
                            if($user->role == 1){
                                $projects = Project::all();
                                foreach($projects as $project){
                                    $timestamp = $project->created_at;

                                    // Convert the timestamp to a Carbon instance
                                    $createdAt = Carbon::parse($timestamp);

                                    // Get the time in 24-hour format (e.g., 13:45:00)
                                    $time = $createdAt->format('H:i:s');
                                    // Get the current time as a Carbon instance
                                    $currentTime = Carbon::now();

                                    // Calculate the difference in hours
                                    $diffInHours = $createdAt->diffInHours($currentTime);
                                    if ($diffInHours === 24) {
                                        $measurement = Measurement::where('project_id', $project->id)->get();
                                    
                                        if(blank($measurement)){
                                            $task = TaskManagement::where('project_id', $project->id)->where('task_type', 'measurement')->first();
                                            if(blank($task)){
                                                $measurement_task = new TaskManagement();
                                                $measurement_task->task = 'Measurement not taken';
                                                $measurement_task->project_id = $project->id;
                                                $measurement_task->task_type = 'measurement';
                                                $measurement_task->task_status = 'pending';
                                                $measurement_task->task_date = Carbon::now();
                                                $measurement_task->save();
                                            }
                                        }else{
                                            $quotation = Quotation::where('project_id', $project->id)->get();
                                            if(blank($quotation)){
                                                $task = TaskManagement::where('project_id', $project->id)->where('task_type', 'quotation')->first();
                                                if(blank($task)){
                                                    $quotation_task = new TaskManagement();
                                                    $quotation_task->task = 'Quotation not taken';
                                                    $quotation_task->project_id = $project->id;
                                                    $quotation_task->task_type = 'quotation';
                                                    $quotation_task->task_status = 'pending';
                                                    $quotation_task->task_date = Carbon::now();
                                                    $quotation_task->save();
                                                }
                                            }
                                        }
                                    }
                        
                                }
                            }
                            return response()->json(['status'=>1,'access_token' =>  $accessToken->access_token ,'user_details'=>$array],200);
                        }else{
                            return response()->json(['status'=>0,'error_type'=>1,'error'=>'User Not Found.'],404);
                        }
                    }else{
                        return response()->json(['status'=>0,'error_type'=>2,'error'=>'User Not Verified.'],404);
                    }
                }
            }else{
               return response()->json(['status'=>0,'error_type'=>3,'error'=>'Wrong Username or Password.'],200);
            }
        }
    }

    //Admin Dashboard
    public function Dashboard(Request $request){
        echo "Dashboard";
    }

    public function storeFeedback(Request $request){
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
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $project = Project::where('id', $request->project_id)->first();
            if(!blank($project)){
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
                return response()->json(['status'=>1,'message'=> 'Feedback has been added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=> 'Something went wrong.'],404);
            }
        }
    }
}
