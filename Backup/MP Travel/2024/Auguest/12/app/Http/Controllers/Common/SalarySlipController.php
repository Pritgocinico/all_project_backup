<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeDeduction;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\SalarySlip;
use App\Models\Log;
use App\Models\SalarySlipDetail;
use App\Models\ShiftTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Pusher\Pusher;

class SalarySlipController extends Controller
{
    private $salarySlip;
    public function __construct()
    {
        $page = "Salary Slip";
        $this->salarySlip = resolve(SalarySlip::class)->with('employeeDetail', 'employeeDetail.departmentDetail', 'employeeDetail.designationDetail');
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salarySlipList = $this->salarySlip->paginate(10);
        $pastMonth = UtilityHelper::getPastMonths();
        return view('admin.salaryslip.index', compact('salarySlipList', 'pastMonth'));
    }

    public function salaryslipAjaxList(Request $request){
        $search = $request->search;
        $salarySlipList = $this->salarySlip
        ->when(Auth()->user()->role_id == 2,function($query){
            $query->where('emp_id',Auth()->user()->id);
        })
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('salary_slips.month', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.year', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.total_working_days', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.present_days', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.payable_salary', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.leave', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.created_at', 'like', '%' . $search . '%')
                      ->orWhereHas('employeeDetail', function ($query) use ($search) {
                        $query->where('users.name', 'like', '%' . $search . '%');
                    });
            });
        })->latest()
        ->paginate(10);
        $pastMonth = UtilityHelper::getPastMonths();
        return view('admin.salaryslip.ajax_list',compact('salarySlipList', 'pastMonth'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        
        $salarySlipList = $this->salarySlip
        ->when(Auth()->user()->role_id == 2,function($query){
            $query->where('emp_id',Auth()->user()->id);
        })
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('salary_slips.month', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.year', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.total_working_days', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.present_days', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.payable_salary', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.leave', 'like', '%' . $search . '%')
                      ->orWhere('salary_slips.created_at', 'like', '%' . $search . '%')
                      ->orWhereHas('employeeDetail', function ($query) use ($search) {
                        $query->where('users.name', 'like', '%' . $search . '%');
                    });
            });
        })->latest()
        ->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=salaryslip Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Employee Name', 'Month', 'Year', 'Working Days', 'Present Days', 'Payable Salary', 'Leave');
            $callback = function () use ($salarySlipList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($salarySlipList as $salary) {
                    
                    fputcsv($file, array($salary->employeeDetail->name, $salary->month, $salary->year, $salary->total_working_days, $salary->present_days, $salary->payable_salary, $salary->leave));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $mainLogoUrl = asset('storage/logo/UQcj8rCZqMmTVJ89a4zx0Vzz7UZ5AtbbWazxf2cd.png');
            $html = view('admin.pdf.salaryslip', ['salarySlipList' => $salarySlipList, 'mainLogoUrl' => $mainLogoUrl])->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response()->streamDownload(
                function () use ($dompdf) {
                    echo $dompdf->output();
                },
                'SalarySlip.pdf'
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userList = User::where('role_id', '!=', '1')->get();
        return view('admin.salaryslip.create', compact('userList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $monthNumber = UtilityHelper::convertMonthNumberToMonthName($request->month);
        $currentMonth = UtilityHelper::getCurrentMonth();
        $currentYear = UtilityHelper::getCurrentYear();
        if ($currentMonth == 1) {
            $currentYear = $currentYear - 1;
        }
        $totalMonthDay = UtilityHelper::totalDayOfMonth($monthNumber, $currentYear);
        $users = User::where('role_id', '!=', 1)->with('deductionDetail')->get();
        $totalHoliday = Holiday::whereYear('holiday_date', $currentYear)->whereMonth('holiday_date', $monthNumber)->count();
        foreach ($users as $key => $user) {
            $totalOvertime = UtilityHelper::getUserTotalOverTime($user->id, $monthNumber, $currentYear);
            $perDaySalary = $user->employee_salary / $totalMonthDay;
            $totalLeave = Leave::where('user_id', $user->id)->sum('total_leave_day');
            $totalAmountPayable = $user->basic_amount + $user->hra_amount + $user->allowance_amount + $user->petrol_amount;
            $totalDeduction = ($perDaySalary * $totalLeave) + $totalAmountPayable;
            $hourSalary = $perDaySalary / 8;
            $overtimeAmount  = $hourSalary * $totalOvertime;
            
            $attendanceDetail = Attendance::whereMonth('attendance_date',$monthNumber)->whereYear('attendance_date',$currentYear)->where('user_id',$user->id)->get();
            $presentDays = 0;
            foreach ($attendanceDetail as $key => $attendance) {
                if($attendance->status == 1){
                    $presentDays += 1; 
                } else if($attendance->status == 2){
                    $presentDays += 0.5;
                }
            }
            $weekOff = UtilityHelper::getWeekOff($monthNumber, $currentYear);
            $paidDays = $presentDays + $totalHoliday + $weekOff;
            $paySalary = $perDaySalary * $paidDays;
            $salarySlip = new SalarySlip();
            $salarySlip->emp_id = $user->id;
            $salarySlip->month = $request->month;
            $salarySlip->year = $currentYear;
            $salarySlip->holiday = $totalHoliday;
            $salarySlip->total_working_days = $totalMonthDay;
            $salarySlip->present_days = $presentDays;
            $salarySlip->absent_day = $totalMonthDay - $presentDays;
            $salarySlip->payable_salary = $paySalary;
            $salarySlip->paid_day = $paidDays;
            $salarySlip->leave = $totalLeave;
            $salarySlip->over_time_amount = $overtimeAmount;
            $salarySlip->per_day_salary = $perDaySalary;
            $salarySlip->total_over_time = (int) $totalOvertime;
            $salarySlip->week_off = $weekOff;
            $salarySlip->save();
            foreach ($user->deductionDetail as $key => $deduct) {
                $deductDetail = new SalarySlipDetail();
                $deductDetail->salary_slip_id = $salarySlip->id;
                $deductDetail->month = $request->month;
                $deductDetail->year = $currentYear;
                $deductDetail->deduction_type = $deduct->deduction_type;
                $deductDetail->deduction_amount = $deduct->amount;
                $deductDetail->emp_id = $user->id;
                $deductDetail->save();
            }
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => "Salary Slip",
                'description' => "Salary Slip Generated For" . $user->name . " of Month is " . $request->month . " and year is " . $currentYear,
            ]);
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            $offerData = [
                'user_id' => $user->id,
                'type' => 'Salary Slip',
                'title' => "Salary Slip Created for Month is " . $request->month ." Year is ".$currentYear,
                'text' => "Salary Slip ",
                'url' => route('salary-slip.index'),
            ];
            $pusher->trigger('notifications', 'new-notification', $offerData);
        }
        return redirect()->route('salary-slip.index')->with('success', 'Salary Slip Generated Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $userList = User::where('role_id', '!=', '1')->get();
        $salaryDetail = $this->salarySlip->with('employeeDetail','deductionDetail')->find($id);
        return view('admin.salaryslip.edit', compact('userList', 'salaryDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $salary = SalarySlip::where('id', $id)->first();
        if ($salary) {
            $salary->present_days = $request->present_day;
            $salary->absent_day = $request->absent_day;
            $salary->total_over_time = $request->total_over_time;
            $salary->over_time_amount = $request->over_time_amount;
            $salary->save();
            foreach ($request->deduction_id as $key => $deduct) {
                $salaryDetail = SalarySlipDetail::where('id', $deduct)->first();
                if ($salaryDetail) {
                    $salaryDetail->deduction_type = $request->deduction_type[$key];
                    $salaryDetail->deduction_amount = $request->amount[$key];
                    $salaryDetail->emp_id = $salary->emp_id;
                    $salaryDetail->save();
                } else {
                    $deductDetail = new SalarySlipDetail();
                    $deductDetail->salary_slip_id = $id;
                    $deductDetail->month = $salary->month;
                    $deductDetail->year = $salary->year;
                    $deductDetail->deduction_type = $request->deduction_type[$key];
                    $deductDetail->deduction_amount = $request->amount[$key];
                    $deductDetail->emp_id = $salary->emp_id;
                    $deductDetail->save();
                }
            }
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => "Salary Slip",
                'description' => "Salary Slip Updated",
            ]);
            return redirect()->route('salary-slip.index')->with('success', 'Salary Slip Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salary = $this->salarySlip->find($id);
        if ($salary) {
            $salary->delete();
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => "Salary Slip",
                'description' => "Salary Slip Deleted",
            ]);
            return response()->json(['status' => 1, 'message' => 'Salary Slip deleted successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something went to wrong.'], 500);
    }

    public function generateSalarySlip(Request $request)
    {
        $id = $request->id;
        $salaryDetail = $this->salarySlip->find($id);
        $employee = User::find($salaryDetail->emp_id);
        if (isset($employee)) {
            $shiftDetail = ShiftTime::find($employee->shift_id);
            $departmentDetail = Department::find($employee->department_id);
            $designationDetail = Designation::find($employee->designation_id);
            $deductionDetail = SalarySlipDetail::where('salary_slip_id', $id)->get();
            $dompdf = new Dompdf();
            $viewFile = view('admin.pdf.salary_slip', compact('shiftDetail', 'salaryDetail', 'employee', 'deductionDetail', 'departmentDetail', 'designationDetail'))->render();
            $dompdf->loadHtml($viewFile);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $output = $dompdf->output();
            $fileName =  'salary_slip_' . $employee->name  ."_" .$salaryDetail->month . "_".$salaryDetail->year. '.pdf';
            $filePath = 'salary_slip/' . $fileName;
            Storage::disk('public')->put($filePath, $output);
            $salaryDetail->file_path = $fileName;
            $update = $salaryDetail->save();
            if ($update) {
                return response()->json([
                    'status' => 'success',
                    'download_url' => asset('storage/' . $filePath)
                ], 200);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ], 500);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong.'
        ], 500);
    }

    public function deductionDelete(Request $request)
    {
        $id = $request->id;
        $delete = SalarySlipDetail::where('id', $id)->delete();
        if ($delete) {
            return response()->json(['status' => 1, 'message' => 'Deduction deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }
}
