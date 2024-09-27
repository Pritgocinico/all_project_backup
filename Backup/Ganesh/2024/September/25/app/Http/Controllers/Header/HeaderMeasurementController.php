<?php

namespace App\Http\Controllers\Header;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\HeaderMeasurement;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Role;
use Hash;
use App\Http\Helpers\SmsHelper;

class HeaderMeasurementController extends Controller
{

    public function __construct()
    {
        $setting = Setting::first();
        // $user = User::first();
        view()->share('setting', $setting);
    }

    public function create()
    {
        $measurements = User::where('role', 3)->get();
        return view('admin.headermeasurement.measurement', compact('measurements'));
    }

    public function insertMeasurement(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users,phone,NULL,id,deleted_at,NULL',
            // 'email' => 'email',
            'password' => 'required|string|min:8',
        ]);

        // Create a new measurement record
        // HeaderMeasurement::create([
        //     'name' => $validatedData['name'],
        //     'mobile' => $validatedData['mobile'],
        //     'email' => $request->email,
        //     'password' => bcrypt($validatedData['password']),
        // ]);
        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->mobile;
        $user->original_password =$request->password;
        $user->password = Hash::make($request->password);
        $user->role     = 3;
        $user->status   = 1;
        $user->save();

        $role           = new RoleUser;
        $role->user_id  = $user->id;
        $role->role_id  = 3;
        $ins = $role->save();
        $role = Role::where('id', 3)->first();
        $setting = Setting::first();
        try {
            $mobileNumber = $request->phone;
            $password = $request->password;
            $message = "Your user id of " . $role->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $user->phone . " , Password: " . $password;
            $templateid = '1407171593745579639';
            if($setting->wa_message_sent == 1){
                $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, true);
            } else {
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        // You can do additional actions here if needed

        return redirect()->route('admin.add.measurement')->with('success', 'Measurement added successfully');
    }

    public function editMeasurement($id)
    {
        $measurement = User::findOrFail($id);
        return view('admin.headermeasurement.edit', compact('measurement'));
    }

    public function updateMeasurement(Request $request, $id)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            // 'email' => 'email',
            // 'password' => 'nullable|string|min:8',
        ]);

        // Update the measurement record
        // $measurement = HeaderMeasurement::findOrFail($id);
        $user = User::where('id', $id)->first();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->mobile;
        $user->original_password = $request->has('password') ? $request->password : $user->original_password;
        $user->password = $request->has('password') ? Hash::make($request->password) : $user->password;
        $user->save();
        // $measurement->update([
        //     'name' => $validatedData['name'],
        //     'mobile' => $validatedData['mobile'],
        //     'email' => $request->email,
        //     'password' => $request->has('password') ? Hash::make($request->password) : $measurement->password,
        // ]);
        $setting = Setting::first();
        try {
            $role = Role::where('id', 3)->first();
            $mobileNumber = $request->mobile;
            $password = $user->original_password;
            $message = "Your user id of " . $role->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $user->phone . " , Password: " . $password;
            $templateid = '1407171593745579639';
            if($setting->wa_message_sent == 1){
                $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, true);
            } else {
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        return response()->json("Measurement updated successfully", 200);
    }
    public function deleteMeasurementUser($id)
    {
        $measurement = User::find($id);
        $measurement->delete();
        return response()->json("success", 200);
    }
    public function getMeasurement(Request $request)
    {
        $measurement = User::where('id', $request->id)->first();
        return response()->json($measurement, 200);
    }
}
