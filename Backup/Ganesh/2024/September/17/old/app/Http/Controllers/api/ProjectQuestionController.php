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
use App\Models\ProjectCompleteImage;
use App\Models\ProjectQuestion;
use App\Models\QaDoneTask;

class ProjectQuestionController extends Controller
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
    
        $measurements = ProjectQuestion::where('question_type',3)->whereIn('project_id',[0,$request->project_id])->get();
        $array_push = array();
        if(!blank($measurements)){
            foreach($measurements as $measurement){
                $array = array();
                $array['id']            = $measurement->id;
                $array['question']          = ($measurement->question != NULL)?$measurement->question:"";
                $array['chk']         = ($measurement->chk != NULL)?$measurement->chk:"";
                $array['created_at']    = ($measurement->created_at != NULL)?date('d/m/Y',strtotime($measurement->created_at)):"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'qa_questons'=>$array_push
            ],200);
        }else{
                return response()->json(['status'=>0,'error'=> 'Measurement Users Not Found.'],404);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'qa_question' => 'required'
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
                $newQuestion = new ProjectQuestion();
                $newQuestion->project_id = $request->project_id;
                $newQuestion->question = $request->qa_question;
                $newQuestion->question_type = 3;
                $newQuestion->created_by = $request->user_id;
                $newQuestion->save();

                $workshopDoneTasks = QaDoneTask::where('project_id', $request->project_id)->get();
                if (!blank($workshopDoneTasks)) {
                    $workshopQuestion = new QaDoneTask();
                    $workshopQuestion->project_id = $request->project_id;
                    $workshopQuestion->question_id = $newQuestion->id;
                    $workshopQuestion->save();
                }
                $log = new Log();
                $log->user_id = $user->name;
                $log->module = 'Project - workshop';
                $log->log = 'Workshop question added.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Question has been added successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'qa_question' => 'required',
            'question_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $project = Project::where('id', $request->project_id)->first();
            if (!blank($project)) {
                $workshopQuestion = ProjectQuestion::where('id', $request->question_id)->first();
                if (!blank($workshopQuestion)) {
                    $workshopQuestion->question = $request->qa_question;
                    $workshopQuestion->save();
                    $log = new Log();
                    $log->user_id = $user->name;
                    $log->module = 'Project - workshop';
                    $log->log = 'Workshop question updated.';
                    $log->save();
                    return response()->json(['status' => 1, 'message' => 'Question has been updated successfully.'], 200);
                } else {
                    return response()->json(['status' => 0, 'error' => 'Question does not exist.'], 404);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
    public function delete(Request $request, $id)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $QaQuestion = ProjectQuestion::find($id);
        $QaTaskQuestion = QaDoneTask::where('question_id', $id)->first();
        if (!blank($QaQuestion) && blank($QaTaskQuestion)) {
            $QaQuestion->delete();
            if(isset($QaTaskQuestion)){
                $QaTaskQuestion->delete();
            }
            $log = new Log();
            $log->user_id = $user->name;
            $log->module = 'Project - workshop';
            $log->log = 'Workshop question deleted.';
            $log->save();
            return response()->json(["status" => 1, "message" => 'Question has been Deleted Successfully.']);
        } else {
            return response()->json(["status" => 0, "error" => "Question not found."]);
        }
    }

    public function checkQaQuestion(Request $request){
        $validator = Validator::make($request->all(), [
            'question_id' => 'required',
            'project_id' => 'required',
            'chk' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $projectData = Project::where('id', $request->project_id)->first();
            $qaTask = QaDoneTask::where('project_id', $request->project_id)->where('add_type',$projectData->add_type)->get();
            if(blank($qaTask)){
                $qaQuestions = ProjectQuestion::whereIn('project_id', [$request->project_id, 0])->where('question_type',3)->get();
                foreach($qaQuestions as $wQuestion){
                    $question = new QaDoneTask();
                    $question->project_id = $request->project_id;
                    $question->question_id =  $wQuestion->id;
                    $question->chk = 'off';
                    $question->save();
                }
                $updateQuestionChk = QaDoneTask::where('project_id', $request->project_id)->where('question_id', $request->question_id)->first();
                if(!blank($updateQuestionChk)){
                    $updateQuestionChk->chk = $request->chk;
                    $updateQuestionChk->add_type = $projectData->add_type;
                    $updateQuestionChk->save();
                    return response()->json(['status'=>1,'message'=> 'Qa question has been updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=> 'Something went wrong.'],404);
                }
            }{
                $updateQuestionChk = QaDoneTask::where('project_id', $request->project_id)->where('question_id', $request->question_id)->first();
                if(!blank($updateQuestionChk)){
                    $updateQuestionChk->chk = $request->chk;
                    $updateQuestionChk->add_type = $projectData->add_type;
                    $updateQuestionChk->save();
                    return response()->json(['status'=>1,'message'=> 'Qa question has been updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=> 'Something went wrong.'],404);
                }
            }
            return response()->json(['status'=>0,'error'=> 'Question not found.'],404);
        }
    }
}
