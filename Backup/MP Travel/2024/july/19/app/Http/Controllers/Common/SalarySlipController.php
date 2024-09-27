<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\SalarySlip;
use App\Models\Log;
use App\Models\SalarySlipDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;

class SalarySlipController extends Controller
{
    private $salarySlip;
    public function __construct()
    {
        $page = "Salary Slip";
        $this->salarySlip = resolve(SalarySlip::class)->with('employeeDetail','employeeDetail.departmentDetail','employeeDetail.designationDetail');
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salarySlipList = $this->salarySlip->paginate(10);
        return view('admin.salaryslip.index',compact('salarySlipList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userList = User::where('role_id','!=', '1')->get();
        return view('admin.salaryslip.create',compact('userList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $totalMonthDay = UtilityHelper::totalDayOfMonth();
        $user = User::find($request->emp_id)->first();
        if($user){
            $perDaySalary = $user->employee_salary / $totalMonthDay;
            $totalLeave = Leave::where('user_id', $user->id)->sum('total_leave_day');
            $totalDeduction = $perDaySalary * $totalLeave;
            $paySalary = $user->employee_salary - $totalDeduction;
            $data = [
                'emp_id' => $user->id,
                'month' => Carbon::now()->format('F'),
                'year' => Carbon::now()->format('Y'),
                'total_working_days' => $totalMonthDay,
                'present_days' => $totalMonthDay - $totalLeave,
                'payable_salary' => $paySalary,
                'leave' => $totalLeave,
            ];
            $insert = $this->salarySlip->create($data);
            if($insert){
                foreach ($request->deduction_type as $key => $type) {
                    $data1 = [
                        'month'=>Carbon::now()->format('F'),
                        'year'=>Carbon::now()->format('Y'),
                        'deduction_type' =>$type,
                        'deduction_amount' => $request->amount[$key],
                        'emp_id' => $user->id,
                    ];
                    SalarySlipDetail::create($data1);
                }
                Log::create([
                    'user_id' => auth()->user()->id,
                    'action' => "Salary Slip Generated",
                ]);
            }
            return redirect()->route('salary-slip.index')->with('success', 'Salary Slip Generated Successfully');
        }
        return redirect()->route('salary-slip.create')->with('error', 'Something Went to Wrong');
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

    public function generateSalarySlip(Request $request){
        $id = $request->id;
        $salaryDetail = $this->salarySlip->find($id);
        $employee = User::find($salaryDetail->emp_id);
        $deductionDetail = SalarySlipDetail::where('emp_id', $salaryDetail->emp_id)->get();
        $dompdf = new Dompdf();
        $viewFile = view('admin.pdf.salary_slip', compact('salaryDetail','employee','deductionDetail'))->render();
        $dompdf->loadHtml($viewFile);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $fileName =  'salary_slip_' . $employee->id . '.pdf';
        $filePath = 'salary_slip/' . $fileName;
        Storage::disk('public')->put($filePath, $output);
        $salaryDetail->file_path = $fileName;
        $update = $salaryDetail->save();
        if ($update) {
            return response()->json([
                'status' => 'success',
                'download_url' => asset('storage/' . $filePath)
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong.'
        ]);
    }
}
