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
use App\Models\Lead;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Hash;
use Auth;
use Carbon\CarbonInterval;

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
        if (Auth()->user()->role_id == 1) {
            return view('admin.dashboard', $data);
        }
        return view('employee.dashboard');
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
}
