<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
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
        $dailyAttendanceLists = $employees->map(function ($employee) use ($attendances) {
            // Check if the employee has logged in today
            $attendance = $attendances->firstWhere('user_id', $employee->id);

            return (object) [
                'employee' => $employee,
                'status' => $attendance ? 1 : 0, // 1 for present, 0 for absent
                'login_time' => $attendance ? $attendance->login_time : null,
            ];
        });

        // Paginate the results (you can adjust the pagination logic as needed)
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
            $userList = $this->users->where('role_id', "!=", 1)->paginate(10);
        } else {
            $userList = $this->users->where('id', Auth()->user()->id)->paginate(10);
        }

        // Get holidays within the specified date range
        $holidayDates = Holiday::whereBetween('holiday_date', [$startDate, $endDate])
            ->pluck('holiday_date')
            ->toArray();

        $totalWorkingDays = $totalMonthDay - count($holidayDates);

        $attendanceData = [];
        foreach ($userList as $user) {
            $presentDays = Attendance::where('user_id', $user->id)
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->where('status', '1')
                ->count();

            $absentDays = $totalWorkingDays - $presentDays;

            $attendanceData[$user->id] = [
                'present' => $presentDays,
                'absent' => $absentDays,
            ];
        }
        return view('admin.attendance.table', compact('userList','attendanceData'));
    }

}
