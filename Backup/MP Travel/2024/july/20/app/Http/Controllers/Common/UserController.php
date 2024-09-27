<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\Country;
use App\Models\Department;
use App\Models\Log;
use App\Models\Role;
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
        $this->users = resolve(User::class)->with('stateDetail', 'countryDetail', 'cityDetail', 'roleDetail','designationDetail','departmentDetail');
    }
    public function index()
    {
        $userList = $this->users->where('role_id', "!=", 1)->paginate(10);
        return view('admin.user.index', compact('userList'));
    }
    public function create(Request $request)
    {
        $countryList = Country::get();
        $departmentList = Department::all();
        $roleList = Role::get();
        return view('admin.user.create', compact('countryList', 'roleList','departmentList'));
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
        $user->zip_code = $request->zip_code;
        $user->created_by = Auth()->user()->id;
        $user->designation_id = $request->designation;
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
        $insert = $user->save();
        if ($insert) {
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'User',
                'description' => auth()->user()->name . " Created a User named '" . $request->role_name . "'"
            ]);
            return redirect()->route('user.index')->with('success', 'Employee has been created successfully.');
        }
        return redirect()->route('user.create')->with('success', 'Something went wrong.');
    }

    public function edit($id)
    {
        $user = $this->users->find($id);
        $departmentList = Department::all();
        $countryList = Country::get();
        $roleList = Role::get();
        return view('admin.user.create', compact('user', 'countryList', 'roleList','departmentList'));
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
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'User',
                'description' => auth()->user()->name . " Updated a User named '" . $request->name . "'"
            ]);
            return redirect()->route('user.index')->with('success', 'Employee has been updated successfully.');
        }
        return redirect()->route('user.create')->with('success', 'Something went wrong.');
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

    public function show($id){

        $user = $this->users->find($id);
        return view('admin.user.show',compact('user'));
    }
}
