<?php

namespace App\Http\Controllers\employee;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\AttendanceRepositoryInterface;
use App\Interfaces\EmployeeDashboardRepositoryInterface;
use App\Interfaces\LeaveRepositoryInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $attendanceRepository, $employeeDashboardRepository, $leaveRepository = "";
    public function __construct(AttendanceRepositoryInterface $attendanceRepository, EmployeeDashboardRepositoryInterface $employeeDashboardRepository, LeaveRepositoryInterface $leaveRepository)
    {
        $this->attendanceRepository = $attendanceRepository;
        $this->employeeDashboardRepository = $employeeDashboardRepository;
        $this->leaveRepository = $leaveRepository;
    }
    public function dashboard()
    {
        return view('admin.index');
    }

    public function  employeeDashboard()
    {
        $currentMonth = date("F");
        $user_id = Auth()->user()->id;
        $attendance['present'] = $this->attendanceRepository->getPresentCount($currentMonth, $user_id, '1');
        $attendance['absent'] = $this->attendanceRepository->getPresentCount($currentMonth, $user_id, '0');
        $attendance['leave_balance'] = '1';
        $attendance['role_name'] = 'Employee';
        $attendance['order_date'] = Carbon::now()->format('Y-m-d') . "/" . Carbon::now()->format('Y-m-d');

        $getYear = $this->employeeDashboardRepository->getStoreYearOrder();
        $page = "Employee Dashboard";
        return view('employee.index', compact('attendance', 'getYear', 'page'));
    }
    public function  category()
    {
        return view('employee.category.index');
    }
    public function  certificate()
    {
        return view('employee.certificate.index');
    }
    public function  attendance()
    {
        $page = "User Attendance List";
        $dateList = UtilityHelper::getAllCurrentYearDate('1');
        return view('employee.attendance.index', compact('page', 'dateList'));
    }
    public function attendanceDateAjax(Request $request)
    {
        $page = $request->page;
        $dateList = UtilityHelper::getAllCurrentYearDate($page);
        return view('employee.attendance.ajax_list', compact('dateList'));
    }
    public function break()
    {
        $attendance = $this->attendanceRepository->getTodayAttendanceUser();
        if (!blank($attendance)) {
            $data = [
                'attendance_id' => $attendance->id,
                'user_id' => Auth()->user()->id,
                'date' => date('Y-m-d'),
                'break_start' => Carbon::now(),
            ];
            $this->attendanceRepository->storeBreakLog($data);
            $where['id'] = $attendance->id;
            $update['break'] = Carbon::now();
            $this->attendanceRepository->getTodayAttendanceUser($update, $where);
        }
        return redirect()->back();
    }

    public function sumTime()
    {
        $diff_in_days = [];
        $breaks = $this->attendanceRepository->getBreakLogDetail();
        if (!empty($breaks)) {
            foreach ($breaks as $br) {
                $to = new \Carbon\Carbon($br->break_start);
                $from = new \Carbon\Carbon($br->break_complete);
                $diff_in_days[] = $to->diff($from)->format('%H:%I:%S');
            }
        } else {
            $diff_in_days = [];
        }
        $time = (array)$diff_in_days;
        $time = array_filter($time, function ($item) {
            return !in_array($item, ['00:00:00', '0:00:00']);
        });
        $begin = \Carbon\Carbon::createFromFormat('H:i:s', '00:00:00');
        $end = clone $begin;
        foreach ($time as $element) {
            $dateTime = \Carbon\Carbon::createFromFormat('H:i:s', $element);
            $end->addHours($dateTime->format('H'))
                ->addMinutes($dateTime->format('i'))
                ->addSeconds($dateTime->format('s'));
        }
        return sprintf(
            '%sh:%sm:%ss',
            $end->diffInHours($begin),
            $end->format('i'),
            $end->format('s')
        );
    }
    public function completeBreak()
    {
        $break_log = $this->attendanceRepository->userBreakLog();
        if (!empty($break_log)) {
            $update['break_over']  = Carbon::now();
            $where['user_id']  = Auth()->user()->id;
            $break_log = $this->attendanceRepository->updateBreakLogTime($update, $where);
        }
        return redirect()->back();
    }

    public function orderAjax(Request $request)
    {
        $from = $request->from;
        $data['pendingCount'] = $this->employeeDashboardRepository->countOrderStatus($from, '1');
        $data['confirmCount'] = $this->employeeDashboardRepository->countOrderStatus($from, '2');
        $data['cancelCount'] = $this->employeeDashboardRepository->countOrderStatus($from, '4');
        $data['returnCount'] = $this->employeeDashboardRepository->countOrderStatus($from, '5');
        $data['deliverCount'] = $this->employeeDashboardRepository->countOrderStatus($from, '3');
        return response()->json(['data' => $data, 'message' => '', 'status' => 1], 200);
    }
    public function leaveAjax(Request $request)
    {
        $from = $request->from;
        $monthNumber = date('m');
        $totalMonth = UtilityHelper::getTotalMonthPast($monthNumber);
        $totalPastLeave = $totalMonth * 2;
        $totalUseLeaveYear = $this->leaveRepository->getTotalYearLeave(Auth()->user()->id);
        $totalYearLeave = 0;
        foreach ($totalUseLeaveYear as $leaveYear) {
            if (Carbon::parse($leaveYear->leave_from)->format('F') !== $monthNumber) {
                if($leaveYear->leave_feature == 0){
                    $totalYearLeave += 0.5;
                } else {
                    $totalYearLeave += UtilityHelper::getDiffBetweenDates($leaveYear->leave_from, $leaveYear->leave_to);
                }
            }
        }
        $diffBetween = 0;
        if ($totalYearLeave !== $totalPastLeave) {
            $diffBetween = $totalPastLeave - $totalYearLeave;
        }
        $data['leave_balance'] = $diffBetween;

        $data['presentCount'] = $this->employeeDashboardRepository->totalAbsent($from, '1');
        $data['absentCount'] = $this->employeeDashboardRepository->totalAbsent($from, '0');
        return response()->json(['data' => $data, 'message' => '', 'status' => 1], 200);
    }
    public function winnerAjax(Request $request)
    {
        $users = DB::table('users')->where('role_id', '!=', 1)->get();
        $data1 = [];
        $status = "6";
        if(Auth()->user() !== null && Auth()->user()->role_id == 2){
            $status = "1";
        }
        $date1 = explode('/', $request->from);
        foreach ($users as $user) {
            $data = [];
            $order = DB::table('orders')->where('order_status', $status)->where('created_by',$user->id)
                ->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1])
                ->count();
            $data[$user->name] = $order;
            array_push($data1, $data);
        }
        $winner = 0;
        sort($data1);
        $coms = [];
        foreach ($data1 as $orders) {
            $com = [];
            foreach ($orders as $key => $value) {
                $cnt = $value;
                $winner = $key;
                $com['winner'] = $winner;
                $com['order'] = $value;
                array_push($coms, $com);
            }
        }
        usort($coms, function ($a, $b) {
            return $a['order'] < $b['order'];
        });
        return response()->json(['data' => $coms, 'message' => '', 'status' => 1], 200);
    }

    public function chartDataAjax(Request $request)
    {
        $date = $request->date;
        $statuses = ['pending', 'confirm', 'delivered', 'return','cancel'];
        $pendingCount = $this->employeeDashboardRepository->countOrderStatus($date, '1');
        $confirmCount = $this->employeeDashboardRepository->countOrderStatus($date, '2');
        $deliveredCount = $this->employeeDashboardRepository->countOrderStatus($date, '6');
        $returnCount = $this->employeeDashboardRepository->countOrderStatus($date, '5');
        $cancelCount = $this->employeeDashboardRepository->countOrderStatus($date, '4');

        $data = [
            'labels'=>$statuses,
            'data'=>[$pendingCount,$confirmCount,$deliveredCount,$returnCount,$cancelCount],
        ];

        return response()->json($data);
    }
}
