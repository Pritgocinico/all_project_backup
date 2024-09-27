<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\LeaveRepositoryInterface;
use App\Helpers\UserLogHelper;
use App\Http\Requests\CreateCertificate;
use App\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Support\Facades\DB;  
use Dompdf\Dompdf;

class HrManagerController extends Controller
{
    protected $leaveRepository,$employeeRepository= "";

    public function __construct(LeaveRepositoryInterface $leaveRepository,EmployeeRepositoryInterface $employeeRepository)
    {
        $this->leaveRepository = $leaveRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function index(){
        return view('admin.hr_manager.info_sheet.index');
    }

    public function create(){
        return view('admin.hr_manager.info_sheet.create');
    }

    public function holidayList(){
        return view('admin.hr_manager.holiday.index');
    }

    public function leaveList (){
        $user = DB::table('users')->where('id','7')->get();
        $page = "Leave List";
        return view('admin.hr_manager.leave.index',compact('page'));
    }

    public function leaveajaxList (Request $request){
        $search = $request->search;
        $leaveList = $this->leaveRepository->getAllLeaveData($search,"","","paginate");
        return view('admin.hr_manager.leave.ajax_list', compact('leaveList'));
    }

    public function leaveStatusUpdate(Request $request){
        $data = [
            'leave_status' => $request->status == "approve"?'2':'3',
            'reject_reason' => null,
        ];
        if($request->status == 'reject'){
            $data['reject_reason'] = $request->reject_reason;
        }
        $where['id']= $request->id;
        $update = $this->leaveRepository->update($data,$where);
        if ($update) {
            $log = 'Leave Status  ' . ucfirst($request->status) . ' change by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Leave', $log);
            return response()->json(['data' => $update, 'message' => 'Leave Status Updated Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);

    }
    
    public function certificateList (){
        $page = "Certificate List";
        $employeeList = $this->employeeRepository->getAllData('','','','export');
        return view('admin.hr_manager.certificate.index',compact('page','employeeList'));
    }   

    public function GenerateCertificate(Request $request)
    {
        $empName = $request->empName;
        $winnerNumber = $request->winnerNumber;
        
        // Load image files as base64 encoded strings
        $logoUrl = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/media/svg/AgroJivanLogo.png')));
        $mainLogoUrl = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/images/certificate/' . $winnerNumber . '-m.png')));
        
        $dompdf = new Dompdf();
        // Load the HTML with embedded images
        $viewFile = view('admin.pdf.certificate', compact('empName', 'logoUrl', 'mainLogoUrl'))->render();
        $dompdf->loadHtml($viewFile);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        // Output the generated PDF
        return $dompdf->stream('Certificate.pdf');
    }

    public function autoGenerateCertificate(CreateCertificate $request){
        $empID = $request->employee_id;
        $title = $request->certificate_title;
        $description = $request->certificate_description;
        $employee =$this->employeeRepository->getDetailById($empID);
        $logoUrl = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/media/svg/AgroJivanLogo.png')));
        $mainLogoUrl = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/images/certificate/1-m.png')));
        
        $dompdf = new Dompdf();
        $viewFile = view('admin.pdf.auto_certificate', compact('employee','description' ,'title','logoUrl', 'mainLogoUrl'))->render();
        $dompdf->loadHtml($viewFile);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->stream('Certificate.pdf');
    }
}
