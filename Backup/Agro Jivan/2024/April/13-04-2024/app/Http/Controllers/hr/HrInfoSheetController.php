<?php

namespace App\Http\Controllers\hr;

use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\HolidayRepositoryInterface;
use App\Interfaces\InfoSheetRepositoryInterface;
use App\Interfaces\LeaveRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\TicketRepositoryInterface;
use App\Interfaces\UserPermissionRepositoryInterface;
use Illuminate\Http\Request;
use App\Helpers\UtilityHelper;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\DepartmentRepositoryInterface;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Attendance;
use Carbon\Carbon;
use PDF;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\StoreInfoSheetsRequest;
use App\Models\InfoSheet;
use Notification;
use App\Notifications\OffersNotification;
use Pusher\Pusher;

class HrInfoSheetController extends Controller
{
    protected $infoSheetRepository,$holidayRepository,$leaveRepository,$ticketRepository,$employeeRepository,$userPermissionRepository,$orderRepository,$roleRepository, $departmentRepository = "";
    public function __construct(InfoSheetRepositoryInterface $infoSheetRepository, HolidayRepositoryInterface $holidayRepository,LeaveRepositoryInterface $leaveRepository,TicketRepositoryInterface $ticketRepository, EmployeeRepositoryInterface $employeeRepository, UserPermissionRepositoryInterface $userPermissionRepository, OrderRepositoryInterface $orderRepository, RoleRepositoryInterface $roleRepository, DepartmentRepositoryInterface $departmentRepository)
    {
        $this->infoSheetRepository = $infoSheetRepository;
        $this->holidayRepository = $holidayRepository;
        $this->leaveRepository = $leaveRepository;
        $this->ticketRepository = $ticketRepository;
        $this->employeeRepository = $employeeRepository;
        $this->userPermissionRepository = $userPermissionRepository;
        $this->orderRepository = $orderRepository;
        $this->roleRepository = $roleRepository;
        $this->departmentRepository = $departmentRepository;
    }
    public function index(){
        $page = "Info Sheet List";
        return view('hr.info_sheet.index',compact('page'));
    }

    public function employee(){
        $page = "Employee List";
        $roleList = $this->roleRepository->getAllRole();
        $employeeList = $this->employeeRepository->getAllData('', '', '', 'export');
        $absent = Attendance::where('attendance_date', Carbon::now()->format('Y-m-d'))->where('status', '0')->count();
        $present = Attendance::where('attendance_date', Carbon::now()->format('Y-m-d'))->where('status', '1')->count();
        $data = [
            'labels' => ['Absent', 'Present'],
            'data' => [$absent, $present],
        ];
        return view('hr.employee.index', compact('roleList', 'employeeList', 'data','page'));
    }
    
    public function employeeajaxList(Request $request)
    {
        $status = $request->status;
        $role = $request->role;
        $search = $request->search;
        $employeeList = $this->employeeRepository->getAllData($status, $role, $search, 'paginate');
        return view('hr.employee.ajax_list', compact('employeeList'));
    }

    public function ajaxList(Request $request){
        $search = $request->search;
        $infoSheetList = $this->infoSheetRepository->getAllData($search,"","paginate",'');
        return view('hr.info_sheet.ajax_list',compact('infoSheetList'));
    }

    public function holiday(){
        $page = "Holiday List";
        return view('hr.holiday.index',compact('page'));
    }

    public function holidayAjaxList(Request $request){
        $search = $request->search;
        $holidayList = $this->holidayRepository->getAllData($search);
        return view('hr.holiday.ajax_list',compact('holidayList'));
    }
    
    public function leave(){
        $page = "Leave List";
        $employeeList = $this->employeeRepository->getAllData('','','','export');
        return view('hr.leave.index', compact('employeeList','page'));
    }

    public function leaveAjaxList(Request $request){
        $search = $request->search;
        $userId = $request->userId;
        $dateFilter = $request->dateFilter;
        $leaveList = $this->leaveRepository->getAllLeaveData($search, $dateFilter, $userId);
        return view('hr.leave.ajax_list',compact('leaveList'));
    }

    public function leaveStatusUpdate(Request $request){
        $data = [
            'leave_status' => $request->status == "approve"?'2':'3',
            'reject_reason' => "",
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

    public function ticket(){
        $employeeList = $this->employeeRepository->getAllData('','','','export');
        return view('hr.ticket.index', compact('employeeList'));
    }

    public function ticketAjaxList(Request $request){
        $search = $request->search;
        $userId = $request->userId;
        $dateFilter = $request->dateFilter;
        $ticketList = $this->ticketRepository->getAllTicketData($search,$dateFilter, $userId,"");
        return view('hr.ticket.ajax_list',compact('ticketList'));
    }

    public function attendance(){
        return view('hr.attendance.index');
    }

    public function attendanceAjaxList(Request $request){
        $from = $request->from;
        $to = $request->to;
        $employeeAttendanceList = $this->employeeRepository->getUserListWithAbsentPresentDetail($from,$to);
        return view('hr.attendance.ajax_list',compact('from','to','employeeAttendanceList'));
    }

    public function employeeView($id){
        $employee = $this->employeeRepository->getDetailById($id);
        $permissionList = $this->userPermissionRepository->getAllUserPermission($id);
        $todayOrder = $this->orderRepository->getUserOrderData($id,'daily');
        $allOrder = $this->orderRepository->getUserOrderData($id,'');
        $page = "Employee View";
        return view('hr.attendance.show', compact('employee', 'permissionList','todayOrder','allOrder','page'));
    }

    public function updateUserPermission(Request $request)
    {
        $id = $request->id;
        $permissions = $this->userPermissionRepository->getDetailByUserId($id);
        if (count($permissions) > 0) {
            $permission = array();
            foreach ($permissions as $per) {
                $permission[$per->capability] = $per->value;
            }
            foreach ($request->permission as $key => $permission) {
                foreach ($permission as $key1 => $val1) {
                    foreach ($permission as $per_key => $per_val) {
                        if ($key1 == $per_key) {
                            $where = [
                                'capability' => $key1, 'user_id' => $id
                            ];
                            $update = [
                                'value' => $val1 == "on" ? '1' : '0',
                            ];
                            $permissions = $this->userPermissionRepository->update($where, $update);
                        }
                    }
                }
            }
        }
        return redirect('hr/hr-employee-view/' . $id)->with('success', 'User Permission Updated Successfully.');
    }

    public function employeeEdit(string $id)
    {
        $page = "Employee Edit";
        $employee = $this->employeeRepository->getDetailById($id);
        $roleList = $this->roleRepository->getAllRole();
        $departmentList = $this->departmentRepository->getAllDepartmentWithPaginate();
        $data = [];
        foreach ($employee->departmentDetail as $department) {
            array_push($data, $department->department_id);
        }
        $employee['department'] = $data;
        return view('hr.employee.edit', compact('roleList', 'employee', 'departmentList','page'));
    }

    public function update(UpdateEmployeeRequest $request, string $id)
    {
        $data = [
            'shift_type' => $request->shift_type,
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'status' => $request->status == "on" ? '1' : '0',
            'role_id' => $request->role,
            'system_code' => $request->system_code,
            'employee_salary' => $request->employee_salary,
            'join_date' => UtilityHelper::convertYmd($request->join_date),
            'is_manager' => $request->is_manager == 'yes' ? '1' : '0',
        ];
        if ($request->password !== "") {
            $data['password'] = \Hash::make($request->password);
        }
        if ($request->hasfile('aadhar_card')) {
            $file = $request->file('aadhar_card');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/employee/aadhar', $filename);
            $data['aadhar_card'] = 'employee/aadhar/' . $filename;
        }
        if ($request->hasfile('pan_card')) {
            $file = $request->file('pan_card');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/employee/pan', $filename);
            $data['pan_card'] = 'employee/pan/' . $filename;
        }
        if ($request->hasfile('qualification')) {
            $file = $request->file('qualification');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/employee/qualification', $filename);
            $data['qualification'] = 'employee/qualification/' . $filename;
        }
        if ($request->hasfile('join_agreement')) {
            $file = $request->file('join_agreement');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/employee/agreement', $filename);
            $data['join_agreement'] = 'employee/agreement/' . $filename;
        }
        $where = ['id' => $id];
        $update = $this->employeeRepository->update($data, $where);
        if ($update) {
            $createUserRole = $this->roleRepository->getDetailById($request->role);
            $log =  ucfirst($createUserRole->name) . ' (' . ucfirst($request->name) . ') Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog(ucfirst($createUserRole->name), $log);
            $whereRole = ['user_id' => $id];
            $role = ['role_id' => $request->role];
            $this->roleRepository->update($role, $whereRole);
            $this->departmentRepository->deleteUserDepartment($id);
            foreach ($request->department as $department) {
                $deptInsert = ['department_id' => $department, 'user_id' => $id];
                $this->departmentRepository->storeDepartmentUser($deptInsert);
            }
            return redirect('hr/employees')->with('success', 'Employee Updated Successfully.');
        }
        return redirect('hr/employees/' . $id . '/edit')->with('error', 'Something Went To Wrong.');
    }

    public function calendar(Request $request, $id)
    {
        if ($request->ajax()) {
            $holidays = DB::table('holidays')
                ->get()
                ->map(function ($item) {
                    return [
                        'title' => 'Holiday',
                        'start' => Carbon::parse($item->date)->format('Y-m-d'),
                        'end' => Carbon::parse($item->date)->format('Y-m-d'),
                        'color' => '#d0995d',
                    ];
                })->toArray();
            $attendances = DB::table('attendances as a')
                ->where('user_id', $id)
                ->get()
                ->map(function ($item) {
                    return [
                        'title' => ($item->status == 1 ? 'Present' : 'Absent'),
                        'start' => Carbon::parse($item->created_at)->format('Y-m-d'),
                        'end' => Carbon::parse($item->created_at)->format('Y-m-d'),
                        'color' => $item->status == 1 ? 'green' : 'red',
                    ];
                })->toArray();

            $leave = DB::table('leave as a')
                ->where('created_by', $id)
                ->get()
                ->map(function ($item) {
                    return [
                        'title' => $item->leave_type,
                        'start' => Carbon::parse($item->leave_from)->format('Y-m-d'),
                        'end' => Carbon::parse($item->leave_to)->format('Y-m-d'),
                        'color' => $item->leave_status == 2 ? 'green' : 'red',
                    ];
                })->toArray();

            $data = [...$holidays, ...$attendances,...$leave];
            return response()->json($data);
        }
    }

    public function exportFile(Request $request)
    {
        $role = $request->role;
        $search = $request->search;
        $employeeList = $this->employeeRepository->getAllData('', $role, $search, 'export');
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=employee Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Name', 'Email', 'Phone Number', 'Role', 'Status', 'Created At');
            $callback = function () use ($employeeList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($employeeList as $employee) {
                    $role = isset($employee->roleDetail) ? $employee->roleDetail->name : "";
                    $status = "Inactive";
                    if ($employee->status == 1) {
                        $status = "Active";
                    }
                    $date = "";
                    if (isset($employee->created_at)) {
                        $date = UtilityHelper::convertFullDateTime($employee->created_at);
                    }
                    fputcsv($file, array($employee->name, $employee->email, $employee->phone_number, $role, $status, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {

            // return view('admin.pdf.employee',compact('employeeList'));
            $pdf = PDF::loadView('hr.pdf.employee', ['employeeList' => $employeeList]);
            return $pdf->download('test.pdf');
        }
    }

    public function employeeAdd ()
    {
        $page = "Create Employee";
        $roleList = $this->roleRepository->getAllRole();
        $departmentList = $this->departmentRepository->getAllDepartmentWithPaginate();
        $systemCode = $this->employeeRepository->getNewSystemcode();
        return view('hr.employee.create', compact('roleList', 'departmentList', 'systemCode','page'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function employeeAddpost(CreateEmployeeRequest $request)
    {
        $data = [
            'shift_type' => $request->shift_type,
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => \Hash::make($request->password),
            'status' => $request->status == "on" ? '1' : '0',
            'role_id' => $request->role,
            'system_code' => $request->system_code,
            'employee_salary' => $request->employee_salary,
            'join_date' => UtilityHelper::convertYmd($request->join_date),
            'is_manager' => $request->is_manager == 'yes' ? '1' : '0',
        ];
        if ($request->hasfile('aadhar_card')) {
            $file = $request->file('aadhar_card');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/employee/aadhar', $filename);
            $data['aadhar_card'] = 'employee/aadhar/' . $filename;
        }
        if ($request->hasfile('pan_card')) {
            $file = $request->file('pan_card');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/employee/pan', $filename);
            $data['pan_card'] = 'employee/pan/' . $filename;
        }
        if ($request->hasfile('qualification')) {
            $file = $request->file('qualification');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/employee/qualification', $filename);
            $data['qualification'] = 'employee/qualification/' . $filename;
        }
        if ($request->hasfile('join_agreement')) {
            $file = $request->file('join_agreement');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/employee/agreement', $filename);
            $data['join_agreement'] = 'employee/agreement/' . $filename;
        }
        $insert = $this->employeeRepository->store($data);
        if ($insert) {
            $createUserRole = $this->roleRepository->getDetailById($request->role);
            $log =  ucfirst($createUserRole->name) . ' (' . ucfirst($request->name) . ') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog(ucfirst($createUserRole->name), $log);
            $role = ['user_id' => $insert->id, 'role_id' => $insert->role_id];
            $this->roleRepository->storeRoleUser($role);
            foreach ($request->department as $department) {
                $deptInsert = ['department_id' => $department, 'user_id' => $insert->id];
                $this->departmentRepository->storeDepartmentUser($deptInsert);
            }
            $permissions = [
                [
                    'feature'       =>  'employee',
                    'capability'    =>  'employee-list',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'employee',
                    'capability'    =>  'employee-edit',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'employee',
                    'capability'    =>  'employee-create',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'employee',
                    'capability'    =>  'employee-delete',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'department',
                    'capability'    =>  'department-list',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'department',
                    'capability'    =>  'department-create',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'department',
                    'capability'    =>  'department-edit',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'department',
                    'capability'    =>  'department-delete',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'category',
                    'capability'    =>  'category-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'category',
                    'capability'    =>  'category-create',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'category',
                    'capability'    =>  'category-edit',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'category',
                    'capability'    =>  'category-delete',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'product',
                    'capability'    =>  'product-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'product',
                    'capability'    =>  'product-own-view',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'product',
                    'capability'    =>  'product-create',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'product',
                    'capability'    =>  'product-edit',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'product',
                    'capability'    =>  'product-delete',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'systemengineer',
                    'capability'    =>  'systemengineer-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'systemengineer',
                    'capability'    =>  'systemengineer-create',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'systemengineer',
                    'capability'    =>  'systemengineer-edit',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'systemengineer',
                    'capability'    =>  'systemengineer-delete',
                    'value'         =>  '0'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'lead-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'lead-add',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'info-sheet-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'ticket-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'ticket-add',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'ticket-edit',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'ticket-delete',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'info-sheet-add',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'info-sheet-edit',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'info-sheet-delete',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'certificate-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'attendance-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'salary-slip-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'salary-slip-create',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'attendance-view',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'leave-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'leave-add',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'leave-edit',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'leave-delete',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'holiday-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Human Resource',
                    'capability'    =>  'holiday-add',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'sale',
                    'capability'    =>  'order-own-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'sale',
                    'capability'    =>  'order-add',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'sale',
                    'capability'    =>  'order-edit',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'sale',
                    'capability'    =>  'order-view',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'sale',
                    'capability'    =>  'pending-order-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'sale',
                    'capability'    =>  'cancel-order-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'sale',
                    'capability'    =>  'return-order-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'sale',
                    'capability'    =>  'complete-order-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'driver',
                    'capability'    =>  'driver-delivery-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'driver',
                    'capability'    =>  'driver-order-status-update',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'confirm',
                    'capability'    =>  'assign-driver-order-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'system engineer',
                    'capability'    =>  'engineer-ticket-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'system engineer',
                    'capability'    =>  'engineer-ticket-view',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'system engineer',
                    'capability'    =>  'engineer-ticket-comment',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Transport Department',
                    'capability'    =>  'confirm-order-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Transport Department',
                    'capability'    =>  'bulk-assign-driver',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Transport Department',
                    'capability'    =>  'batch-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Warehouse Manager',
                    'capability'    =>  'all-batch-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Warehouse Manager',
                    'capability'    =>  'warehouse-stock-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Sales Manager',
                    'capability'    =>  'sales-stock-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Sales Manager',
                    'capability'    =>  'Pending-order-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Sales Manager',
                    'capability'    =>  'Completed-order-list',
                    'value'         =>  '1'
                ],
                [
                    'feature'       =>  'Sales Manager',
                    'capability'    =>  'Confirm-order-list',
                    'value'         =>  '1'
                ],
            ];
            foreach ($permissions as $permission) {
                $permissionStore = [
                    'feature' => $permission['feature'],
                    'capability' => $permission['capability'],
                    'value' => $permission['value'],
                    'user_id' => $insert->id,
                ];
                $this->userPermissionRepository->storeEmployeePermission($permissionStore);
            }
            return redirect('hr/employees')->with('success', 'Employee Created Successfully.');
        }
        return redirect('hr/hr-employees-add')->with('error', 'Something Went To Wrong.');
    }


    public function destroy(string $id)
    {
        $userDetail = $this->employeeRepository->getDetailById($id);
        $delete = $this->employeeRepository->delete($id);
        if ($delete) {
            $createUserRole = $this->roleRepository->getDetailById($userDetail->role_id);
            $log =  ucfirst($createUserRole->name) . ' (' . ucfirst($userDetail->name) . ') Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog(ucfirst($createUserRole->name), $log);
            return response()->json(['data' => '', 'message' => 'Employee Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
// Info sheet Add
    public function create(Request $request){
        $page = "Create Info Sheet";
        return view('hr.info_sheet.create',compact('page'));
    }
// Info Sheet Create
    public function store(StoreInfoSheetsRequest $request){
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status == "on" ? '1' : '0',
            'created_by' => Auth()->user()->id,
        ];
        if ($request->hasfile('info_sheet')) {
            $file = $request->file('info_sheet');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/info_sheet/', $filename);
            $data['info_sheet'] = 'info_sheet/' . $filename;
        }
        $insert = $this->infoSheetRepository->store($data);
        if ($insert) {
            $log = 'Info Sheet (' . ucfirst($request->title) . ') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Info Sheet', $log);
            try{
                $employeeList = $this->employeeRepository->getAllUsersIgnored(Auth()->user()->id);
                foreach ($employeeList as $emp) {
                    $offerData = [ 
                        'user_id' => $emp->id,
                        'type' => 'InfoSheet',
                        'title' => 'New Info Sheet Added.',
                        'text' => $request->title,
                        'url' => route('info-sheet.edit', $insert),
                    ];
                    Notification::send($emp, new OffersNotification($offerData));
                    app('pusher')->trigger('notifications', 'new-notification', $offerData);
                }    
            }catch(\Exception $e){
            }

            return redirect('hr/hr-info-sheet')->with('success', 'Info Sheet Created Successfully.');
        }
        return redirect('hr/hr-info-sheet.create')->with('error', 'Something Went To Wrong.');
    }

    public function edit(InfoSheet $infoSheets,string $id){
        $page ="Edit Info Sheet";
        $info = $this->infoSheetRepository->getDetailById($id);
        return view('hr.info_sheet.edit',compact('info','page'));
    }

    public function updateInfoSheet(StoreInfoSheetsRequest $request,string $id)
    {
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status == "on" ? '1' : '0',
        ];
        if ($request->hasfile('info_sheet')) {
            $file = $request->file('info_sheet');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/info_sheet/', $filename);
            $data['info_sheet'] = 'info_sheet/' . $filename;
        }
        $where['id'] = $id;
        $update = $this->infoSheetRepository->update($data,$where);
        if ($update) {
            $log = 'Info Sheet (' . ucfirst($request->title) . ') Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Info Sheet', $log);
            return redirect('hr/hr-info-sheet')->with('success', 'Info Sheet Updated Successfully.');
        }
        return redirect('hr/hr-info-sheet/' . $id . '/edit')->with('error', 'Something Went To Wrong.');
    }
    public function deleteInfoSheet(string $id){
        $info = $this->infoSheetRepository->getDetailById($id);
        $delete = $this->infoSheetRepository->delete($id);
        if ($delete) {
            $log = 'Info Sheet (' . ucfirst($info->title) . ') Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Info Sheet', $log);
            return response()->json(['data' => '', 'message' => 'Info Sheet Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function holidayCreate(Request $request){
        $data = [
            'holiday_date' => $request->holiday_date,
            'holiday_name' => $request->holiday_name,
        ];
        $insert = $this->holidayRepository->store($data);
        if ($insert) {
            $log = 'Holiday (date=' . $request->holiday_date . ' and name='.ucfirst($request->holiday_name).') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Holiday ', $log);
            return response()->json(['data' => $insert, 'message' => 'Holiday Created Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function exportCSV(Request $request){
        $holidayList = $this->holidayRepository->getAllDataExport($request->search);
        if($request->format == "excel" || $request->format == "csv"){
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Info Sheet.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Holiday Date', 'Reason', 'Created At');
            $callback = function () use ($holidayList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($holidayList as $holiday) {
                    $holidayDate = UtilityHelper::convertDmy($holiday->holiday_date);
                    $date = "";
                    if (isset($holiday->created_at)) {
                        $date = UtilityHelper::convertDmyWith12HourFormat($holiday->created_at);
                    }
                    fputcsv($file, array($holidayDate, $holiday->holiday_name,$date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if($request->format == "pdf"){
            $pdf = PDF::loadView('admin.pdf.holiday', ['holiday' => $holidayList]);
            return $pdf->download('Info Sheet.pdf');
        }
    }
    
    public function exportLeaveCSV(Request $request){
        $search = $request->search;
        $userId = $request->userId;
        $dateFilter = $request->dateFilter;
        $leaveList = $this->leaveRepository->getAllLeaveData($search, $dateFilter, $userId,"export");
        
        if($request->format == "excel" || $request->format == "csv"){
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Leave List.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Employee Name', 'Leave Type', 'Leave From', 'Leave To', 'Leave Feature','Reason','Status','Total Days');
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
                    fputcsv($file, array($leave->userDetail->name, $leave->leave_type, $leave->leave_from, $leave->leave_to, $feature,$leave->reason,$status, $day));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if($request->format == "pdf"){
            $pdf = PDF::loadView('admin.pdf.leave', ['leaveList' => $leaveList])->setPaper('a3', 'landscape');
            return $pdf->download('Leave List.pdf');
        }
    }

        
    public function exportTicketCSV(Request $request){
        $search = $request->search;
        $userId = $request->userId;
        $dateFilter = $request->dateFilter;
        $ticketList = $this->ticketRepository->getAllTicketData($search, $dateFilter, $userId,"export");
        if($request->format == "excel" || $request->format == "csv"){
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Ticket.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Ticket Type', 'Subject', 'Description', 'Status','Created At');
            $callback = function () use ($ticketList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($ticketList as $ticket) {
                    $text = 'Inactive';
                    if ($ticket->status == 1){
                        $text = 'Active';
                    }
                    $date = "";
                    if (isset($ticket->created_at)) {
                        $date = UtilityHelper::convertDmyWith12HourFormat($ticket->created_at);
                    }
                    fputcsv($file, array($ticket->ticket_type, $ticket->subject, $ticket->description, $text,$date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if($request->format == "pdf"){
            $pdf = PDF::loadView('admin.pdf.ticket', ['ticketList' => $ticketList]);
            return $pdf->download('Ticket.pdf');
        }
    }

    public function ticketShow(string $id){
        $ticket = $this->ticketRepository->getDataById($id);
        return view('hr.ticket.show',compact('ticket'));
    }

    public function addCommentTicket(Request $request)
    {
        $data = [
            'ticket_id' => $request->ticket_id,
            'comment' => $request->comment_message,
            'message_type' => 'text',
            'sent_by' => Auth()->user()->id,
        ];
        if ($request->hasfile('message_file')) {
            $file = $request->file('message_file');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/message/', $filename);
            $data['message_file'] = 'message/' . $filename;
            $data['message_type'] = 'file';
        }
        if ($request->comment_message !== null && $request->message_file !== null) {
            $data['message_type'] = "text_file";
        }
        $ticket = $this->ticketRepository->storeTicketComment($data);
        if ($ticket) {
            $log =  "Ticket " . $ticket->id . ' Comment Added by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Ticket', $log);
            $ticketDetail = $this->ticketRepository->getDataById($ticket->ticket_id);
            $userSchema = $this->employeeRepository->getDetailById($ticketDetail->created_by);
            $pusher = new Pusher(env('PUSHER_APP_KEY'),  env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            $offerData = [
                'user_id'=>$userSchema->id,
                'type' => 'Ticket',
                'title' => 'You received an Ticket Comment.',
                'text' => 'Ticket Comment',
                'url' => route('ticket.show',$ticket->ticket_id),
            ];
            Notification::send($userSchema, new OffersNotification($offerData));
            $pusher->trigger('notifications', 'new-notification', $offerData);
            $hrDetail = $this->employeeRepository->getOtherHrDetail();
            foreach ($hrDetail as $hr) {
                $offerData = [
                    'user_id'=>$hr->id,
                    'type' => 'Ticket',
                    'title' => 'You received an Ticket Comment.',
                    'text' => 'Ticket Comment',
                    'url' => route('hr-ticket-view',$ticket->ticket_id),
                ];
                Notification::send($hr, new OffersNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }
            $userDetail = $this->employeeRepository->getDetailById('1');
            $offerData = [
                'user_id'=>$userDetail->id,
                'type' => 'Ticket',
                'title' => 'You received an Ticket Comment.',
                'text' => 'Ticket Comment',
                'url' => route('ticket-show',$ticket->ticket_id),
            ];
            Notification::send($userDetail, new OffersNotification($offerData));
            $pusher->trigger('notifications', 'new-notification', $offerData);

            if ($ticketDetail->ticket_type == "System Repair") {
                $employeeList = $this->employeeRepository->getAllSystemEngineer();
                foreach ($employeeList as $key => $employee) {
                    $offerData = [
                        'user_id' => $employee->id,
                        'type' => 'Ticket',
                        'title' => 'You received an Ticket Comment.',
                        'text' => 'Ticket Comment',
                        'url' => route('engineer-ticket-view', $ticket->ticket_id),
                    ];
                    Notification::send($employee, new OffersNotification($offerData));
                    $pusher->trigger('notifications', 'new-notification', $offerData);
                }
            }
            return response()->json(['data' => $ticket, 'message' => '', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
}
