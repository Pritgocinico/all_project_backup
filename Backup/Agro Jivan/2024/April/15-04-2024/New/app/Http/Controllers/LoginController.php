<?php

namespace App\Http\Controllers;

use App\Helpers\UserLogHelper;
use Illuminate\Http\Request;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $employeeRepository, $roleRepository = "";
    public function __construct(EmployeeRepositoryInterface $employeeRepository, RoleRepositoryInterface $roleRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->roleRepository = $roleRepository;
    }
    public function index()
    {
        if (Auth()->user() !== null) {
            $id = Auth()->user()->role_id;

            if ($id == 2) {
                return redirect()->route('employee-dashboard');
            } else if ($id == 1) {
                return redirect()->route('dashboard');
            } else if ($id == '4') {
                return redirect()->route('confirm-dashboard');
            } else if ($id == '5') {
                return redirect()->route('driver-dashboard');
            } else if ($id == '6') {
                return redirect()->route('system-engineer-dashboard');
            } else if ($id == '7') {
                return redirect()->route('transport-department-dashboard');
            } else if ($id == '8') {
                return redirect()->route('warehouse-dashboard');
            } else if ($id == '9') {
                return redirect()->route('sales-manager-dashboard');
            } else if ($id == '10') {
                return redirect()->route('sales-service-dashboard');
            }
            return redirect()->route('hr-dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $username = $request->email;
        $password = $request->password;
        if (
            Auth::attempt(['phone_number' => $username, 'password' => $password, 'status' => 1]) ||
            Auth::attempt(['email' => $username, 'password' => $password, 'status' => 1])
        ) {
            $request->session()->regenerate();
            $id = Auth()->user()->role_id;
            UserLogHelper::storeLog("Login", ucfirst(Auth()->user()->name) . " Logged in Successfully");
            if ($id != 1) {
                $where = ['user_id' => Auth()->user()->id, 'attendance_date' => Carbon::now()->format('Y-m-d')];
                $attendance = Attendance::where($where)->first();
                if ($attendance !== null) {
                    if ($attendance->login_time == null) {
                        $data = [
                            'status' => '1',
                            'login_time' => Carbon::now(),
                        ];
                        $shift = Auth()->user()->shift_type;
                        $current_time = Carbon::now();
                        if ($shift == '1') {
                            $time_to_compare = Carbon::createFromTime(8, 45, 0);
                            if ($current_time->greaterThan($time_to_compare)) {
                                $data['status'] = '2';
                            }
                        }
                        Attendance::where($where)->update($data);
                    } else {
                        if ($attendance->after_hour_login == null) {
                            $current_time = Carbon::now();
                            $time_to_compare = Carbon::createFromTime(17, 30, 0);
                            if ($current_time->greaterThan($time_to_compare)) {
                                $data['after_hour_login'] = Carbon::now();
                                Attendance::where($where)->update($data);
                            }
                        }
                    }
                }
            }
            if ($id == 2) {
                return redirect()->route('employee-dashboard')
                    ->with('success', 'You have successfully logged in!');
            }
            if ($id == 1) {
                return redirect()->route('dashboard')
                    ->with('success', 'You have successfully logged in!');
            }
            if ($id == '4') {
                return redirect()->route('confirm-dashboard')
                    ->with('success', 'You have successfully logged in!');
            }
            if ($id == '5') {
                return redirect()->route('driver-dashboard')
                    ->with('success', 'You have successfully logged in!');
            }
            if ($id == '6') {
                return redirect()->route('system-engineer-dashboard')
                    ->with('success', 'You have successfully logged in!');
            }
            if ($id == '7') {
                return redirect()->route('transport-department-dashboard')
                    ->with('success', 'You have successfully logged in!');
            }
            if ($id == '8') {
                return redirect()->route('warehouse-dashboard')
                    ->with('success', 'You have successfully logged in!');
            }
            if ($id == '9') {
                return redirect()->route('sales-manager-dashboard')
                    ->with('success', 'You have successfully logged in!');
            }
            if ($id == '10') {
                return redirect()->route('sales-service-dashboard')
                    ->with('success', 'You have successfully logged in!');
            }
            return redirect()->route('hr-dashboard')
                ->with('success', 'You have successfully logged in!');
        }
        return back()->with('error', 'Your provided credentials do not match in our records.')->onlyInput('email');
    }
    public function logout()
    {
        $where = ['user_id' => Auth()->user()->id, 'attendance_date' => Carbon::now()->format('Y-m-d')];
        $attendance = Attendance::where($where)->first();
        if ($attendance->after_hour_login == null) {
            $data['logout_time'] = Carbon::now();
        } else {
            $data['after_hour_logout'] = Carbon::now();
        }
        Attendance::where($where)->update($data);
        Auth::logout();
        return redirect('/login')->with('success', 'User Logout Successfully');
    }

    public function edit()
    {
        $roleList = $this->roleRepository->getAllRole();
        $page = "Edit Profile";
        return view('admin.profile.index', compact('roleList', 'page'));
    }
    public function userEdit()
    {
        $roleList = $this->roleRepository->getAllRole();
        $page = "Edit Profile";
        return view('employee.profile.index', compact('roleList', 'page'));
    }

    public function updateProfile(Request $request)
    {
        $id = Auth()->user()->id;
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'status' => $request->status == "on" ? '1' : '0',
            'role_id' => $request->role,
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
        if ($request->hasfile('profile_image')) {
            $file = $request->file('profile_image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/profile', $filename);
            $data['profile_image'] = 'profile/' . $filename;
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
        $where = ['id' => $id];
        $update = $this->employeeRepository->update($data, $where);
        if ($update) {
            $whereRole = ['user_id' => $id];
            $role = ['role_id' => $request->role];
            $roleList = $this->roleRepository->update($role, $whereRole);
            $redirect = "employee/employee-dashboard";
            if (Auth()->user()->role_id == 1) {
                $redirect = "admin/dashboard";
            }
            return redirect($redirect)->with('success', 'Employee Updated Successfully.');
        }
        return redirect('edit-profile')->with('error', 'Something Went To Wrong.');
    }

    // public function expireOldLeaves()
    // {
    //     $previousLeaveList = $this->leaveRepository->getPreviousLeaveData();


    // }
}
