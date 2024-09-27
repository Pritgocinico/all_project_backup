<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AdditionalProjectDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\ProjectQuestion;
use App\Models\Customer;
use App\Models\WorkshopQuestion;
use App\Models\WorkshopDoneTask;
use App\Models\Project;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Invoicefile;
use App\Models\PartialDeliverDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use stdClass;
use App\Http\Helpers\SmsHelper;
use App\Models\GlassMeasurementFile;

class WorkshopController extends Controller
{

    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }

    public function listworkshopQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $workshopQuestions = ProjectQuestion::where('question_type','1')->whereIn('project_id', [$request->project_id, 0])->get();
            $workshopTasks = WorkshopDoneTask::where('project_id', $request->project_id)->get();
            if (!blank($workshopTasks)) {
                $array_push = array();
                foreach ($workshopTasks as $tasks) {
                    $array = array();
                    $array['id'] = $tasks->question_id;
                    $project_data = new stdClass();

                    $project = Project::where('id', $request->project_id)->first();
                    if (!blank($project)) {
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
                    $array['workshop_question'] = $tasks->wquestion->question;
                    $array['chk'] = $tasks->chk;
                    $array['created_by'] = $tasks->created_by;
                    $array['created_at'] = ($tasks->created_at != NULL) ? date('d/m/Y', strtotime($tasks->created_at)) : "";

                    array_push($array_push, $array);
                }
                return response()->json([
                    'status' => 1,
                    'project_id' => $request->project_id,
                    'workshop' => $array_push
                ], 200);
            } else {
                if (!blank($workshopQuestions)) {
                    $array_push = array();
                    foreach ($workshopQuestions as $workshop) {
                        $array = array();
                        $array['id'] = $workshop->id;
                        $project_data = new stdClass();

                        $project = Project::where('id', $request->project_id)->first();
                        if (!blank($project)) {
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
                        $array['workshop_question'] = $workshop->question;
                        $array['chk'] = $workshop->chk;
                        $array['created_by'] = $workshop->created_by;
                        $array['created_at'] = ($workshop->created_at != NULL) ? date('d/m/Y', strtotime($workshop->created_at)) : "";

                        array_push($array_push, $array);
                    }
                    return response()->json([
                        'status' => 1,
                        'project_id' => $request->project_id,
                        'workshop' => $array_push
                    ], 200);
                } else {
                    return response()->json(['status' => 0, 'error' => 'Workshop Not Found.'], 404);
                }
            }
        }
    }

    public function addworkshopQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'workshop_question' => 'required'
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
                $newQuestion->question = $request->workshop_question;
                $newQuestion->question_type = 1;
                $newQuestion->created_by = $request->user_id;
                $newQuestion->save();

                $workshopDoneTasks = WorkshopDoneTask::where('project_id', $request->project_id)->get();
                if (!blank($workshopDoneTasks)) {
                    $workshopQuestion = new WorkshopDoneTask();
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

    public function updateworkshopQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'workshop_question' => 'required',
            'question_id' => 'required',
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
                $workshopQuestion = ProjectQuestion::where('id', $request->question_id)->first();
                // $workshopDoneQuestion = WorkshopDoneTask::where('question_id', $request->question_id)->first();
                if (!blank($workshopQuestion)) {
                    $workshopQuestion->question = $request->workshop_question;
                    // $workshopQuestion->chk = $request->chk;
                    $workshopQuestion->save();
                    // if(!blank($workshopDoneQuestion)){
                    //     $workshopDoneQuestion->chk = $request->chk;
                    //     $workshopDoneQuestion->save();
                    // }
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

    public function deleteworkshopQuestion(Request $request, $id)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $workshopQuestion = ProjectQuestion::find($id);
        $workshopTaskQuestion = WorkshopDoneTask::where('question_id', $id)->first();
        if (!blank($workshopQuestion) && blank($workshopTaskQuestion)) {
            $workshopQuestion->delete();
            if(isset($workshopTaskQuestion)){
                $workshopTaskQuestion->delete();
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

    public function checkWorkshop(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required',
            'project_id' => 'required',
            'chk' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $projectData = Project::where('id', $request->project_id)->first();
            $workshopTask = WorkshopDoneTask::where('project_id', $request->project_id)->where('add_type', $projectData->add_type)->get();
            if (blank($workshopTask)) {
                $workshopQuestions = ProjectQuestion::where('question_type',1)->whereIn('project_id', [$request->project_id, 0])->get();
                foreach ($workshopQuestions as $wQuestion) {
                    $question = new WorkshopDoneTask();
                    $question->project_id = $request->project_id;
                    $question->question_id = $wQuestion->id;
                    $question->chk = 'off';
                    $question->save();
                }
                $updateQuestionchk = WorkshopDoneTask::where('project_id', $request->project_id)->where('question_id', $request->question_id)->first();
                if (!blank($updateQuestionchk)) {
                    $updateQuestionchk->chk = $request->chk;
                    $updateQuestionchk->add_type = $projectData->add_type;
                    $updateQuestionchk->save();
                    return response()->json(['status' => 1, 'message' => 'Workshop question has been updated successfully.'], 200);
                } else {
                    return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
                }
            } {
                $updateQuestionchk = WorkshopDoneTask::where('project_id', $request->project_id)->where('question_id', $request->question_id)->first();
                if (!blank($updateQuestionchk)) {
                    $updateQuestionchk->chk = $request->chk;
                    $updateQuestionchk->add_type = $projectData->add_type;
                    $updateQuestionchk->save();
                    return response()->json(['status' => 1, 'message' => 'Workshop question has been updated successfully.'], 200);
                } else {
                    return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
                }
            }
            return response()->json(['status' => 0, 'error' => 'Question not found.'], 404);
        }
    }

    public function updateCutting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'cutting_selection' => 'required',
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
                if ($request->cutting_selection == 1) {
                    $project->cutting_selection = $request->cutting_selection;
                    $project->cutting_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->cutting_date)->format('Y/m/d H:i:s');
                    $project->sub_step = "Cutting (5.1)";
                    $project->save();
                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->cutting_selection = $request->cutting_selection;
                        $additionalProject->cutting_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->cutting_date)->format('Y/m/d H:i:s');
                        $additionalProject->sub_step = "Cutting (5.1)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Cutting has been completed.'], 200);
                } else {
                    $project->cutting_selection = $request->cutting_selection;
                    $project->sub_step = "Cutting (5.1)";
                    $project->save();
                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->cutting_selection = $request->cutting_selection;
                        $additionalProject->cutting_date = null;
                        $additionalProject->sub_step = "Cutting (5.1)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Cutting not done.'], 200);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function updateShutter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'shutter_selection' => 'required',
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
                if ($request->shutter_selection == 1) {
                    $project->shutter_selection = $request->shutter_selection;
                    $project->shutter_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->shutter_date)->format('Y/m/d H:i:s');
                    $project->sub_step = "Shutter Joinery (5.2)";
                    $project->save();

                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->shutter_selection = $request->shutter_selection;
                        $additionalProject->shutter_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->shutter_date)->format('Y/m/d H:i:s');
                        $additionalProject->sub_step = "Shutter Joinery (5.2)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Shutter Joinery done.'], 200);
                } else {
                    $project->shutter_selection = $request->shutter_selection;
                    $project->sub_step = "Shutter Joinery (5.2)";
                    $project->save();

                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->shutter_selection = $request->shutter_selection;
                        $additionalProject->sub_step = "Shutter Joinery (5.2)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Shutter Joinery not done.'], 200);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function updateGlassmeasure(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'glass_measurement' => 'required',
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
                if ($request->glass_measurement == 1) {
                    $project->glass_measurement = $request->glass_measurement;
                    $project->glass_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->glass_date)->format('Y/m/d H:i:s');
                    $project->sub_step = "Glass Measurement (5.3)";
                    $project->save();
                    if ($request->hasFile('glassMeasurementFile')) {
                        $files = $request->file('glassMeasurementFile');
                        foreach ($files as $file) {
                            $filename = $file->getClientOriginalName();
                            $destinationPath = 'public/glassmeasurementfiles/';
                            $file_name = $filename;
                            $file->move($destinationPath, $file_name);

                            $g_file_photo = new GlassMeasurementFile();
                            $g_file_photo->project_id = $request->project_id;
                            $g_file_photo->file = $file_name;
                            $g_file_photo->save();
                        }
                    }
                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->glass_measurement = $request->glass_measurement;
                        $additionalProject->glass_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->glass_date)->format('Y/m/d H:i:s');
                        $additionalProject->sub_step = "Glass Measurement (5.3)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Glass Measurement done.'], 200);
                } else {
                    $project->glass_measurement = $request->glass_measurement;
                    $project->sub_step = "Glass Measurement (5.3)";
                    $project->save();

                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->glass_measurement = $request->glass_measurement;
                        $additionalProject->sub_step = "Glass Measurement (5.3)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Glass Measurement not done.'], 200);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function updateGlassReceive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'glass_receive' => 'required',
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
                if ($request->glass_receive == 1) {
                    $project->glass_receive = $request->glass_receive;
                    $project->glass_receive_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->glass_receive_date)->format('Y/m/d H:i:s');
                    $project->sub_step = "Glass Received (5.4)";
                    $project->save();

                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->glass_receive = $request->glass_receive;
                        $additionalProject->glass_receive_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->glass_receive_date)->format('Y/m/d H:i:s');
                        $additionalProject->sub_step = "Glass Received (5.4)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Glass Received.'], 200);
                } else {
                    $project->glass_receive = $request->glass_receive;
                    $project->sub_step = "Glass Received (5.4)";
                    $project->save();
                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->glass_receive = $request->glass_receive;
                        $additionalProject->sub_step = "Glass Received (5.4)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Glass not Received.'], 200);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function updateShutterReady(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'shutter_ready' => 'required',
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
                if ($request->shutter_ready == 1) {
                    $project->shutter_ready = $request->shutter_ready;
                    $project->shutter_ready_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->shutter_ready_date)->format('Y/m/d H:i:s');
                    $project->sub_step = "Glass fitting (5.5)";
                    $project->save();
                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->shutter_ready = $request->shutter_ready;
                        $additionalProject->shutter_ready_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->shutter_ready_date)->format('Y/m/d H:i:s');
                        $additionalProject->sub_step = "Glass fitting (5.5)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Shutter ready with glass fitting.'], 200);
                } else {
                    $project->shutter_ready = $request->shutter_ready;
                    $project->sub_step = "Glass fitting (5.5)";
                    $project->save();
                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->shutter_ready = $request->shutter_ready;
                        $additionalProject->sub_step = "Glass fitting (5.5)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Shutter not ready with glass fitting.'], 200);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function storeMaterialStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'material_delivered' => 'required',
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
                if ($request->material_delivered == 1) {
                    $project->material_delivered = $request->material_delivered;
                    $project->delivered_by = $request->delivered_by;
                    $project->driver_number = $request->driver_number;
                    $project->deliver_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->deliver_date)->format('Y/m/d H:i:s');
                    $project->sub_step = "Material (5.7)";
                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->material_delivered = $request->material_delivered;
                        $additionalProject->delivered_by = $request->delivered_by;
                        $additionalProject->driver_number = $request->driver_number;
                        $additionalProject->deliver_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->deliver_date)->format('Y/m/d H:i:s');
                        $additionalProject->sub_step = "Material (5.7)";
                        $additionalProject->save();
                    }
                    $project->save();
                    try {
                        $userDetail = Customer::where('id', $project->customer_id)->first();
                        $mobileNumber = $userDetail->phone;
                        $message = "Dear " . $userDetail->name . ", your material has been dispatched from the workshop and will be delivered by " . $request->driver_numbers . ". You can call him for the status of the delivery on " . $request->deliver_date . ". Shree Ganesh Aluminum";
                        $templateid = '1407171594318987872';
                        $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                        if ($project->driver_number) {
                            $isSent = SmsHelper::sendSms($project->driver_number, $message, $templateid, false);
                        }
                    } catch (Exception $e) {
                        echo 'Message:' . $e->getMessage();
                    }
                    return response()->json(['status' => 1, 'message' => 'Material Delivered.'], 200);
                } elseif ($request->material_delivered == 0) {
                    $project->material_delivered = $request->material_delivered;
                    $project->sub_step = "Material (5.7)";
                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->material_delivered = $request->material_delivered;
                        $additionalProject->sub_step = "Material (5.7)";
                        $additionalProject->save();
                    }
                    $project->save();
                    try {
                        $message = "Material delivery of the " . $project->name . " is not dispatched yet from workshop kindly complete the delivery within next 24 hours. Shree Ganesh Aluminum";
                        $templateid = '1407171594323827401';
                        $filltingUser = User::where('role', '5')->get();
                        foreach ($filltingUser as $key => $fitting) {
                            $mobile = $fitting->phone;
                            $status = false;
                            if ($key == 0) {
                                $status = true;
                            }
                            $isSent = SmsHelper::sendSms($mobile, $message, $templateid, $status);
                        }
                    } catch (Exception $e) {
                        echo 'Message:' . $e->getMessage();
                    }
                    return response()->json(['status' => 1, 'message' => 'Material not delivered.'], 200);
                } else {
                    $project = Project::where('id', $request->project_id)->first();
                    $project->material_delivered = $request->material_delivered;
                    $project->sub_step = "Material (5.7)";
                    $project->save();
                    $partialDetails = new PartialDeliverDetail();
                    $partialDetails->project_id = $request->project_id;
                    $partialDetails->partial_deliver_by = $request->delivered_by;
                    $partialDetails->driver_number = $request->driver_number;
                    $partialDetails->partial_deliver_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->deliver_date)->format('Y/m/d H:i:s');
                    $partialDetails->save();
                    $userDetail = Customer::where('id', $project->customer_id)->first();
                    $mobileNumber = $userDetail->phone;
                    $message = "Dear " . $userDetail->name . ", your material has been dispatched from the workshop and will be delivered by " . $request->driver_numbers . ". You can call him for the status of the delivery on " . $request->deliver_date . ". Shree Ganesh Aluminum";
                    $templateid = '1407171594318987872';
                    $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                    if ($partialDetails->driver_number) {
                        $isSent = SmsHelper::sendSms($partialDetails->driver_number, $message, $templateid, false);
                    }
                    return response()->json(['status' => 1, 'message' => 'Partially delivered.'], 200);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function deletePartial(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'partial_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $partialData = PartialDeliverDetail::where('id', $id)->first();
            if (!blank($partialData)) {
                $partialData->delete();

                return response()->json(['status' => 1, 'message' => 'Partial deliver data deleted.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Data not found.'], 404);
            }
        }
    }

    public function storeInvoiceStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'invoice_status' => 'required',
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
                if ($request->invoice_status == 1) {
                    $project->invoice_status = $request->invoice_status;
                    $project->invoice_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->invoice_date)->format('Y/m/d H:i:s');
                    $project->sub_step = "Material (5.6)";
                    $project->save();
                    if ($request->hasFile('invoicefiles')) {
                        $allowedfileExtension = ['jpg', 'png', 'jpeg', 'pdf'];
                        $files = $request->file('invoicefiles');

                        foreach ($files as $file) {
                            $filename = $file->getClientOriginalName();
                            $destinationPath = 'public/invoicefiles/';
                            $extension = $file->getClientOriginalExtension();
                            $file_name = $filename;
                            $file->move($destinationPath, $file_name);

                            $invoiceFile = new Invoicefile();
                            $invoiceFile->project_id = $request->project_id;
                            $invoiceFile->invoice = $file_name;
                            $invoiceFile->save();
                        }
                    }
                    try {
                        $route = route('projectView', $project->id);
                        $userDetail = Customer::where('id', $project->customer_id)->first();
                        $mobileNumber = $userDetail->phone;
                        $message = "Dear " . $userDetail->name . ", your invoice has been generated for your " . $project->project_generated_id . ", kindly check your project details to this link " . $route . ". Shree Ganesh Aluminum";
                        $templateid = '1407171594311415919';
                        $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                    } catch (Exception $e) {
                        echo 'Message:' . $e->getMessage();
                    }

                    return response()->json(['status' => 1, 'message' => 'Invoice Added.'], 200);
                } else {
                    $project->invoice_status = $request->invoice_status;
                    $project->sub_step = "Material (5.6)";
                    $project->save();
                    return response()->json(['status' => 1, 'message' => 'Invoice not generated.'], 200);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
}
