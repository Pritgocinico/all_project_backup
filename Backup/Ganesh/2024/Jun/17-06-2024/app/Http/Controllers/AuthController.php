<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\Log;
use Carbon\Carbon;
use App\Models\RoleUser;
use App\Models\Setting;
use App\Models\Project;
use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Feedbackuploads;
use App\Models\Measurement;
use App\Models\TaskManagement;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\Measurementfile;
use App\Models\Workshop;
use App\Models\Fitting;
use App\Models\Purchase;
use Nnjeim\World\World;
use App\Models\City;
use Nnjeim\World\WorldHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Notification;
use App\Notifications\OffersNotification;

class AuthController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function index()
    {
        if (auth()->user())
        {
            if(Auth::user()->role == 1){
                return redirect()->route('admin.dashboard');
            }else{
                return view('auth.login');
            }
        }else
        {
           return view('auth.login');
        }
    }
    public function adminLogin(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = $request->only($fieldType, 'password');

        if (Auth::attempt(array($fieldType => $input['email'], 'password' => $input['password']))) {

            $role = Auth::user()->role;
            $user=Auth::user();
            $user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp()
            ]);
            if($user->status==1){
                    Auth::login($user, true);
                    $log = new Log();
                    $log->user_id   = Auth::user()->name;
                    $log->module    = 'Login';
                    $log->log       = $user->name.' Logged in Successfully';
                    $log->save();
                if($role==1){
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
                    return redirect()->intended('admin/dashboard')
                            ->withSuccess('Signed in');
                }elseif ($role==4) {
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
                    return redirect()->intended('quotation/dashboard')
                            ->withSuccess('Signed in');
                }else{
                    Session::flash('message','Invalid email or password');
                    return redirect("/login")->withInput()->withSuccess('Login details are not valid');
                }
            }else{
                Session::flash('message','Your account has been deactivated. Please contact with administrator.');
                return redirect("/login")->withInput()->withSuccess('Your account has been deactivated. Please contact with administrator.');
            }
        }
        Session::flash('message','Invalid email or password');
        return redirect("/login")->withInput()->withSuccess('Login details are not valid');
    }
    public function logout(Request $request) {

        Auth::logout();
        return redirect('/login');
    }
    public function showResetPasswordForm($token) {
         return view('auth.reset-password', ['token' => $token]);
      }
    public function submitResetPasswordForm(Request $request)
    {
          $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->back()->with('status', __($status));
        }
        return back()->withErrors(['email' => [__($status)]]);
    }

    public function feedbackForm(Request $request, $project_id = NULL){
        $project = Project::where('id', $project_id)->first();
        
        $customer = Customer::where('id', $project->customer_id)->first();
        $feedbackDetail = Feedback::where('project_id',$project_id)->count();
        if($feedbackDetail > 0){
            return view('auth/thankyou');
        }
        return view('auth/feedback_form', compact('customer', 'project'));
    }

    public function feedbackStore(Request $request){
        $request->validate([
            'rating' => 'required',
        ]);
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
                $extension = date('YmdHis') . "." . $file->getClientOriginalExtension();
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

        $user = User::where('id',Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Feedback Submitted by Customer.',
            'text' => 'Customer: '.$feedback->customer_name,
            'url' => route('viewFeedback',$feedback->id),
        ];
  
        Notification::send($user, new OffersNotification($notificationData));
        return view('auth/thankyou');
    }

    public function projectView(WorldHelper $world, Request $request, $project_id){
        $users = Customer::all();
        $business = User::where('role', '=', 7)->get();
        $projects = Project::with('customer')->findOrFail($project_id);
        $customer = Customer::where('id',$projects->customer_id)->first();
        $quotationfiles = Quotationfile::where('project_id', $project_id)->get();
        $measurements = Measurement::where('project_id', $project_id)->orderBy('id', 'desc')->get();
        $measurementfiles = Measurementfile::where('project_id', $project_id)->get();
        $quotations = Quotation::where('project_id', $project_id)->first();
        $workshops = Workshop::where('project_id', $project_id)->get();
        $fittings = Fitting::where('project_id', $project_id)->get();
        $purchases = Purchase::where('project_id', $project_id)->orderBy('id','DESC')->get();
        $this->world = $world;
        $action = $this->world->cities([
            'filters' => [
                'state_id' => 1650,
            ],
        ]);
        if ($action->success) {
            $cities = $action->data;
        }
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);
        
        if ($state_action->success) {
            $states = $state_action->data;
        }

        $cities = City::where('state_id', $projects->statename)->get();

        return view('auth/project_view', compact('users', 'business', 'projects', 'customer', 'quotationfiles', 'measurements', 'measurementfiles', 'quotations', 'workshops', 'fittings', 'purchases', 'states', 'cities'));
    }

    public function TermsNConditions(Request $request){
        $settings   = Setting::first();

        return view('auth.termsNconditions', compact('settings'));
    }

    public function PrivacyPolicies(Request $request){
        $settings   = Setting::first();

        return view('auth.privacyPolicy', compact('settings'));
    }
    
}
