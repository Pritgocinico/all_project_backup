<?php

namespace App\Http\Controllers\Header;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\HeaderFitting;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Customer;
use App\Models\Role;
use Hash;
use App\Http\Helpers\SmsHelper;

class HeaderFittingController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        // $user = User::first();
        view()->share('setting', $setting);
    }

    public function create()
    {
        $fittings = User::where('role',6)->get();
        return view('admin.headerfitting.fitting', ['fittings' => $fittings]);
    }


    public function store(Request $request)
    {
        // Validate and store data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users,phone,NULL,id,deleted_at,NULL',
            // 'email' => 'nullable|email|max:255',
            'password' => 'required|string|min:8',
            'phone' => [
                'required',
                function ($attribute, $value, $fail){
                    $existsInUsers = User::where('phone', $value)->exists();
    
                    if ($existsInUsers || $existsInCustomers) {
                        $fail('The ' . $attribute . ' must be unique across both users and customers.');
                    }
                },
            ],
        ]);

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->mobile;
        $user->original_password    = $request->password;
        $user->password = Hash::make($request->password);
        $user->role     = 6;
        $user->status   = 1;
        $user->save();

        $role           = new RoleUser;
        $role->user_id  = $user->id;
        $role->role_id  = 6;
        $ins = $role->save();
        $roleData = Role::firstWhere('id',6);
        try {
            $mobileNumber = $request->phone;
            $password = $request->password;
            $message = "Your user id of " . $roleData->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $user->phone . " , Password: " . $password;
            $templateid = '1407171593745579639';
            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        // HeaderFitting::create([
        //     'name' => $validatedData['name'],
        //     'mobile' => $validatedData['mobile'],
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        return redirect()->route('header.fitting.create')->with('success', 'Fitting added successfully');
    }

    public function edit($id)
    {
        $fitting = HeaderFitting::find($id);
        return view('admin.headerfitting.edit', ['fitting' => $fitting]);
    }

    public function update(Request $request, $id)
    {
        // Validate and update data
        $validatedData = $request->validate([
            'name'      => 'required|string|max:255',
            'mobile' => [
                'required',
                function ($attribute, $value, $fail) use($id){
                    $existsInUsers = User::where('phone', $value)->where('id','<>',$id)->exists();
    
                    if ($existsInUsers) {
                        $fail('The ' . $attribute . ' must be unique across both users.');
                    }
                },
            ],
            // 'email' => 'nullable|email|max:255',
            // 'password' => 'nullable|string|min:8', // Allow password to be nullable
        ]);

        $user = User::where('id',$id)->first();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->mobile;
        $user->original_password = $request->has('password') ? $request->password : $user->original_password;
        $user->password = $request->has('password') ? Hash::make($request->password) : $user->password;
        $user->save();
        $roleData = Role::firstWhere('id',6);
        try {
            $mobileNumber = $user->phone;
            $password = $user->original_password;
            $message = "Your user id of " . $roleData->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $user->phone . " , Password: " . $password;
            $templateid = '1407171593745579639';
            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        // $fitting = HeaderFitting::find($id);
        // $fitting->update([
        //     'name'      => $validatedData['name'],
        //     'mobile'    => $validatedData['mobile'],
        //     'email'     => $request->email,
        //     'password'  => $request->has('password') ? Hash::make($request->password) : $measurement->password,
        // ]);
        return response()->json("Fitting updated successfully", 200);
    }

    public function deleteFittingUser($id)
    {
        $fitting = User::find($id);
        $fitting->delete();
        return response()->json("success", 200);
    }
    public function getFittingUser(Request $request){
        $fitting = User::where('id',$request->id)->first();
        return response()->json($fitting, 200);
    }
}
