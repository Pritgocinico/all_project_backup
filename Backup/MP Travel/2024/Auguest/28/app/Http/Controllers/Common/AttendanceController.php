<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\BreakLog;
use App\Models\User;
use App\Models\Holiday;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use PDF;

class AttendanceController extends Controller
{
    private $attendance;
    private $users;
    public function __construct()
    {
        $page = "Attendance";
        $this->attendance = resolve(Attendance::class);
        $this->users = resolve(User::class)->with('stateDetail', 'countryDetail', 'cityDetail', 'roleDetail', 'designationDetail', 'departmentDetail', 'shiftDetail');
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
        $user_id = Auth()->user()->role_id;
        $status = request('status');
        if ($user_id == 1) {
            $employees = User::where('role_id', '!=', 1)->latest()->get();
        } else {
            $employees = User::where('id', $user_id)->get();
        }
        $attendances = $this->attendance->when($status || $status == 0,function($query)use($status){
            $query->where('status',(string) $status);
        })->whereDate('attendance_date', Carbon::now())->get();
        $dailyAttendanceListsPaginated = $employees->map(function ($employee) use ($attendances) {
            $attendance = $attendances->firstWhere('user_id', $employee->id);
            $totalHour = 0;

            if (isset($attendance->logout_time)) {
                $logoutTime = Carbon::parse($attendance->logout_time)->format('H:i:s');
                $totalHour = UtilityHelper::getHourBetweenTwoTimesAttendance($attendance->login_time, $logoutTime);
            }
            $breakDetail = BreakLog::whereDate('date', Carbon::now())->where('user_id', $employee->id)->get();
            $diffSecond = 0;
            if (isset($breakDetail)) {
                foreach ($breakDetail as $key => $break) {
                    $diffSecond += UtilityHelper::getDiffInSecond($break->break_start, $break->break_over);
                }
            }
            $status = 0;
            if (isset($attendance)) {

                if ($attendance->status == 1) {
                    $status = 1;
                }
                if ($attendance->status == 1) {
                    $status = 2;
                }
            }
            return (object) [
                'employee' => $employee,
                'status' => $status,
                'login_time' => $attendance ? $attendance->login_time : null,
                'logout_time' => $attendance ? $attendance->logout_time : null,
                'total_work_hour' => $totalHour,
                'break_time' => UtilityHelper::formatSecondsToTime($diffSecond),
            ];
        });

        return view('admin.attendance.daily_attendance', compact('dailyAttendanceListsPaginated'));
    }

    public function ajaxList(Request $request)
    {
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
        if (Auth()->user()->role_id == 1) {
            $userList = $this->users->where('role_id', "!=", 1)->latest()->get();
        } else {
            $userList = $this->users->where('id', Auth()->user()->id)->latest()->get();
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
            $diff_in_days = [];
            foreach ($attendanceList as $key => $attendance) {
                if ($attendance->status == 1) {
                    $presentDays += 1;
                }
                if ($attendance->status == 2) {
                    $presentDays += 0.5;
                }
                $logoutTime = Carbon::parse($attendance->logout_time)->format('H:i:s');
                $logoutTime = Carbon::parse($attendance->logout_time)->format('H:i:s');
                $to = new \Carbon\Carbon($attendance->break_start);
                $from = new \Carbon\Carbon($logoutTime);
                $diff_in_days[] = $to->diff($from)->format('%H:%I:%S');
            }
            $totalSeconds = 0;
            foreach ($diff_in_days as $element) {
                $parts = explode(':', $element);
                $totalSeconds += $parts[0] * 3600 + $parts[1] * 60 + $parts[2];
            }
            $interval = CarbonInterval::seconds($totalSeconds)->cascade();
            $totalHour = $interval->format('%H:%I:%S');
            $breakDetail = BreakLog::where('user_id', $user->id)->whereBetween('date', [$startDate, $endDate])->get();
            $diffSecond = 0;
            foreach ($breakDetail as $key => $break) {
                $diffSecond += UtilityHelper::getDiffInSecond($break->break_start, $break->break_over);
            }
            $absentDays = $totalWorkingDays - $presentDays;


            $attendanceData[$user->id] = [
                'present' => $presentDays,
                'absent' => $absentDays,
                'totalHour' => $totalHour,
                'totalBreak' => UtilityHelper::formatSecondsToTime($diffSecond),
            ];
        }
        return view('admin.attendance.table', compact('userList', 'attendanceData'));
    }
    public function attendanceExport(Request $request)
    {
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
        if (Auth()->user()->role_id == 1) {
            $userList = $this->users->where('role_id', "!=", 1)->latest()->get();
        } else {
            $userList = $this->users->where('id', Auth()->user()->id)->latest()->get();
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
            $diff_in_days = [];
            foreach ($attendanceList as $key => $attendance) {
                if ($attendance->status == 1) {
                    $presentDays += 1;
                }
                if ($attendance->status == 2) {
                    $presentDays += 0.5;
                }
                $logoutTime = Carbon::parse($attendance->logout_time)->format('H:i:s');
                $to = new \Carbon\Carbon($attendance->break_start);
                $from = new \Carbon\Carbon($logoutTime);
                $diff_in_days[] = $to->diff($from)->format('%H:%I:%S');
            }
            $totalSeconds = 0;
            foreach ($diff_in_days as $element) {
                $parts = explode(':', $element);
                $totalSeconds += $parts[0] * 3600 + $parts[1] * 60 + $parts[2];
            }
            $interval = CarbonInterval::seconds($totalSeconds)->cascade();
            $totalHour = $interval->format('%H:%I:%S');
            $breakDetail = BreakLog::where('user_id', $user->id)->whereBetween('date', [$startDate, $endDate])->get();
            $diffSecond = 0;
            foreach ($breakDetail as $key => $break) {
                $diffSecond += UtilityHelper::getDiffInSecond($break->break_start, $break->break_over);
            }
            $absentDays = $totalWorkingDays - $presentDays;


            $attendanceData[$user->id] = [
                'present' => $presentDays,
                'absent' => $absentDays,
                'totalHour' => $totalHour,
                'totalBreak' => UtilityHelper::formatSecondsToTime($diffSecond),
            ];
        }
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Attendance.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('User Name', 'Absent(Days)', 'Present(Days)', 'Total Working Hours', 'Total Break Time');
            $callback = function () use ($attendanceData, $columns, $userList) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($userList as $user) {

                    fputcsv($file, array($user->name, $attendanceData[$user->id]['absent'], $attendanceData[$user->id]['present'], $attendanceData[$user->id]['totalHour'], $attendanceData[$user->id]['totalBreak']));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.attendance', ['userList' => $userList,'attendanceData' => $attendanceData,'setting' =>$setting]);
            return $pdf->download('Attendance.pdf');
        }
    }
    public function dailyAttendanceExport(Request $request)
    {
        $user_id = Auth()->user()->id;

        if ($user_id == 1) {
            $employees = User::where('id', '!=', 1)->get();
        } else {
            $employees = User::where('id', $user_id)->get();
        }

        // Get today's attendance records
        $today = Carbon::today()->toDateString();
        $attendances = $this->attendance->whereDate('created_at', $today)->get();

        // Create a collection to hold the attendance status for each employee
        $dailyAttendanceLists = $employees->map(function ($employee) use ($attendances, $today) {
            $attendance = $attendances->firstWhere('user_id', $employee->id);
            $totalHour = 0;

            if (isset($attendance->logout_time)) {
                $logoutTime = Carbon::parse($attendance->logout_time)->format('H:i:s');
                $totalHour = UtilityHelper::getHourBetweenTwoTimesAttendance($attendance->login_time, $logoutTime);
            }
            $breakDetail = BreakLog::where('date', $today)->where('user_id', $employee->id)->get();
            $diffSecond = 0;
            if (isset($breakDetail)) {
                foreach ($breakDetail as $key => $break) {
                    $diffSecond += UtilityHelper::getDiffInSecond($break->break_start, $break->break_over);
                }
            }
            return (object) [
                'employee' => $employee,
                'status' => $attendance ? 1 : 0,
                'login_time' => $attendance ? $attendance->login_time : null,
                'logout_time' => $attendance ? $attendance->logout_time : null,
                'total_work_hour' => $totalHour,
                'break_time' => UtilityHelper::formatSecondsToTime($diffSecond),
            ];
        });

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Daily Attendance.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('User Name', 'Absent(Days)', 'Present(Days)', 'Total Working Hours', 'Total Break Time');
            $callback = function () use ($dailyAttendanceLists, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($dailyAttendanceLists as $today_Attendance) {
                    $loginTime = $today_Attendance->login_time ? UtilityHelper::convertDmyAMPMFormat($today_Attendance->login_time) : '-';
                    $logoutTime = $today_Attendance->logout_time ? UtilityHelper::convertDmyAMPMFormat($today_Attendance->logout_time) : '-';
                    fputcsv($file, array($today_Attendance->employee->name, $loginTime, $logoutTime, $today_Attendance->total_work_hour, $today_Attendance->break_time));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.daily_attendance', ['dailyAttendanceLists' => $dailyAttendanceLists,'setting' =>$setting]);
            return $pdf->download('Daily Attendance.pdf');
        }
    }
}
