<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UtilityHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\AttendanceRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Log as ModelsLog;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    protected $employeeRepository,$attendanceRepository="";
    public function __construct(EmployeeRepositoryInterface $employeeRepository, AttendanceRepositoryInterface $attendanceRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->attendanceRepository = $attendanceRepository;
    }
    public function attendanceList(){
        $page = "Attendance List";
        $manager = request('is_manager');
        return view('admin.hr_manager.attendance.index',compact('page','manager'));
    }
    
    public function attendanceAjaxList(Request $request){
        $from = $request->from;
        $to = $request->to;
        $manager = $request->manager;
        $employeeAttendanceList = $this->employeeRepository->getUserListWithAbsentPresentDetail($from,$to,$manager);
        return view('admin.hr_manager.attendance.ajax_list',compact('from','to','employeeAttendanceList'));
    }

    public function checkAttendance(){
        $holidays = Holiday::where('holiday_date',date('Y-m-d'))->get();
        if(blank($holidays)){
            $staffs = User::where('status',1)->where('role_id','!=',1)->whereNull('deleted_at')->get();
            foreach($staffs as $staff){
                $attendance = Attendance::where('user_id',$staff->id)->whereDate('attendance_date', date('Y-m-d'))->get();
                if(blank($attendance)){
                    $data = [
                        'user_id' => $staff->id,
                        'attendance_date' => date('Y-m-d'),
                    ];
                    Attendance::create($data);
                }
            }
        }
        Log::info("Attendance Cron Job Run at 11:25");
       return 1;
    }

    public function attendanceByDate(Request $req){
        $user = $req->id;
        $date = $req->date;
        $attendance = Attendance::with('breakLogDetail')->where('user_id',$user)->whereDate('attendance_date', $date)->first();
        return $attendance;
    }

    public function logDetail(Request $request){
        $user = $request->id;
        $date = $request->date;
        $log = ModelsLog::where('module','login')->where('user_id',$user)->whereDate('created_at', $date)->get();
        return $log;
    }

    public function dailyAttendance(Request $request){
        $type = request('type');
        $status = request('status');
        $page = "Daily Attendance";
        return view('admin.hr_manager.daily_attendance.index',compact('page','type','status'));
    }

    public function dailyAttendanceAjax(Request $request){
        $search = $request->search;
        $type = $request->type;
        $status = $request->status;
        $attendanceList = $this->attendanceRepository->dailyAttendanceDetail($search,$type,$status);
        return view('admin.hr_manager.daily_attendance.ajax_list',compact('attendanceList','type','status'));
    }
}
