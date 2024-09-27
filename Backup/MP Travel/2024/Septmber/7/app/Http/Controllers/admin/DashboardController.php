<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakLog;
use App\Models\FollowUpEvent;
use App\Models\Lead;
use App\Models\Log;
use App\Models\Certificate;
use App\Models\Holiday;
use App\Models\InfoSheet;
use App\Models\Leave;
use App\Models\SalarySlip;
use App\Models\Ticket;
use App\Models\Customer;
use Carbon\Carbon;
use Hash;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function __construct()
    {
        view()->share('page','Dashboard');
    }
    public function index()
    {
        $data['userCount'] = User::where('role_id', "!=", 1)->count();
        $data['roleCount'] = Role::count();
        $data['departmentCount'] = Department::count();
        $data['designationCount'] = Designation::count();
        $data['leadCount'] = Lead::count();
        $data['leads'] = Lead::get();
        $data['followUpList'] = FollowUpEvent::get();

        
        $statuses = ['1', '2', '3', '4','5','6'];

        $leadStatusData = Lead::selectRaw('lead_status, COUNT(*) as count')
        ->groupBy('lead_status')
        ->pluck('count', 'lead_status')
        ->toArray();
        $data['lead_labels'] = ['Pending','Assigned','Hold','Complete','Extends','Cancel'];
        $statusArray = [];
        foreach ($statuses as $status) {
            $statusArray[] = isset($leadStatusData[$status]) ? $leadStatusData[$status] : 0;
        }
        $data['statusData'] = $statusArray;

        $followStatusData = FollowUpEvent::selectRaw('event_status, COUNT(*) as count')
        ->groupBy('event_status')
        ->pluck('count', 'event_status')
        ->toArray();
        $data['follow_labels'] = ['Pending','Assigned','Hold','Complete','Extends','Cancel'];
        $followStatusArray = [];
        foreach ($statuses as $status) {
            $followStatusArray[] = isset($followStatusData[$status]) ? $followStatusData[$status] : 0;
        }
        $data['followStatusArray'] = $followStatusArray;
        $data['infoSheetCount'] = InfoSheet::count();
        $data['holidayCount'] = Holiday::count();
        $data['leaveCount'] = Leave::count();
        $data['ticketCount'] = Ticket::count();
        $data['certificateCount'] = Certificate::count();
        $data['customerCount'] = Customer::count();
        $data['salarySlipMonth'] = SalarySlip::latest()->first();
        $data['totalEmployee'] = $totalEmployee = User::where('role_id', "!=", 1)->count();
        $data['presentCount'] = $presentCount = Attendance::selectRaw('status, COUNT(*) as count')->whereDate('attendance_date', Carbon::now())
            ->where('status', "1")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->count();
        $data['halfDayCount'] = $halfDayCount = Attendance::selectRaw('status, COUNT(*) as count')->whereDate('attendance_date', Carbon::now())
            ->where('status', "2")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->count();
        

        $data['absentCount'] = $absentCount = $totalEmployee - ($presentCount - $halfDayCount);
        $data['labels'] = ['Present', 'Absent', 'Half Day'];
        $data['data'] = [$presentCount, $absentCount, $halfDayCount];
        return view('admin.dashboard', $data);
    }

    public function break()
    {
        $today = Carbon::today()->toDateString();
        $attendance = Attendance::where('user_id', Auth()->user()->id)->whereDate('attendance_date', $today)->first();
        if (!blank($attendance)) {
            $data = [
                'attendance_id' => $attendance->id,
                'user_id' => Auth()->user()->id,
                'date' => date('Y-m-d'),
                'break_start' => Carbon::now(),
            ];
            BreakLog::create($data);

            $attendance->update(['break' => Carbon::now()]);
        }
        return redirect()->back();
    }

    public function sumTime()
    {
        $diff_in_days = [];
        $breaks = BreakLog::where('user_id', Auth()->user()->id)
            ->orderBy('id', 'DESC')
            ->whereDate('created_at', Carbon::today())
            ->get();

        if (!$breaks->isEmpty()) {
            foreach ($breaks as $br) {
                if ($br->break_over) {
                    $to = new \Carbon\Carbon($br->break_start);
                    $from = new \Carbon\Carbon($br->break_over);
                    $diff_in_days[] = $from->diff($to)->format('%H:%I:%S');
                }
            }
        }
        $time = array_filter($diff_in_days, function ($item) {
            return $item !== '00:00:00' && $item !== '0:00:00';
        });
        $totalSeconds = 0;
        foreach ($time as $element) {
            $parts = explode(':', $element);
            $totalSeconds += $parts[0] * 3600 + $parts[1] * 60 + $parts[2];
        }
        $interval = CarbonInterval::seconds($totalSeconds)->cascade();
        $breakTime = $interval->format('%H:%I:%S');
        return $breakTime;
    }


    public function completeBreak()
    {
        $break_log = BreakLog::where('user_id', Auth()->user()->id)->orderBy('id', 'DESC')->first();
        if (!empty($break_log)) {
            $break_log->break_over = Carbon::now();
            $break_log->save();
        }
        return redirect()->back();
    }

    public function changePassword()
    {
        $user = Auth()->user();
        return view('employee.user.change_password', compact('user'));
    }
    public function updatePassword(ChangePasswordRequest $request)
    {

        $user = Auth::user();
        if(Auth()->user()->role_id == "1"){
            $user->email = $request->email;
        }
        if(isset($request->new_password)){
            $user->password = Hash::make($request->new_password);
        }
        $update = $user->save();
        if($update){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Password Change',
                'description' => auth()->user()->name . " Password has been updated.",
            ]);
            Auth::logout();
            
            return redirect()->route('login')->with('success', 'Password successfully changed! Please log in with your new password.');
        }
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }
    public function profileView(){
        $user = Auth::user();
        return view('admin.user.profile', compact('user'));
    }

    public function updateProfile(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore(auth()->user()->id)->whereNull('deleted_at')
            ],
            'phone_number' => 'required',
            'new_password' => 'nullable',
            'new_confirm_password' => 'nullable|string|min:5|same:new_password',
        ]);
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        if(isset($request->password)){
            $user->password = Hash::make($request->password);
        }
        $update = $user->save();
        if($update){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Profile Update',
                'description' => auth()->user()->name . " Profile has been updated.",
            ]);
            return redirect()->back()->with('success', 'Profile successfully updated!');
        }
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }
}
