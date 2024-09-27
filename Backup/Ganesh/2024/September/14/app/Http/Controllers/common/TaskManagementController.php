<?php

namespace App\Http\Controllers\common;

use Nnjeim\World\WorldHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\Fitting;
use App\Models\TaskManagement;
use App\Models\Workshop;
use App\Models\Log;
use App\Models\WorkshopQuestion;
use App\Models\WorkshopDoneTask;
use App\Models\FittingQuestion;
use App\Models\FittingDoneTask;
use App\Models\Purchase;
use App\Models\Setting;
use Nnjeim\World\World;
use Carbon\Carbon;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;

class TaskManagementController extends Controller
{
    protected $world;
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }

    public function taskmanagement(){
        $taskStatus = request('task_status');
        $tasks = TaskManagement::where('deleted_at', null)->when($taskStatus,function($query)use($taskStatus){
            $query->where('task_status',$taskStatus);
        })->orderBy('id','desc')->get();
        $page = 'TaskManagement';
        if(Auth::user()->role == 1){
            return view('admin.taskmanagement.taskmanagement', compact('tasks', 'page'));
        }else if(Auth::user()->role == 8) {
            return view('purchase.taskmanagement.taskmanagement', compact('tasks', 'page'));
        }
        return view('quotation.taskmanagement.taskmanagement', compact('tasks', 'page'));
    }

    public function addTask(){
        $projects = Project::whereIn('type', [0,1])->get();
        if(Auth::user()->role == 1){
            return view('admin.taskmanagement.add_task', compact('projects'));
        }elseif(Auth::user()->role == 8) {
            return view('purchase.taskmanagement.add_task', compact('projects'));
        }
        return view('quotation.taskmanagement.add_task', compact('projects'));
    }

    public function storetask(Request $request){
        $request->validate([
            'task' => 'required',
            'project_id' => 'required',
            'task_type' => 'required',
            'status' => 'required',
            'taskDate' => 'required',
        ]);
        
        $tasks = new TaskManagement();
        $tasks->task = $request->task;
        $tasks->project_id = $request->project_id;
        $tasks->task_type = $request->task_type;
        $tasks->task_status = $request->status;
        // $tasks->task_date = date("Y/m/d", strtotime($request->taskDate));
        $tasks->task_date = Carbon::createFromFormat('Y-m-d\TH:i', $request->taskDate)->format('Y/m/d h:i:s');
        // dd($request->taskDate, $tasks->task_date);
        $tasks->save();

        $user = User::where('id',Auth::user()->id)->first();
        if(Auth::user()->role == 1){
            $notificationData = [
                'type' => 'message',
                'title' => 'Task has been Added.',
                'text' => 'Task: '.$tasks->task_type.'- Task',
                'url' => route('task-management'),
            ];
      
            Notification::send($user, new OffersNotification($notificationData));
        }else{
            $notificationData = [
                'type' => 'message',
                'title' => 'Task has been Added.',
                'text' => 'Task: '.$tasks->task_type.'- Task',
                'url' => route('quotation_task-management'),
            ];
      
            Notification::send($user, new OffersNotification($notificationData));
        }
        

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Task Management - ';
        $log->log       = 'Task Created';
        $log->save();

        if($request->task_type == 'workshop'){
            $workshopQuestion = new WorkshopQuestion();
            $workshopQuestion->project_id = $request->project_id;
            $workshopQuestion->workshop_question = $request->task;
            $workshopQuestion->created_by = auth()->user()->id;
            $workshopQuestion->save();

            $workshopTask = new WorkshopDoneTask();
            $workshopTask->project_id = $request->project_id;
            $workshopTask->question_id = $workshopQuestion->id;
            if($request->status == 'pending'){
                $workshopTask->chk = 'off';
            }else{
                $workshopTask->chk = 'on';
            }
            $workshopTask->save();
        }elseif($request->task_type == 'fitting'){
            $fittingQuestion = new FittingQuestion();
            $fittingQuestion->project_id = $request->project_id;
            $fittingQuestion->fitting_question = $request->task;
            $fittingQuestion->created_by = auth()->user()->id;
            $fittingQuestion->save();

            $fittingTask = new FittingDoneTask();
            $fittingTask->project_id = $request->project_id;
            $fittingTask->question_id = $fittingQuestion->id;
            if($request->task_status == 'pending'){
                $fittingTask->chk = 'off';
            }else{
                $fittingTask->chk = 'on';
            }
            $fittingTask->save();
        }

        if(Auth::user()->role == 1){
            return redirect()->route('task-management')->with('success', 'Task created successfully.');
        }else if(Auth::user()->role == 8) {
            return redirect()->route('purchase_task-management')->with('success', 'Task created successfully.');
        }
        return redirect()->route('quotation_task-management')->with('success', 'Task created successfully.');
    }

    public function editTask($id){
        $task = TaskManagement::where('id', $id)->first();
        // $projects = Project::where('type', 1)->get();
        $projects = Project::whereIn('type', [0,1])->get();

        if(Auth::user()->role == 1){
            return view('admin.taskmanagement.edit_task', compact('projects', 'task'));
        }else if(Auth::user()->role == 8) {
            return view('purchase.taskmanagement.edit_task', compact('task', 'projects'));
        }
        return view('quotation.taskmanagement.edit_task', compact('projects', 'task'));
    }

    public function updateTask(Request $request, $id){
        $request->validate([
            'task' => 'required',
            'project_id' => 'required',
            'task_type' => 'required',
            'status' => 'required',
        ]);

        $task = TaskManagement::find($id);
        $task->task = $request->task;
        $task->project_id = $request->project_id;
        $task->task_type = $request->task_type;
        $task->task_status = $request->status;
        // $task->task_date = date("Y/m/d", strtotime($request->taskDate));
        $task->task_date = Carbon::createFromFormat('Y-m-d\TH:i', $request->taskDate)->format('Y/m/d h:i:s');
        $task->save();

        $user = User::where('id',Auth::user()->id)->first();
        if(Auth::user()->role == 1){
            $notificationData = [
                'type' => 'message',
                'title' => 'Task Management.',
                'text' => 'Task Updated',
                'url' => route('task-management'),
            ];
      
            Notification::send($user, new OffersNotification($notificationData));
        }else{
            $notificationData = [
                'type' => 'message',
                'title' => 'Task Management.',
                'text' => 'Task Updated',
                'url' => route('quotation_task-management'),
            ];
      
            Notification::send($user, new OffersNotification($notificationData));
        }
        

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Task Management - ';
        $log->log       = 'Task Updated';
        $log->save();

        if(Auth::user()->role == 1){
            return redirect()->route('task-management')->with('success', 'Task updated successfully.');
        }else if(Auth::user()->role == 8) {
            return redirect()->route('purchase_task-management')->with('success', 'Task updated successfully.');
        }
        return redirect()->route('quotation_task-management')->with('success', 'Task updated successfully.');
    }

    public function deleteTask($id)
    {
        $task = TaskManagement::find($id);
        $task->delete();
        
        $user = User::where('id',Auth::user()->id)->first();
        if(Auth::user()->role == 1){
            $notificationData = [
                'type' => 'message',
                'title' => 'Task Management - ',
                'text' => 'Task Deleted',
                'url' => route('task-management'),
            ];
      
            Notification::send($user, new OffersNotification($notificationData));
        }else{
            $notificationData = [
                'type' => 'message',
                'title' => 'Task Management - ',
                'text' => 'Task Deleted',
                'url' => route('quotation_task-management'),
            ];
      
            Notification::send($user, new OffersNotification($notificationData));
        }
        

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Task Management';
        $log->log       = 'Task Deleted';
        $log->save();

        return response()->json("success", 200);
    }
    
}
