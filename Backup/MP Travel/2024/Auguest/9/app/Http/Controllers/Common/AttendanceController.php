<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\BreakLog;
use App\Models\User;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class AttendanceController extends Controller
{
    private $attendance;
    private $users;
    public function __construct()
    {
        $page = "Attendance";
        $this->attendance = resolve(Attendance::class);
        $this->users = resolve(User::class)->with('stateDetail', 'countryDetail', 'cityDetail', 'roleDetail', 'designationDetail', 'departmentDetail','shiftDetail');
        view()->share('page', $page);   
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.attendance.index');
    }

    public function dailyAttendance()
    {
        $user_id = Auth()->user()->id;

        if($user_id == 1){
            $employees = User::where('id', '!=', 1)->get();
        }else{
            $employees = User::where('id', $user_id)->get();
        }

        // Get today's attendance records
        $today = Carbon::today()->toDateString();
        $attendances = $this->attendance->whereDate('created_at', $today)->get();

        // Create a collection to hold the attendance status for each employee
        $dailyAttendanceLists = $employees->map(function ($employee) use ($attendances,$today) {
            $attendance = $attendances->firstWhere('user_id', $employee->id);
            $totalHour = 0;
            
            if(isset($attendance->logout_time)){
                $logoutTime = Carbon::parse($attendance->logout_time)->format('H:i:s');
                $totalHour = UtilityHelper::getHourBetweenTwoTimes($attendance->login_time,$logoutTime);
            }
            $breakDetail = BreakLog::where('date',$today)->where('user_id',$employee->id)->get();
            $diffSecond = 0;
            if(isset($breakDetail)){
                foreach ($breakDetail as $key => $break) {
                    $diffSecond += UtilityHelper::getDiffInSecond($break->break_start,$break->break_over);
                }
            }
            return (object) [
                'employee' => $employee,
                'status' => $attendance ? 1 : 0,
                'login_time' => $attendance ? $attendance->login_time : null,
                'logout_time' => $attendance ? $attendance->logout_time : null,
                'total_work_hour' =>$totalHour,
                'break_time' =>UtilityHelper::formatSecondsToTime($diffSecond),
            ];
        });
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $dailyAttendanceLists->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $dailyAttendanceListsPaginated = new LengthAwarePaginator($currentItems, $dailyAttendanceLists->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        return view('admin.attendance.daily_attendance', compact('dailyAttendanceListsPaginated'));
    }

    public function ajaxList(Request $request){
        $currentMonth = UtilityHelper::getCurrentMonth();
        $currentYear = UtilityHelper::getCurrentYear();
        if ($currentMonth == 1) {
            $currentYear = $currentYear - 1;
        }

        // Set default start and end dates
        $startDate = $request->input('start_date') ?: Carbon::create($currentYear, $currentMonth, 1)->toDateString();
        $endDate = $request->input('end_date') ?: Carbon::now()->toDateString();

        // Get the total number of days between the start and end dates
        $totalMonthDay = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        if(Auth()->user()->role_id == 1){
            $userList = $this->users->where('role_id', "!=", 1)->latest()->paginate(10);
        } else {
            $userList = $this->users->where('id', Auth()->user()->id)->latest()->paginate(10);
        }

        // Get holidays within the specified date range
        $holidayDates = Holiday::whereBetween('holiday_date', [$startDate, $endDate])
            ->pluck('holiday_date')
            ->toArray();

        $totalWorkingDays = $totalMonthDay - count($holidayDates);

        $attendanceData = [];
        foreach ($userList as $user) {
            $attendanceList = Attendance::where('user_id', $user->id)->whereBetween('attendance_date', [$startDate, $endDate])->get();

            $presentDays = 0;
            $totalHour = 0;
            foreach ($attendanceList as $key => $attendance) {
                if($attendance->status == 1){
                    $presentDays += 1;
                }
                if($attendance->status == 2){
                    $presentDays += 0.5;
                }
                $logoutTime = Carbon::parse($attendance->logout_time)->format('H:i:s');
                $totalHour += UtilityHelper::getHourBetweenTwoTimes($attendance->login_time,$logoutTime);
            }

            $breakDetail = BreakLog::where('user_id', $user->id)->whereBetween('date', [$startDate, $endDate])->get();
            $diffSecond = 0;
            foreach ($breakDetail as $key => $break) {
                $diffSecond += UtilityHelper::getDiffInSecond($break->break_start,$break->break_over);
            }
            $absentDays = $totalWorkingDays - $presentDays;


            $attendanceData[$user->id] = [
                'present' => $presentDays,
                'absent' => $absentDays,
                'totalHour' => $totalHour,
                'totalBreak' => UtilityHelper::formatSecondsToTime($diffSecond),
            ];
        }
        return view('admin.attendance.table', compact('userList','attendanceData'));
    }

}
