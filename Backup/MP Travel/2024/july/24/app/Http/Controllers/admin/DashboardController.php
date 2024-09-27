<?php

namespace App\Http\Controllers\admin;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){
        $data['userCount'] = User::where('role_id', 2)->count();
        $data['roleCount'] = Role::count();
        $data['departmentCount'] = Department::count();
        $data['designationCount'] = Designation::count();
        return view('admin.dashboard', $data);
    }

    public function break(){
        $today = Carbon::today()->toDateString();
        $attendance = Attendance::where('user_id',Auth()->user()->id)->whereDate('attendance_date', $today)->first();
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

    public function completeBreak()
    {
        $break_log = BreakLog::where('user_id',Auth()->user()->id)->orderBy('id','DESC')->first();
        if (!empty($break_log)) {
            $break_log->break_over = Carbon::now();
            $break_log->save();
        }
        return redirect()->back();
    }
}
