<?php

namespace App\Http\Controllers\employee;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\AttendanceRepositoryInterface;
use App\Interfaces\LeaveRepositoryInterface;
use Illuminate\Http\Request;
use PDF;

class EmployeeLeaveController extends Controller
{
    protected $leaveRepository,$attendanceRepository = "";

    public function __construct(LeaveRepositoryInterface $leaveRepository, AttendanceRepositoryInterface $attendanceRepository)
    {
        $this->leaveRepository = $leaveRepository;
        $this->attendanceRepository = $attendanceRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = "Leave List";
        return view('employee.apply_leave.index',compact('page'));
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
    public function store(Request $request)
    {
        $data = [
            'leave_type' => $request->leave_type,
            'leave_from' => $request->leave_from,
            'leave_to' => $request->leave_to,
            'reason' => $request->reason,
            'created_by' => Auth()->user()->id,
        ];
        $feature = $request->leave_feature;
        if($request->leave_to == UtilityHelper::convertYmd("")){
            $attendance = $this->attendanceRepository->getTodayAttendanceUser();
            $diff = UtilityHelper::getDiffBetweenTwoTime($attendance->login_time,"");
            $feature = "1";
            if($diff > 4){
                $feature = "0";
            }
        }
        $data['leave_feature'] = $feature;
        $insert = $this->leaveRepository->store($data);
        if ($insert) {
            $log = 'Leave From ' . $request->leave_from . ' To ' . $request->leave_to . ' Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Leave', $log);
            return response()->json(['data' => $insert, 'message' => 'Leave Created Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
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
        $leave = $this->leaveRepository->getDetailById($id);
        return response()->json(['data' => $leave, 'message' => '', 'status' => 1], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'leave_type' => $request->leave_type,
            'leave_from' => $request->leave_from,
            'leave_to' => $request->leave_to,
            'reason' => $request->reason,
            'leave_feature' => $request->leave_feature,
            'status' => $request->status == "on" ? '1' : '0',
        ];
        $where['id'] = $id;
        $update = $this->leaveRepository->update($data, $where);
        if ($update) {
            $log = 'Leave From ' . $request->leave_from . ' To ' . $request->leave_to . ' Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Leave', $log);
            return response()->json(['data' => $update, 'message' => 'Leave Updated Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leave = $this->leaveRepository->getDetailById($id);
        $delete = $this->leaveRepository->delete($id);
        if ($delete) {
            $log = 'Leave From ' . $leave->leave_from . ' To ' . $leave->leave_to . ' Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Leave', $log);
            return response()->json(['data' => $delete, 'message' => 'Leave Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function ajaxList(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $status = $request->status;
        $feature = $request->feature;
        $leaveList = $this->leaveRepository->getAllData($search,$date,$status,$feature,"paginate");
        return view('employee.apply_leave.ajax_list', compact('leaveList'));
    }

    public function exportCSV(Request $request){
        $search = $request->search;
        $date = $request->date;
        $status = $request->status;
        $feature = $request->feature;
        $leaveList = $this->leaveRepository->getAllData($search,$date,$status,$feature,"export");
        if($request->format == "excel" || $request->format == "csv"){
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Leave.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Leave Type', 'Leave From', 'Leave To', 'Leave Feature','Reason','Status','Total Days');
            $callback = function () use ($leaveList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($leaveList as $leave) {
                    
                    $feature =$leave->leave_feature == "1"?'Full Day':'Half Day';
                    $status ="Pending";
                    if($leave->leave_status == "2"){
                        $status = "Approve";
                    }
                    if($leave->leave_status == "3"){
                        $status = "Reject";
                    }
                    $day = UtilityHelper::getDiffBetweenDates($leave->leave_from,$leave->leave_to);
                    fputcsv($file, array($leave->leave_type, $leave->leave_from, $leave->leave_to, $feature,$leave->reason,$status, $day));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if($request->format == "pdf"){
            $pdf = PDF::loadView('admin.pdf.leave', ['leaveList' => $leaveList]);
            return $pdf->download('Leave.pdf');
        }
    }
}
