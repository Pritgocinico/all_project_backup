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
use App\Models\Feedback;
use App\Models\Feedbackuploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;

class FeedbackController extends Controller
{
    protected $world;
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }

    public function listFeedback(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
    
        $feedbacks = Feedback::where('deleted_at', null)->get();
        if(!blank($feedbacks)){
            $array_push = array();
            foreach($feedbacks as $feedback){
                $array = array();
                $array['id'] = $feedback->id;
                $array['project_id'] = $feedback->project_id;
                $array['customer_id'] = $feedback->customer_id;
                $array['customer_name'] = $feedback->customer_name;
                $array['email'] = $feedback->email;
                $array['phone'] = $feedback->phone;
                $array['comments'] = $feedback->comments;
                $array['rating'] = $feedback->rating;
                $array['created_at'] = ($feedback->created_at != NULL)?date('d/m/Y',strtotime($feedback->created_at)):"";
                
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'feedbacks'=> $array_push
            ],200);
        }else{
            return response()->json(['status'=>0,'error'=> 'Feedback Not Found.'],404);
        }
    }

    public function viewFeedback(Request $request, $id){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
    
        $feedback = Feedback::where('id', $id)->first();
        $array_push = array();
        if(!blank($feedback)){
            $array = array();
            $array['id'] = $feedback->id;
            $array['project_id'] = $feedback->project_id;
            $array['customer_id'] = $feedback->customer_id;
            $array['customer_name'] = $feedback->customer_name;
            $array['email'] = $feedback->email;
            $array['phone'] = $feedback->phone;
            $array['comments'] = $feedback->comments;
            $array['rating'] = $feedback->rating;
            $array['created_at'] = ($feedback->created_at != NULL)?date('d/m/Y',strtotime($feedback->created_at)):"";

            $feedback_uploads = Feedbackuploads::where('feedback_id', $feedback->id)->get();
                $f_uploads = array();
                if(!blank($feedback_uploads)){
                    foreach($feedback_uploads as $uploads){
                        $fuploads = array();
                        $fuploads['id'] = $uploads->id;
                        $fuploads['feedback_id'] = $uploads->feedback_id;
                        $fuploads['project_id'] = $uploads->project_id;
                        $fuploads['file'] = $uploads->file;
                        $fuploads['file_name'] = $uploads->file_name;
                        $fuploads['created_at'] = ($uploads->created_at != NULL)?date('d/m/Y',strtotime($uploads->created_at)):"";
                        array_push($f_uploads,$fuploads);
                    }
                }
            $array['feedback_uploads']     = $f_uploads;

            return response()->json(['status' => 1, 'feedback'=>$array],200);
        }else{
            return response()->json(['status'=>0,'error'=> 'Feedback Not Found.'],404);
        }
    }

    public function deleteFeedback(Request $request, $id){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
    
        $feedback = Feedback::where('id', $id)->first();
        if(!blank($feedback)){
            $feedback->delete();
            $feedback_upload = Feedbackuploads::where('feedback_id', $id)->delete();
            $log = new Log();
            $log->user_id   = $user->name;
            $log->module    = 'Feedback';
            $log->log       = 'Feedback Deleted.';
            $log->save();
            return response()->json(["status"=>1,"message"=>'Feedback Deleted Successfully.']);
        }else{
            return response()->json(["status"=>0,"error"=>"Feedback not found."]);
        }
    }
}
