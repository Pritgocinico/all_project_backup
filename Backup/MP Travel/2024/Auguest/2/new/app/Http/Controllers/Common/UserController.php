<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Helpers\UtilityHelper;
use App\Models\Attendance;
use App\Models\BreakLog;
use App\Models\Country;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeDeduction;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Log;
use App\Models\Role;
use App\Models\ShiftTime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Nnjeim\World\World;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $users;

    public function __construct()
    {
        $this->middleware('auth');
        $page = "User";
        view()->share('page', $page);
        $this->users = resolve(User::class)->with('stateDetail', 'countryDetail', 'cityDetail', 'roleDetail', 'designationDetail', 'departmentDetail', 'shiftDetail');
    }
    public function index(Request $request)
    {
        $shift = $request->shift;
        $userList = $this->users->where('role_id', "!=", 1)->when($shift, function ($query) use ($shift) {
            $query->where('shift_id', $shift);
        })->latest()->paginate(10);
        return view('admin.user.index', compact('userList'));
    }
    public function create(Request $request)
    {
        $countryList = Country::get();
        $departmentList = Department::all();
        $roleList = Role::get();
        $shiftList = ShiftTime::all();
        return view('admin.user.create', compact('countryList', 'roleList', 'departmentList', 'shiftList'));
    }

    public function getStateByCountry(Request $request)
    {
        $code = $request->country_code;
        $state = World::states([
            'filters' => [
                'country_code' => $code,
            ],
        ]);
        return response()->json($state);
    }

    public function getCityByState(Request $request)
    {
        $country = $request->country_code;
        $state = $request->state;
        $city = World::cities([
            'filters' => [
                'country_code' => $country,
                'state_id' => $state,
            ],
        ]);
        return response()->json($city);
    }

    public function store(CreateUserRequest $request)
    {
        $departmentDetail = Department::where('id', $request->department)->first();
        $words = explode(' ', $departmentDetail->name);
        $result = '';
        for ($i = 1; $i < count($words); $i++) {
            $result .= strtoupper($words[$i][0]);
        }
        $lastId =  User::all()->last() ? User::all()->last()->id + 1 : 1;
        $userCode = 'MP-' . $result . '-' . substr("00000{$lastId}", -6);
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->role_id  = $request->role;
        $user->password = Hash::make($request->password);
        $user->original_password = $request->password;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->country = $request->country;
        $user->address = $request->address;
        $user->shift_id = $request->shift;
        $user->zip_code = $request->zip_code;
        $user->created_by = Auth()->user()->id;
        $user->designation_id = $request->designation;
        $user->department_id = $request->department;
        $user->user_code = $userCode;
        $user->employee_salary = $request->employee_salary;
        if ($request->hasFile('aadhar_card')) {
            $file = $request->file('aadhar_card');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $aadharLogo = $file->storeAs('aadhar', $newFilename, 'public');
            $user->aadhar_card = $aadharLogo;
        }
        if ($request->hasFile('pan_card')) {
            $file = $request->file('pan_card');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $panLogo = $file->storeAs('pan', $newFilename, 'public');
            $user->pan_card = $panLogo;
        }
        if ($request->hasFile('user_agreement')) {
            $file = $request->file('user_agreement');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $agreementLogo = $file->storeAs('agreement', $newFilename, 'public');
            $user->user_agreement = $agreementLogo;
        }
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $pathLogo = $file->storeAs('profile', $newFilename, 'public');
            $user->profile_image = $pathLogo;
        }
        $insert = $user->save();
        if ($insert) {
            if (isset($request->deduction_type)) {
                foreach ($request->deduction_type as $key => $deduct) {
                    $deduction = new EmployeeDeduction();
                    $deduction->user_id = $user->id;
                    $deduction->deduction_type = $deduct;
                    $deduction->amount = $request->amount[$key];
                    $deduction->save();
                }
            }
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'User',
                'description' => auth()->user()->name . " Created a User named '" . $request->role_name . "'"
            ]);
            return redirect()->route('user.index')->with('success', 'Employee has been created successfully.');
        }
        return redirect()->route('user.create')->with('error', 'Something went wrong.');
    }

    public function edit($id)
    {
        $user = $this->users->find($id);
        $departmentList = Department::all();
        $countryList = Country::get();
        $roleList = Role::get();
        $shiftList = ShiftTime::all();
        $deductionDetail = EmployeeDeduction::where('user_id', $id)->get();
        return view('admin.user.create', compact('shiftList', 'user', 'countryList', 'roleList', 'departmentList', 'deductionDetail'));
    }

    public function update(CreateUserRequest $request, $id)
    {

        $user = $this->users->find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->role_id  = $request->role;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->country = $request->country;
        $user->address = $request->address;
        $user->zip_code = $request->zip_code;
        $user->updated_by = Auth()->user()->id;
        $user->designation_id = $request->designation;
        $user->shift_id = $request->shift;
        $user->department_id = $request->department;
        $user->employee_salary = $request->employee_salary;
        if ($request->hasFile('aadhar_card')) {
            $file = $request->file('aadhar_card');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $aadharLogo = $file->storeAs('aadhar', $newFilename, 'public');
            $user->aadhar_card = $aadharLogo;
        }
        if ($request->hasFile('pan_card')) {
            $file = $request->file('pan_card');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $panLogo = $file->storeAs('pan', $newFilename, 'public');
            $user->pan_card = $panLogo;
        }
        if ($request->hasFile('user_agreement')) {
            $file = $request->file('user_agreement');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $agreementLogo = $file->storeAs('agreement', $newFilename, 'public');
            $user->user_agreement = $agreementLogo;
        }
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $pathLogo = $file->storeAs('profile', $newFilename, 'public');
            $user->profile_image = $pathLogo;
        }
        if ($request->password !== null) {
            $user->password = Hash::make($request->password);
            $user->original_password = $request->password;
        }
        $update = $user->save();
        if ($update) {
            if (isset($request->deduction_type)) {
                foreach ($request->deduction_id as $key => $deductionId) {
                    $deduction = EmployeeDeduction::find($deductionId);
                    if ($deduction) {
                        $deduction->deduction_type = $request->deduction_type[$key];
                        $deduction->amount = $request->amount[$key];
                        $deduction->status = $request->deduction_status[$key] == "on" ? 1 : 0;
                        $deduction->save();
                    } else {
                        if (isset($request->deduction_type[$key])) {

                            $deductionDetail = new EmployeeDeduction();
                            $deductionDetail->user_id = $id;
                            $deductionDetail->deduction_type = $request->deduction_type[$key];
                            $deductionDetail->amount = $request->amount[$key];
                            $deductionDetail->status = isset($request->deduction_status[$key]) && $request->deduction_status[$key] == "on" ? 1 : 0;
                            $deductionDetail->save();
                        }
                    }
                }
            }
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'User',
                'description' => auth()->user()->name . " Updated a User named '" . $request->name . "'"
            ]);
            return redirect()->route('user.index')->with('success', 'Employee has been updated successfully.');
        }
        return redirect()->route('user.create')->with('error', 'Something went wrong.');
    }

    public function destroy($id)
    {
        $user = $this->users->find($id);
        $delete = $user->delete();
        if ($delete) {
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'User',
                'description' => auth()->user()->name . " Deleted a User named '" . $user->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Employee has been deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }

    public function show($id)
    {

        $user = $this->users->find($id);
        return view('admin.user.show', compact('user'));
    }

    public function calendar(Request $request, $id)
    {
        if ($request->ajax()) {
            $holidays = Holiday::get()
                ->map(function ($item) {
                    return [
                        'title' => $item->holiday_name,
                        'start' => UtilityHelper::convertYmd($item->holiday_date),
                        'end' => UtilityHelper::convertYmd($item->holiday_date),
                        'color' => '#d0995d',
                    ];
                })->toArray();
            $attendances = Attendance::where('user_id', $id)
                ->get()
                ->map(function ($item) {
                    $title = "Absent";
                    $color = "red";
                    if ($item->status == 1) {
                        $title = 'Present';
                        $color = "green";
                    }
                    if ($item->status == 2) {
                        $title = 'Half Day';
                        $color = "gray";
                    }
                    return [
                        'title' => ($title),
                        'start' => UtilityHelper::convertYmd($item->created_at),
                        'end' => UtilityHelper::convertYmd($item->created_at),
                        'color' => $color,
                    ];
                })->toArray();
            $leave = Leave::where('user_id', $id)->get()
                ->map(function ($item) {
                    return [
                        'title' => $item->leave_type,
                        'start' => UtilityHelper::convertYmd($item->leave_from),
                        'end' => UtilityHelper::convertYmd($item->leave_to),
                        'color' => $item->leave_status == 2 ? 'green' : 'red',
                    ];
                })->toArray();
            $data = [...$holidays, ...$attendances, ...$leave];
            return response()->json($data);
        }
    }
    public function attendanceByDate(Request $req)
    {
        $user = $req->id;
        $date = $req->date;
        $attendance = Attendance::with('breakLogDetail')->where('user_id', $user)->whereDate('attendance_date', $date)->first();
        return $attendance;
    }
    public function breakTime(Request $request)
    {
        $id = $request->id;
        $date = $request->date;
        $diff_in_days = [];
        $breaks = BreakLog::where('user_id', $id)->orderBy('id', 'DESC')->where('date', $date)->get();
        if (!empty($breaks)) {
            foreach ($breaks as $br) {
                $to = new \Carbon\Carbon($br->break_start);
                $from = new \Carbon\Carbon($br->break_over);
                $diff_in_days[] = $to->diff($from)->format('%H:%I:%S');
            }
        }
        return UtilityHelper::sumTime($diff_in_days);
    }
    public function logDetail(Request $request)
    {
        $user = $request->id;
        $date = $request->date;
        $log = Log::where('module', 'login')->where('user_id', $user)->whereDate('created_at', $date)->get();
        return $log;
    }
}
