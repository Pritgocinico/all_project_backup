<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class AttendanceController extends Controller
{
    private $attendance;
    public function __construct()
    {
        $page = "Attendance";
        $this->attendance = resolve(Attendance::class);
        view()->share('page', $page);   
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->attendance->with('employee')->latest();

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('attendance_date', [$startDate, $endDate]);
        }

        $allAttendanceLists = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'view' => view('admin.attendance.table', compact('allAttendanceLists'))->render()
            ]);
        }

        return view('admin.attendance.index', compact('allAttendanceLists'));
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

}
