<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\QuotationUpload;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Log;
use App\Models\Setting;
use App\Models\TaskManagement;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;

class TaskManagementController extends Controller
{

    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }

    public function listTask(Request $request)
    {
        try {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            if ($accessToken->type == "customer") {
                $user = Customer::where('id', $accessToken->user_id)->first();
                $projectIdArray = Project::where('customer_id', $user->id)->pluck('id')->toArray();
                $tasks = TaskManagement::whereIn('project_id', $projectIdArray)->where('task_status', 'Pending')->get();
            } else {
                $user = User::where('id', $accessToken->user_id)->first();
                $role = $user->roles;
                $task_status = $request->task_status;
                $tasksQuery = TaskManagement::whereNull('deleted_at')->orderBy('id', 'desc');

                if ($role[0]->id == 1) {
                    $tasks = $tasksQuery->when($task_status, function ($query) use ($task_status) {
                        $query->where('task_status', $task_status);
                    })->get();
                } else {
                    $roleNames = $role[0]->name;
                    $tasks = $tasksQuery->where('task_type', $roleNames)->get();
                }
            }

            if (!blank($tasks)) {
                $array_push = array();
                foreach ($tasks as $task) {
                    if (!blank($task->project)) {
                        $array = array();
                        $array['id'] = $task->id;
                        $array['task'] = $task->task;
                        $project_data = array();
                        $project = Project::where('id', $task->project_id)->first();
                        if (!blank($project)) {
                            $project_data['id'] = $project->id;
                            $project_data['project_generated_id'] = $project->project_generated_id;
                            $project_data['customer_id'] = $project->customer_id;
                            $project_data['phone_number'] = $project->phone_number;
                            $project_data['architecture_name'] = $project->architecture_name;
                            $project_data['architecture_number'] = $project->architecture_number;
                            $project_data['supervisor_name'] = $project->supervisor_name;
                            $project_data['supervisor_number'] = $project->supervisor_number;
                            $project_data['email'] = $project->email;
                            $project_data['address'] = $project->address;
                            $project_data['city'] = $project->city;
                            $project_data['statename'] = $project->statename;
                            $project_data['zipcode'] = $project->zipcode;
                            $project_data['description'] = $project->description;
                            $project_data['start_date'] = ($project->start_date != NULL) ? date('d/m/Y', strtotime($project->start_date)) : "";
                            $project_data['measurement_date'] = ($project->measurement_date != NULL) ? date('d/m/Y', strtotime($project->measurement_date)) : "";
                            $project_data['reference_name'] = $project->reference_name;
                            $project_data['reference_phone'] = $project->reference_phone;
                            $project_data['status'] = $project->status;
                            $project_data['lead_no'] = $project->lead_no;
                            $project_data['lead_status'] = $project->lead_status;
                            $project_data['quotation_cost'] = $project->quotation_cost;
                            $project_data['project_cost'] = $project->project_cost;
                            $project_data['material_selection'] = $project->material_selection;
                            $project_data['reject_reason'] = $project->reject_reason;
                            $project_data['created_at'] = ($project->created_at != NULL) ? date('d/m/Y', strtotime($project->created_at)) : "";
                        }
                        $array['project_detail'] = $project_data;
                        $array['task_type'] = $task->task_type;
                        $array['task_status'] = $task->task_status;
                        $array['created_at'] = ($task->created_at != NULL) ? date('d/m/Y', strtotime($task->created_at)) : "";

                        array_push($array_push, $array);
                    }
                }
                return response()->json([
                    'status' => 1,
                    'task' => $array_push
                ], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Task Not Found.'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'error' => $th->getMessage()], 404);
        }
    }

    public function addTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required',
            'project_id' => 'required',
            'task_type' => 'required',
            'task_status' => 'required',
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
                $task = new TaskManagement();
                $task->task = $request->task;
                $task->project_id = $request->project_id;
                $task->task_type = $request->task_type;
                $task->task_status = $request->task_status;
                $task->save();

                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'TaskManagement';
                $log->log       = 'Task Created.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Task has been added successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function updateTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
            'task' => 'required',
            'project_id' => 'required',
            'task_type' => 'required',
            'task_status' => 'required',
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
                $task = TaskManagement::where('id', $request->task_id)->first();
                if (!blank($task)) {
                    $task->task = $request->task;
                    $task->project_id = $request->project_id;
                    $task->task_type = $request->task_type;
                    $task->task_status = $request->task_status;
                    $task->save();

                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'TaskManagement';
                    $log->log       = 'Task Updated.';
                    $log->save();
                    return response()->json(['status' => 1, 'message' => 'Task has been updated successfully.'], 200);
                } else {
                    return response()->json(['status' => 0, 'error' => 'Task doesnot exist.'], 404);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function deleteTask(Request $request, $id)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $task = TaskManagement::find($id);
        if (!blank($task)) {
            $task->delete();
            $log = new Log();
            $log->user_id   = $user->name;
            $log->module    = 'TaskManagement';
            $log->log       = 'Task Deleted.';
            $log->save();
            return response()->json(["status" => 1, "message" => 'Task has been Deleted Successfully.']);
        } else {
            return response()->json(["status" => 0, "error" => "Task not found."]);
        }
    }

    public function changeTaskStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
            'task_status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $task = TaskManagement::find($request->task_id);
            if (!blank($task)) {
                $task->task_status = $request->task_status;
                $task->save();

                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'TaskManagement';
                $log->log       = 'Task Status Changed.';
                $log->save();
                return response()->json(["status" => 1, "message" => 'Task Status changed successfully.']);
            } else {
                return response()->json(["status" => 0, "error" => "Task not found."]);
            }
        }
    }
}
