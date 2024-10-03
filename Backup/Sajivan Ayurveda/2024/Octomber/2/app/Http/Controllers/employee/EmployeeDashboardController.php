<?php

namespace App\Http\Controllers\employee;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\Customer;
use App\Models\FollowUpEvent;
use App\Models\FollowUpMember;
use App\Models\Lead;
use App\Models\LeadMember;
use App\Models\Leave;
use App\Models\SalarySlip;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeDashboardController extends Controller
{
    public function __construct()
    {
        view()->share('page', 'Dashboard');
    }

    public function index()
    {
        $id = Auth()->user()->id;
        $data['assignLead'] = LeadMember::where('user_id', $id)->count();
        $data['createdLead'] = Lead::where('created_by', $id)->count();
        $assignFollowUp = FollowUpMember::where('user_id', $id)->count();
        $createFollowUp = FollowUpEvent::where('created_by', $id)->count();
        $data['followUpCount'] = $assignFollowUp + $createFollowUp;
        $data['salarySlipMonth'] = SalarySlip::where('emp_id', $id)->latest()->first();
        $data['customerCount'] = Customer::count();
        $data['certificateCount'] = Certificate::where('emp_id', $id)->count();
        $data['monthList'] = UtilityHelper::getPastMonthsWithCurrent();
        $data['leads'] = lead::where('created_by', Auth()->user()->id)->orWhereHas('leadMemberDetail', function ($query) {
            $query->where('user_id', Auth()->user()->id);
        })->latest()->get();

        $statuses = ['1', '2', '3', '4','5','6'];

        // $leadStatusData = Lead::where('created_by', Auth()->user()->id)->orWhereHas('leadMemberDetail', function ($query) {
        //     $query->where('user_id', Auth()->user()->id);
        // })->selectRaw('lead_status, COUNT(*) as count')
        // ->groupBy('lead_status')
        // ->pluck('count', 'lead_status')
        // ->toArray();
        // $data['labels'] = ['Pending','Assigned','Hold','Complete','Extends','Cancel'];
        // $statusArray = [];
        // foreach ($statuses as $status) {
        //     $statusArray[] = isset($leadStatusData[$status]) ? $leadStatusData[$status] : 0;
        // }
        // $data['statusData'] = $statusArray;


        $data['followUpList'] = FollowUpEvent::where('created_by', Auth()->user()->id)->orWhereHas('followUpMemberDetail', function ($query) {
            $query->where('user_id', Auth()->user()->id);
        })->get();

        $followStatusData = FollowUpEvent::where('created_by', Auth()->user()->id)->orWhereHas('followUpMemberDetail', function ($query) {
            $query->where('user_id', Auth()->user()->id);
        })->selectRaw('event_status, COUNT(*) as count')
        ->groupBy('event_status')
        ->pluck('count', 'event_status')
        ->toArray();
        $data['follow_labels'] = ['Pending','Assigned','Hold','Complete','Extends','Cancel'];
        $followStatusArray = [];
        foreach ($statuses as $status) {
            $followStatusArray[] = isset($followStatusData[$status]) ? $followStatusData[$status] : 0;
        }
        $data['followStatusArray'] = $followStatusArray;
        return view('employee.dashboard', $data);
    }

    public function leaveDataAjax(Request $request)
    {
        $id = Auth()->user()->id;
        $month_name = $request->month_name;
        $monthNumber = UtilityHelper::convertMonthNumberToMonthName($month_name);
        $currentYear = UtilityHelper::getCurrentYear();
        if ($monthNumber == 1) {
            $currentYear = $currentYear - 1;
        }
        $data['working_days'] = $workingDays = UtilityHelper::getWorkingDaysInMonth($month_name);
        $data['presentDays'] = Attendance::whereMonth('attendance_date', $monthNumber)->whereYear('attendance_date', $currentYear)->where('user_id', $id)->whereIn('status', [1, 2])->count();
        $data['absentDays'] = $workingDays - $data['presentDays'];
        $startOfMonth = Carbon::createFromDate($currentYear, $monthNumber, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($currentYear, $monthNumber, 1)->endOfMonth();
        $data['leaveCount'] = $leaves = Leave::where('user_id', $id)
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('leave_from', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('leave_to', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($query) use ($startOfMonth, $endOfMonth) {
                        $query->where('leave_from', '<', $startOfMonth)
                            ->where('leave_to', '>', $endOfMonth);
                    });
            })
            ->sum('total_leave_day');
        return response()->json($data);
    }
}
