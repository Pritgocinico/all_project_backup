<?php

namespace App\Http\Controllers\hr;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\Customer;
use App\Models\Holiday;
use App\Models\InfoSheet;
use App\Models\Leave;
use App\Models\SalarySlip;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        View()->share('page', 'Dashboard');
    }
    public function index(Request $request)
    {
        $data['totalEmployee'] = $totalEmployee = User::where('role_id', "!=", 1)->count();
        $data['presentCount'] = $presentCount = Attendance::selectRaw('status, COUNT(*) as count')->whereDate('attendance_date', Carbon::now())
            ->where('status', 1)
            ->groupBy('status')
            ->pluck('count', 'status')
            ->count();
        $data['halfDayCount'] = $halfDayCount = Attendance::selectRaw('status, COUNT(*) as count')->whereDate('attendance_date', Carbon::now())
            ->where('status', 2)
            ->groupBy('status')
            ->pluck('count', 'status')
            ->count();

        $data['absentCount'] = $absentCount = $totalEmployee - ($presentCount - $halfDayCount);
        $data['labels'] = ['Present', 'Absent', 'Half Day'];
        $data['data'] = [$presentCount, $absentCount, $halfDayCount];

        $data['infoSheetCount'] = InfoSheet::count();
        $data['holidayCount'] = Holiday::count();
        $data['leaveCount'] = Leave::count();
        $data['ticketCount'] = Ticket::count();
        $data['certificateCount'] = Certificate::count();
        $data['customerCount'] = Customer::count();
        $data['salarySlipMonth'] = SalarySlip::latest()->first();

        return view('hr.dashboard', $data);
    }
}
