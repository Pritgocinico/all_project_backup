<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Helpers\UtilityHelper;
use App\Interfaces\AttendanceRepositoryInterface;
use App\Interfaces\DepartmentRepositoryInterface;
use App\Interfaces\HolidayRepositoryInterface;
use App\Interfaces\LeaveRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\UserPermissionRepositoryInterface;
use App\Models\Attendance;
use Carbon\Carbon;
use PDF;
use \Mpdf\Mpdf as mPDF;

class EmployeeController extends Controller
{
    protected $roleRepository, $employeeRepository, $departmentRepository, $userPermissionRepository, $orderRepository, $holidayRepository, $leaveRepository, $attendanceRepository = "";
    public function __construct(RoleRepositoryInterface $roleRepository, EmployeeRepositoryInterface $employeeRepository, DepartmentRepositoryInterface $departmentRepository, UserPermissionRepositoryInterface $userPermissionRepository, OrderRepositoryInterface $orderRepository, HolidayRepositoryInterface $holidayRepository, LeaveRepositoryInterface $leaveRepository, AttendanceRepositoryInterface $attendanceRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->employeeRepository = $employeeRepository;
        $this->departmentRepository = $departmentRepository;
        $this->userPermissionRepository = $userPermissionRepository;
        $this->orderRepository = $orderRepository;
        $this->holidayRepository = $holidayRepository;
        $this->leaveRepository = $leaveRepository;
        $this->attendanceRepository = $attendanceRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleList = $this->roleRepository->getAllRole();
        $employeeList = $this->employeeRepository->getAllData('', '', '', 'export');
        $absent = Attendance::where('attendance_date', Carbon::now()->format('Y-m-d'))->where('status', '0')->count();
        $present = Attendance::where('attendance_date', Carbon::now()->format('Y-m-d'))->where('status', '1')->count();
        $HalfDay = Attendance::where('attendance_date', Carbon::now()->format('Y-m-d'))->where('status', '2')->count();
        $data = [
            'labels' => ['Absent', 'Present','Half Day'],
            'data' => [$absent, $present,$HalfDay],
        ];
        $page = "Employee List";
        return view('admin.employee.index', compact('roleList', 'employeeList', 'data', 'page'));
    }

    public function ajaxList(Request $request)
    {
        $status = $request->status;
        $role = $request->role;
        $search = $request->search;
        $employeeList = $this->employeeRepository->getAllData($status, $role, $search, 'paginate');
        return view('admin.employee.ajax_list', compact('employeeList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roleList = $this->roleRepository->getAllRole();
        $departmentList = $this->departmentRepository->getAllDepartmentWithPaginate();
        $systemCode = $this->employeeRepository->getNewSystemcode();
        $page = "Create Employee";
        return view('admin.employee.create', compact('roleList', 'departmentList', 'systemCode', 'page'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request)
    {
        $count = $this->employeeRepository->employeeCount();
        $id = 0;
        if ($count > 0) {
            $id = $this->employeeRepository->getLastInsertId();
        }
        $id = $id + 1;
        $str_length = 5;
        $str = substr("00000{$id}", -$str_length);
        $emp_code = 'AGR-EMP-' . $str;
        $data = [
            'employee_code' => $emp_code,
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
                    'feature'       =>  'employee',
                    'capability'    =>  'vip-customer-list',
                    'value'         =>  '1'
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
            return redirect('admin/employees')->with('success', 'Employee Created Successfully.');
        }
        return redirect('admin/employees.create')->with('error', 'Something Went To Wrong.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = $this->employeeRepository->getDetailById($id);
        $permissionList = $this->userPermissionRepository->getAllUserPermission($id);
        $todayOrder = $this->orderRepository->getUserOrderData($id, 'daily');
        $allOrder = $this->orderRepository->getUserOrderData($id, '');
        $page = "Employee View";
        return view('admin.employee.show', compact('employee', 'permissionList', 'todayOrder', 'allOrder', 'page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = $this->employeeRepository->getDetailById($id);
        $roleList = $this->roleRepository->getAllRole();
        $departmentList = $this->departmentRepository->getAllDepartmentWithPaginate();
        $data = [];
        foreach ($employee->departmentDetail as $department) {
            array_push($data, $department->department_id);
        }
        $page = "Employee Edit";
        $employee['department'] = $data;
        return view('admin.employee.edit', compact('roleList', 'employee', 'departmentList', 'page'));
    }

    /**
     * Update the specified resource in storage.
     */
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
            return redirect('admin/employees')->with('success', 'Employee Updated Successfully.');
        }
        return redirect('admin/employees/' . $id . '/edit')->with('error', 'Something Went To Wrong.');
    }

    /**
     * Remove the specified resource from storage.
     */
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
            $pdf = PDF::loadView('admin.pdf.employee', ['employeeList' => $employeeList]);
            return $pdf->download('Employee.pdf');
        }
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
        return redirect('admin/employees/' . $id)->with('success', 'User Permission Updated Successfully.');
    }

    public function calendar(Request $request, $id)
    {
        if ($request->ajax()) {
            $holidays = $this->holidayRepository->getAllHoliday();
            $attendances = $this->holidayRepository->getAllAttendance($id);
            $leave = $this->leaveRepository->getAllDataCalendar($id);
            $data = [...$holidays, ...$attendances, ...$leave];
            return response()->json($data);
        }
    }
    public function breakTime(Request $request)
    {
        $id = $request->id;
        $date = $request->date;
        $diff_in_days = [];
        $breaks = $this->attendanceRepository->getUserBreak($id, $date);
        if (!empty($breaks)) {
            foreach ($breaks as $br) {
                $to = new \Carbon\Carbon($br->break_start);
                $from = new \Carbon\Carbon($br->break_over);
                $diff_in_days[] = $to->diff($from)->format('%H:%I:%S');
            }
        }
        return UtilityHelper::sumTime($diff_in_days);
    }

    public function vipCustomerList()
    {
        $page = "VIP Customer List";
        return view('admin.vip_customer.index', compact('page'));
    }

    public function vipCustomerAjaxList(Request $request)
    {
        $search = $request->search;
        $customerList = $this->orderRepository->getVipCustomerList($search, 'paginate');
        return view('admin.vip_customer.ajax_list', compact('customerList'));
    }

    public function offerLetter(Request $request)
    {
        $offerText = $request->offer_text;
        $employee = $this->employeeRepository->getDetailById($request->id);
        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P'
        ]);
        if (isset($employee)) {
            $footer = '<p style="margin-top: 20px; margin-bottom: 0;">Bharat Patel<br> <strong>Director - AgroJivan</strong></p>
            <img src="'.asset('public/assets/media/svg/AgroJivan_Green_Bg.svg').'" alt="AgroJivan Logo" style="max-width: 178px; margin-top: 0px;">
        <footer width="100%" style="font-size: 15px;">
                AgroJivan | 615, Shivalik Satyamev, Near Vakil Saheb
                Bridge,<br> Bopal, Daskroi, Ahmedabad- 380058, Gujarat. | +91 8141904714
          </footer>';
            $pdf->SetHTMLFooter($footer);
            $viewFile = view('admin.pdf.offer_letter', compact('employee', 'offerText'))->render();
            $pdf->WriteHTML($viewFile);
        } else {
            $pdf->WriteHTML('<h5 style="text-align: center;">No Order Available.</h5>');
        }
        return $pdf->Output('Offer Letter.pdf', "D");
    }
    
    public function vipCustomerExport(Request $request){
        $search = $request->search;
        $customerList = $this->orderRepository->getVipCustomerList($search, 'export');
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Vip Customer.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Customer Name', 'Phone Number', 'State', 'District', 'Sub District', 'Village','Total Orders');
            $callback = function () use ($customerList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($customerList as $customer) {
                    $state = isset($customer->stateDetail) ? $customer->stateDetail->name : "";
                    $district = isset($customer->districtDetail) ? $customer->districtDetail->district_name : "";
                    $subDistrict = isset($customer->subDistrictDetail) ? $customer->subDistrictDetail->sub_district_name : "";
                    $village = isset($customer->villageDetail) ? $customer->villageDetail->village_name : "";
                    fputcsv($file, array($customer->customer_name, $customer->phoneno,$state, $district, $subDistrict,$village,$customer->total));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.vip_customer', ['customerList' => $customerList]);
            return $pdf->download('Vip Customer.pdf');
        }
    }
    public function attendanceDateAjax(Request $request){
        $page = $request->page;
        $dateList = UtilityHelper::getAllCurrentYearDate($page,'yes');
        return view('admin.employee.attendance_list',compact('dateList'));
    }
}
