<?php

namespace App\Http\Controllers\hr;

use App\Http\Controllers\Controller;
use App\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Http\Request;
use App\Helpers\UtilityHelper;
use App\Http\Requests\CreateSalarySlip;
use App\Interfaces\AttendanceRepositoryInterface;
use App\Interfaces\LeaveRepositoryInterface;
use App\Interfaces\SalarySlipRepositoryInterface;
use Carbon\Carbon;
use PDF;


class SalarySlipController extends Controller
{
    protected $employeeRepository, $leaveRepository, $attendanceRepository, $salarySlipRepository;
    public function __construct(EmployeeRepositoryInterface $employeeRepository, LeaveRepositoryInterface $leaveRepository, AttendanceRepositoryInterface $attendanceRepository, SalarySlipRepositoryInterface $salarySlipRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->leaveRepository = $leaveRepository;
        $this->attendanceRepository = $attendanceRepository;
        $this->salarySlipRepository = $salarySlipRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = "Salary Slip List";
        $employeeLists = $this->employeeRepository->getAllData('', '', '', 'export');

        $date = Carbon::now();
        $lastMonth =  $date->subMonth()->format('F');
        $monthNumber =  $date->format('m');

        return view('hr.salaryslip.index', compact('employeeLists', 'lastMonth', 'monthNumber','page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSalarySlip $request)
    {
        $monthNumber = $request->month;
        $month = date("F", mktime(0, 0, 0, $monthNumber, 10));
        $employee = $this->employeeRepository->getAllEmployee();
        $totalSunday = UtilityHelper::getTotalSundaysOfMonth($monthNumber, date('Y'));
        $data['totalDay'] = $totalDay = 30 - $totalSunday;
        $totalMonth = UtilityHelper::getTotalMonthPast($monthNumber);
        $totalPastLeave = $totalMonth * 2;
        foreach ($employee as $key => $emp) {
            $salary = $emp->employee_salary;
            $number = UtilityHelper::monthNumber($month);
            $data['totalPresentDay'] = $totalPresentDay = $this->attendanceRepository->getPresentCount($number, $emp->id, '1');
            $data['totalAbsentDay'] = $this->attendanceRepository->getPresentCount($number, $emp->id, '0');
            $data['totalHalfDay'] = $this->attendanceRepository->getPresentCount($number, $emp->id, '2');

            $totalUseLeaveYear = $this->leaveRepository->getTotalYearLeave($emp->id);
            $leaveMonth = $this->leaveRepository->getTotalMonthLeave($month, $emp->id);
            $data['totalLeave'] = 0;
            $data['totalYearLeave'] = $totalYearLeave = 0;

            foreach ($leaveMonth as $leave) {
                $data['totalLeave'] += UtilityHelper::getDiffBetweenDates($leave->leave_from, $leave->leave_to);
            }
            foreach ($totalUseLeaveYear as $leaveYear) {
                if (Carbon::parse($leaveYear->leave_from)->format('F') !== $month) {
                    $data['totalYearLeave'] = $totalYearLeave += UtilityHelper::getDiffBetweenDates($leaveYear->leave_from, $leaveYear->leave_to);
                }
            }
            $perDaySalary = $salary / $totalDay;
            $diffBetween = 0;
            if ($totalYearLeave !== $totalPastLeave) {
                $diffBetween = $totalPastLeave - $totalYearLeave;
            }
            $useLeaveMonth = 2 + $diffBetween;
            $thisMonthLeave = $this->leaveRepository->getMonthLeave($monthNumber);
            $totalMonthLeave = 0;
            foreach ($thisMonthLeave as $leave) {
                $data['totalMonthLeave'] = $totalMonthLeave += UtilityHelper::getDiffBetweenDates($leave->leave_from, $leave->leave_to);
            }
            $data = [
                'emp_id' => $emp->id,
                'month' => date("F", mktime(0, 0, 0, $request->month, 10)),
                'working_days' => $totalDay,
                'present_days' => $totalPresentDay,
                'leaves' => $data['totalLeave'],
                'pt' => 200,
                'payable_salary' => $salary,
            ];
            if ($useLeaveMonth < $totalMonthLeave) {
                $cutSalary = $perDaySalary * $totalMonthLeave;
                $data['payable_salary'] = $salary - $cutSalary;
            }
            $this->salarySlipRepository->store($data);
        }
        return response()->json(['data' => '', 'message' => 'Salary Slip Created Successfully', 'status' => 1], 200);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function calculateSalarySlip(Request $request)
    {
        $id = $request->id;
        $month = $request->month;
        $employee = $this->employeeRepository->getDetailById($id);
        $salary = $employee->employee_salary;
        if ($month == "") {
            $date = \Carbon\Carbon::now();
            $month =  $date->subMonth()->format('m');
        }
        $leaveMonth = $this->leaveRepository->getTotalMonthLeave($month, $id);
        $data['totalLeave'] = 0;
        $data['totalDay'] = $totalDay = 22;
        foreach ($leaveMonth as $leave) {
            $data['totalLeave'] += UtilityHelper::getDiffBetweenDates($leave->leave_from, $leave->leave_to);
        }
        $data['totalPresentDay'] = $totalPresentDay = $this->attendanceRepository->getPresentCount($month, $id, '1');
        $data['totalAbsentDay'] = $this->attendanceRepository->getPresentCount($month, $id, '0');
        $data['totalHalfDay'] = $this->attendanceRepository->getPresentCount($month, $id, '2');
        $perDaySalary = $salary / $totalDay;
        $data['paySalary'] = $perDaySalary * $totalPresentDay;
        return response()->json(['data' => $data, 'message' => '', 'status' => 1], 200);
    }

    public function ajaxList(Request $request)
    {
        $salarySlipList = $this->salarySlipRepository->getAllDetail($request->search);
        return view('hr.salaryslip.ajax_list', compact('salarySlipList'));
    }

    public function generateSalaryPDF(Request $request)
    {
        $empId = $request->userId;
        $salaryId = $request->salaryId;
        $data['employee'] = $this->employeeRepository->getDetailById($empId);
        $data['salaryDetail'] = $salaryDetail = $this->salarySlipRepository->getDetailById($salaryId);
        // dd($salaryDetail);
        $data['hraCalculate'] =  $hraCalculate = $salaryDetail->payable_salary * 10 / 100;
        $data['medicalAllowance'] =  $medicalAllowance = $salaryDetail->payable_salary * 5 / 100;
        $data['conveyanceAllowance'] =  $conveyanceAllowance = $salaryDetail->payable_salary * 7 / 100;
        $data['basicPay'] =  $basicPay = $salaryDetail->payable_salary - $hraCalculate - $medicalAllowance - $conveyanceAllowance;
        $data['pfCount'] = $salaryDetail->payable_salary * 20 / 100;
        $pdf = PDF::loadView('admin.pdf.salary_pdf', ['data' => $data]);
        return $pdf->download('Salary Slip.pdf');
    }
}
