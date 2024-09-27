<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\Project;
use App\Models\User;
use App\Models\Log;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Feedback;
use App\Models\TaskManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Redirect;
use DateTime;
use App\Http\Helpers\SmsHelper;

class AdminController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function dashboard(Request $request)
    {
        $role = Auth::user()->role;
        if ($role == 1) {
            $page = 'Admin Dashboard';
            $icon = 'dashboard.png';
            $projects = Project::whereIn('status', [1, 2])->get();
            $runningProjects = Project::where('status', 1)->get();
            $completedProjects = Project::where('status', 2)->get();
            $leads = Project::where('type', 0)->get();
            $completedTasks = TaskManagement::where('deleted_at', null)->whereHas('project')->where('task_status', 'completed')->get();
            $feedback = Feedback::where('deleted_at', null)->get();
            $pendingTasks = TaskManagement::where('task_status', 'pending')->whereHas('project')->where('deleted_at', null)->get();
            $sales = Project::where('deleted_at', null)->sum('margin_cost');
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $cancelLead = Project::where('lead_cancel_status', '1')
            ->whereNull('deleted_at')
            ->count();

            $monthWiseSales = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthWiseSales[$month] = 0;
            }

            if (!blank($request->dateRange)) {
                $dateRange = $request->dateRange;
                $dateParts = explode(' - ', $dateRange);

                // Convert start date to Y-m-d format
                $startDate = DateTime::createFromFormat('d/m/Y', $dateParts[0]);
                if ($startDate !== false) {
                    $formattedStartDate = $startDate->format('Y-m-d');
                }

                // Convert end date to Y-m-d format
                $endDate = DateTime::createFromFormat('d/m/Y', $dateParts[1]);
                if ($endDate !== false) {
                    $formattedEndDate = $endDate->format('Y-m-d');
                }
                $salesData = Project::selectRaw('SUM(margin_cost) as total_margin_cost, MONTH(created_at) as month')
                    ->whereNull('deleted_at')
                    ->whereBetween('created_at', [$formattedStartDate, $formattedEndDate])
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                foreach ($salesData as $sale) {
                    $monthWiseSales[$sale->month] = $sale->total_margin_cost;
                }
                return response()->json($monthWiseSales, 200);
            } else {
                $salesData = Project::selectRaw('SUM(margin_cost) as total_margin_cost, MONTH(created_at) as month')
                    ->whereNull('deleted_at')
                    ->whereYear('created_at', $currentYear)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
                foreach ($salesData as $sale) {
                    $monthWiseSales[$sale->month] = $sale->total_margin_cost;
                }
            }
            return view('admin.dashboard', compact('cancelLead','feedback', 'page', 'icon', 'monthWiseSales', 'projects', 'runningProjects', 'leads', 'completedProjects', 'completedTasks', 'pendingTasks', 'sales'));
        } else {
            $page = 'Quotation Dashboard';
            $icon = 'dashboard.png';
            $projects = Project::whereIn('status', [1, 2])->get();
            $runningProjects = Project::where('status', 1)->get();
            $completedProjects = Project::where('status', 2)->get();
            $leads = Project::where('type', 0)->get();
            $completedTasks = TaskManagement::where('deleted_at', null)->whereHas('project')->where('task_status', 'completed')->get();
            $feedback = Feedback::where('deleted_at', null)->get();
            $pendingTasks = TaskManagement::where('task_status', 'pending')->whereHas('project')->where('deleted_at', null)->get();
            $sales = Project::where('deleted_at', null)->sum('margin_cost');
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $cancelLead = Project::where('lead_cancel_status', '1')
            ->whereNull('deleted_at')
            ->count();

            $monthWiseSales = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthWiseSales[$month] = 0;
            }
            if (!blank($request->dateRange)) {
                $dateRange = $request->dateRange;
                $dateParts = explode(' - ', $dateRange);

                // Convert start date to Y-m-d format
                $startDate = DateTime::createFromFormat('d/m/Y', $dateParts[0]);
                if ($startDate !== false) {
                    $formattedStartDate = $startDate->format('Y-m-d');
                }

                // Convert end date to Y-m-d format
                $endDate = DateTime::createFromFormat('d/m/Y', $dateParts[1]);
                if ($endDate !== false) {
                    $formattedEndDate = $endDate->format('Y-m-d');
                }
                $salesData = Project::selectRaw('SUM(margin_cost) as total_margin_cost, MONTH(created_at) as month')
                    ->whereNull('deleted_at')
                    ->whereBetween('created_at', [$formattedStartDate, $formattedEndDate])
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                foreach ($salesData as $sale) {
                    $monthWiseSales[$sale->month] = $sale->total_margin_cost;
                }
                return response()->json($monthWiseSales, 200);
            } else {
                $salesData = Project::selectRaw('SUM(margin_cost) as total_margin_cost, MONTH(created_at) as month')
                    ->whereNull('deleted_at')
                    ->whereYear('created_at', $currentYear)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
                foreach ($salesData as $sale) {
                    $monthWiseSales[$sale->month] = $sale->total_margin_cost;
                }
            }
            return view('quotation.dashboard', compact('feedback','cancelLead','page', 'icon', 'monthWiseSales', 'projects', 'runningProjects', 'leads', 'completedProjects', 'completedTasks', 'pendingTasks', 'sales'));
        }
    }
    public function edit_profile()
    {
        $userId = Auth::check() ? Auth::id() : true;
        $user = User::where('id', $userId)->first();
        $page = 'Profile';
        $icon = 'profile.png';
        if (Auth::user()->role == 1) {
            return view('admin/profile/edit_profile', compact('user', 'page', 'icon'));
        } else {
            return view('quotation/profile/edit_profile', compact('user', 'page', 'icon'));
        }

    }
    public function view_profile()
    {
        $userId = Auth::check() ? Auth::id() : true;
        $user = User::where('id', $userId)->first();
        $page = 'Profile';
        $icon = 'profile.png';
        if (Auth::user()->role == 1) {
            return view('admin/profile/view_profile', compact('user', 'page', 'icon'));
        } else {
            return view('quotation/profile/view_profile', compact('user', 'page', 'icon'));
        }
    }
    public function save_profile(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $req->hidden_id,
            // 'phone'         => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);

        if ($req->has('image') && $req->file('image') != null) {
            $image = $req->file('image');
            $destinationPath = 'public/settings/';
            $rand = rand(1, 100);
            $docImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $docImage);
            $img = $docImage;
        } else {
            $img = $req->uploded_image;
        }

        $user = User::where('id', $req->hidden_id)->first();
        $user->name = $req->name;
        $user->email = $req->email;
        $user->phone = $req->phone;
        $user->address = $req->address;
        $user->image = $img;
        $insert = $user->save();
        return Redirect::back();
        // return redirect()->route('edit.profile');
    }
    public function change_password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);
        Auth::logout();
        return redirect('login')->with('message', 'Your password has been changed!');
    }
    public function dashboardCount(Request $request)
    {
        // Extract start and end dates from the dateRange input
        list($s_date, $e_date) = explode('-', $request->dateRange);

        // Convert to DateTime objects for better handling (optional but recommended)
        $s_date = trim($s_date); // Remove any extra whitespace
        $e_date = trim($e_date);
        $startDate = Carbon::createFromFormat('d/m/Y', $s_date)->startOfDay()->toDateString();
        $endDate = Carbon::createFromFormat('d/m/Y', $e_date)->endOfDay()->toDateString();

        // Retrieve filtered projects based on status and created_at date range
        $data['projects'] = Project::whereIn('status', [1, 2])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        // Count running projects within the date range
        $data['runningProjects'] = Project::where('status', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Count completed projects within the date range
        $data['completedProjects'] = Project::where('status', 2)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Count leads within the date range
        $data['leads'] = Project::where('type', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Count completed tasks within the date range
        $data['completedTasks'] = TaskManagement::whereNull('deleted_at')
            ->whereHas('project')
            ->where('task_status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Count feedback within the date range
        $data['feedback'] = Feedback::whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Count pending tasks within the date range
        $data['pendingTasks'] = TaskManagement::where('task_status', 'pending')
            ->whereHas('project')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $data['cancelLead'] = Project::where('lead_cancel_status', '1')
            ->whereNull('deleted_at')
            ->whereBetween('lead_cancel_date_time', [$startDate, $endDate])
            ->count();

        $data['sales'] = Project::whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('margin_cost');

        return $data;

    }

    public function userVerify($id = null){
        $userData = User::where('id', $id)->first();
        $role = Role::where('id', $userData->role)->first();
        $userData->app_user_active = 1;
        $update = $userData->save();
        if($update){
            // Log
            try {
                $mobileNumber = $userData->phone;
                $password = $userData->original_password;
                $message = "Your user id of " . $role->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $userData->phone . " , Password: " . $password;
                $templateid = '1407171593745579639';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
            } catch (Exception $e) {
                echo 'Message: ' . $e->getMessage();
            }
            return response()->json("success", 200);
        }
        return response()->json("error", 500);

    }
}
