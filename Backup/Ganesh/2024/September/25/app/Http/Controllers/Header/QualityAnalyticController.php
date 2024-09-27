<?php

namespace App\Http\Controllers\Header;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SmsHelper;
use App\Models\Log;
use App\Models\RoleUser;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class QualityAnalyticController extends Controller
{
    public function __construct()
    {
        $page = "Quality Analytic";
        $setting = Setting::first();
        $viewData = [
            'page' => $page,
            'setting' => $setting,
        ];
        view()->share($viewData);
    }
    public function index()
    {
        $qualityUser = User::where('role', 10)->latest()->get();
        return view('admin.quality_analytic.index', compact('qualityUser'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users,phone,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:8',
        ]);
        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->mobile;
        $user->original_password    = $request->password;
        $user->password = Hash::make($request->password);
        $user->role     = 10;
        $user->status   = 1;
        $insert = $user->save();
        if ($insert) {
            $role           = new RoleUser();
            $role->user_id  = $user->id;
            $role->role_id  = 10;
            $ins = $role->save();
            $roleData = Role::firstWhere('id', 10);
            $setting = Setting::first();
            try {
                $mobileNumber = $request->phone;
                $password = $request->password;
                $message = "Your user id of " . $roleData->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $user->phone . " , Password: " . $password;
                $templateid = '1407171593745579639';
                if($setting->wa_message_sent == 1){
                    $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, true);
                } else {
                    $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                }
            } catch (Exception $e) {
                echo 'Message: ' . $e->getMessage();
            }

            return redirect()->route('header.fitting.create')->with('success', 'Fitting added successfully');
        }
        return redirect()->route('header.fitting.create')->with('error', 'Something Went To Wrong.');
    }
    public function edit(Request $request){
        $id = $request->id;
        $qualityUser = User::find($id);
        return response()->json($qualityUser);
    }

    public function update(Request $request){
        $id = $request->customer_id;
        $qualityUser = User::find($id);
        $qualityUser->name = $request->name;
        $qualityUser->email = $request->email;
        $qualityUser->phone = $request->mobile;
        if($request->password !== null){
            $qualityUser->password = Hash::make($request->password);
        }
        $update = $qualityUser->save();
        if($update){
            $log = new Log();
            $log->user_id   = $qualityUser->name;
            $log->module    = 'Quality User';
            $log->log       = 'Quality User Updated.';
            return response()->json("Quality User updated successfully", 200);
        }
        return response()->json("Something Went To Wrong", 500);
    }
    public function destroy($id)
    {
        $quality = User::find($id);
        if($quality){
            $log = new Log();
            $log->user_id   = $quality->name;
            $log->module    = 'Quality User';
            $log->log       = 'Quality User Deleted.';
            $log->save();
            $quality->delete();
            return response()->json("success", 200);
        } 
        return response()->json('User Not Found.',500);
    }
}
