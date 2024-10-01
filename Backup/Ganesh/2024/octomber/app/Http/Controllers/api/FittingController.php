<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\ProjectQuestion;
use App\Models\Quotationfile;
use App\Models\QuotationUpload;
use App\Models\Customer;
use App\Models\FittingQuestion;
use App\Models\FittingDoneTask;
use App\Models\Project;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Sitephotos;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use stdClass;

class FittingController extends Controller
{

    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }

    public function listfittingQuestion(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $fittingQuestions = ProjectQuestion::where('question_type',2)->whereIn('project_id', [$request->project_id, 0])->get();
            $fittingTasks = FittingDoneTask::where('project_id', $request->project_id)->get();
            if(!blank($fittingTasks)){
                $array_push = array();
                foreach($fittingTasks as $task){
                    $array = array();
                    $array['id']                                = $task->question_id;
                    $project_data = new stdClass();

                    $project = Project::where('id', $request->project_id)->first();
                    if(!blank($project)){
                        $project_data->id = $project->id;
                        $project_data->project_generated_id = $project->project_generated_id;
                        $project_data->customer_id = $project->customer_id;
                        $project_data->phone_number = $project->phone_number;
                        $project_data->architecture_name = $project->architecture_name;
                        $project_data->architecture_number = $project->architecture_number;
                        $project_data->supervisor_name = $project->supervisor_name;
                        $project_data->supervisor_number = $project->supervisor_number;
                        $project_data->email = $project->email;
                        $project_data->address = $project->address;
                        $project_data->city = $project->city;
                        $project_data->statename = $project->statename;
                        $project_data->zipcode = $project->zipcode;
                        $project_data->description = $project->description;
                        $project_data->start_date = ($project->start_date != NULL) ? date('d/m/Y', strtotime($project->start_date)) : "";
                        $project_data->measurement_date = ($project->measurement_date != NULL) ? date('d/m/Y', strtotime($project->measurement_date)) : "";
                        $project_data->reference_name = $project->reference_name;
                        $project_data->reference_phone = $project->reference_phone;
                        $project_data->status = $project->status;
                        $project_data->lead_no = $project->lead_no;
                        $project_data->lead_status = $project->lead_status;
                        $project_data->quotation_cost = $project->quotation_cost;
                        $project_data->project_cost = $project->project_cost;
                        $project_data->material_selection = $project->material_selection;
                        $project_data->reject_reason = $project->reject_reason;
                        $project_data->created_at = ($project->created_at != NULL) ? date('d/m/Y', strtotime($project->created_at)) : "";
                    }
                    $array['project_detail'] = $project_data;
                    $array['fitting_question'] = $task->question->question;
                    $array['chk'] = $task->chk;
                    $array['created_by'] = $task->created_by;
                    $array['created_at'] = ($task->created_at != NULL)?date('d/m/Y',strtotime($task->created_at)):"";

                    array_push($array_push,$array);
                }
                return response()->json([
                    'status' => 1,
                    'project_id' => $request->project_id,
                    'fitting'=> $array_push
                ],200);
            }else{
                if(!blank($fittingQuestions)){
                    $array_push = array();
                    foreach($fittingQuestions as $fitting){
                        $array = array();
                        $array['id']                                = $fitting->id;
                        $project_data = new stdClass();

                        $project = Project::where('id', $request->project_id)->first();
                        if(!blank($project)){
                            $project_data->id = $project->id;
                            $project_data->project_generated_id = $project->project_generated_id;
                            $project_data->customer_id = $project->customer_id;
                            $project_data->phone_number = $project->phone_number;
                            $project_data->architecture_name = $project->architecture_name;
                            $project_data->architecture_number = $project->architecture_number;
                            $project_data->supervisor_name = $project->supervisor_name;
                            $project_data->supervisor_number = $project->supervisor_number;
                            $project_data->email = $project->email;
                            $project_data->address = $project->address;
                            $project_data->city = $project->city;
                            $project_data->statename = $project->statename;
                            $project_data->zipcode = $project->zipcode;
                            $project_data->description = $project->description;
                            $project_data->start_date = ($project->start_date != NULL) ? date('d/m/Y', strtotime($project->start_date)) : "";
                            $project_data->measurement_date = ($project->measurement_date != NULL) ? date('d/m/Y', strtotime($project->measurement_date)) : "";
                            $project_data->reference_name = $project->reference_name;
                            $project_data->reference_phone = $project->reference_phone;
                            $project_data->status = $project->status;
                            $project_data->lead_no = $project->lead_no;
                            $project_data->lead_status = $project->lead_status;
                            $project_data->quotation_cost = $project->quotation_cost;
                            $project_data->project_cost = $project->project_cost;
                            $project_data->material_selection = $project->material_selection;
                            $project_data->reject_reason = $project->reject_reason;
                            $project_data->created_at = ($project->created_at != NULL) ? date('d/m/Y', strtotime($project->created_at)) : "";
                        }
                        $array['project_detail'] = $project_data;
                        $array['fitting_question'] = $fitting->question;
                        $array['chk'] = $fitting->chk;
                        $array['created_by'] = $fitting->created_by;
                        $array['created_at'] = ($fitting->created_at != NULL)?date('d/m/Y',strtotime($fitting->created_at)):"";
    
                        array_push($array_push,$array);
                    }
                    return response()->json([
                        'status' => 1,
                        'project_id' => $request->project_id,
                        'fitting'=> $array_push
                    ],200);
                }else{
                    return response()->json(['status'=>0,'error'=> 'Fitting Not Found.'],404);
                }
            }
        }
    }

    public function addfittingQuestion(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'fitting_question' => 'required'
        ]);
        if(!blank($request->user_id)){
            $user = User::where('id', $request->user_id)->first();
            $role = $user->role;
        }
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $project = Project::where('id',$request->project_id)->first();
            if(!blank($project)){
                $newQuestion = new ProjectQuestion();
                $newQuestion->project_id = $request->project_id;
                $newQuestion->question = $request->fitting_question;
                $newQuestion->question_type = 2;
                $newQuestion->created_by = $request->user_id;
                $newQuestion->save();

                $fittingDoneTasks = FittingDoneTask::where('project_id', $request->project_id)->get();
                if(!blank($fittingDoneTasks)){
                    $fittingQuestion = new FittingDoneTask();
                        $fittingQuestion->project_id = $request->project_id;
                        $fittingQuestion->question_id = $newQuestion->id;
                        $fittingQuestion->save();
                }
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Project';
                $log->log       = 'Fitting question added.';
                $log->save();
                return response()->json(['status'=>1,'message'=> 'Question has been added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=> 'Something went wrong.'],404);
            }
        }
    }

    public function updatefittingQuestion(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'fitting_question' => 'required',
            'question_id' => 'required',
        ]);
        if(!blank($request->user_id)){
            $user = User::where('id', $request->user_id)->first();
            $role = $user->role;
        }
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $project = Project::where('id',$request->project_id)->first();
            // $fittingDoneQuestion = FittingDoneTask::where('question_id', $request->question_id)->first();
            if(!blank($project)){
                $fittingQuestion = ProjectQuestion::where('question_type',2)->where('id', $request->question_id)->first();
                if(!blank($fittingQuestion)){
                    $fittingQuestion->fitting_question = $request->fitting_question;
                    // $fittingQuestion->chk = $request->chk;
                    $fittingQuestion->save();
                    // if(!blank($fittingDoneQuestion)){
                    //     $fittingDoneQuestion->chk = $request->chk;
                    //     $fittingDoneQuestion->save();
                    // }
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Project';
                    $log->log       = 'Fitting question updated.';
                    $log->save();
                    return response()->json(['status'=>1,'message'=> 'Question has been updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=> 'Question does not exist.'],404);
                }
            }else{
                return response()->json(['status'=>0,'error'=> 'Something went wrong.'],404);
            }
        }
    }

    public function deletefittingQuestion(Request $request, $id){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
    
        $fittingQuestion = ProjectQuestion::find($id);
        $fittingTaskQuestion = FittingDoneTask::where('question_id', $id)->first();
        if(!blank($fittingQuestion) && !blank($fittingTaskQuestion)){
            $fittingQuestion->delete();
            $fittingTaskQuestion->delete();
            $log = new Log();
            $log->user_id   = $user->name;
            $log->module    = 'Project';
            $log->log       = 'Fitting question deleted.';
            $log->save();
            return response()->json(["status"=>1,"message"=>'Question has been Deleted Successfully.']);
        }else{
            return response()->json(["status"=>0,"error"=>"Question not found."]);
        }
    }

    public function checkFitting(Request $request){
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
            $fittingTask = FittingDoneTask::where('project_id', $request->project_id)->where('add_type',$projectData->add_type)->get();
            if(blank($fittingTask)){
                $fittingQuestions = FittingQuestion::whereIn('project_id', [$request->project_id, 0])->get();
                foreach($fittingQuestions as $wQuestion){
                    $question = new FittingDoneTask();
                    $question->project_id = $request->project_id;
                    $question->question_id =  $wQuestion->id;
                    $question->chk = 'off';
                    $question->save();
                }
                $updateQuestionchk = FittingDoneTask::where('project_id', $request->project_id)->where('question_id', $request->question_id)->first();
                
                if(!blank($updateQuestionchk)){
                    $updateQuestionchk->chk = $request->chk;
                    $updateQuestionchk->add_type = $projectData->add_type;
                    $updateQuestionchk->save();
                    return response()->json(['status'=>1,'message'=> 'Fitting question has been updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=> 'Something went wrong.'],404);
                }
            }{
                $updateQuestionchk = FittingDoneTask::where('project_id', $request->project_id)->where('question_id', $request->question_id)->first();
                if(!blank($updateQuestionchk)){
                    $updateQuestionchk->chk = $request->chk;
                    $updateQuestionchk->add_type = $projectData->add_type;
                    $updateQuestionchk->save();
                    return response()->json(['status'=>1,'message'=> 'Fitting question has been updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=> 'Something went wrong.'],404);
                }
            }
            return response()->json(['status'=>0,'error'=> 'Question not found.'],404);
        }
    }

    public function fittingSitephotos(Request $request){
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'sitephotos' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $project = Project::where('id',$request->project_id)->first();
            if(!blank($project)){
                if ($request->hasFile('sitephotos')) {
                    $allowedfileExtension = ['jpg', 'png', 'jpeg', 'pdf'];
                    $files = $request->file('sitephotos');
    
                    foreach ($files as $file) {
                        $filename = $file->getClientOriginalName();
                        $destinationPath = 'public/sitephoto/';
                        $extension = $file->getClientOriginalExtension();
                        $file_name = $filename;
                        $file->move($destinationPath, $file_name);

                        $f_sitephoto = new Sitephotos();
                        $f_sitephoto->project_id = $request->project_id;
                        $f_sitephoto->site_photo = $file_name;
                        $f_sitephoto->created_by = $user->id;
                        $f_sitephoto->save();
                    }
                    return response()->json(['status'=>1,'message'=> 'Sitephoto uploaded successfully.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=> 'Something went wrong.'],404);
            }
        }
    }

}
