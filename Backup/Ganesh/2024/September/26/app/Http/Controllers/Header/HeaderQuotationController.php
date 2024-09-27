<?php

namespace App\Http\Controllers\Header;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\HeaderQuotation;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Role;
use Hash;
use App\Http\Helpers\SmsHelper;

class HeaderQuotationController extends Controller
{

    public function __construct() {
        $setting=Setting::first();
        // $user = User::first();
        view()->share('setting', $setting);
    }

    public function create()
    {
        $measurements = User::where('role',4)->get();
        return view('admin.headerquatation.quatation', compact('measurements'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users,phone,NULL,id,deleted_at,NULL',
            // 'email' => 'email',
            'password' => 'required|string|min:8',
        ]);

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->mobile;
        $user->original_password = $request->password;
        $user->password = Hash::make($request->password);
        $user->role     = 4;
        $user->status   = 1;
        $user->save();

        $role           = new RoleUser;
        $role->user_id  = $user->id;
        $role->role_id  = 4;
        $ins = $role->save();
        $roleData = Role::firstWhere('id',4);
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
        // Create a new quotation record
        // HeaderQuotation::create([
        //     'name' => $validatedData['name'],
        //     'mobile' => $validatedData['mobile'],
        //     'email' => $request->email,
        //     'password' => bcrypt($validatedData['password']),
        // ]);

        // You can do additional actions here if needed

        return redirect()->route('quotation.create')->with('success', 'Quotation added successfully');
    }
    public function edit($id)
    {
        $measurement = User::findOrFail($id);
        return view('admin.headerquatation.edit', compact('measurement'));
    }

    public function update(Request $request, $id)
    {
        // print_r($request->all());
        // exit;
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            // 'email' => 'email',
            // 'password' => 'nullable|string|min:8',
        ]);

        $user = User::where('id',$id)->first();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->mobile;
        $user->original_password = $request->has(   'password') ? $request->password : $user->original_password;
        $user->password = $request->has('password') ? Hash::make($request->password) : $user->password;
        $user->save();
        $roleData = Role::firstWhere('id',4);
        $setting = Setting::first();
        try {
            $mobileNumber = $user->phone;
            $password = $user->original_password;
            $message = "Your user id of " . $roleData->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $mobileNumber . " , Password: " . $password;
            $templateid = '1407171593745579639';
            if($setting->wa_message_sent == 1){
                $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, true);
            } else {
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        // $quotation = HeaderQuotation::findOrFail($id);

        // $quotation->update([
        //     'name' => $validatedData['name'],
        //     'mobile' => $validatedData['mobile'],
        //     'email' => $request->email,
        //     'password' => $request->has('password') ? Hash::make($request->password) : $quotation->password,
        // ]);
        return response()->json("Quotation updated successfully", 200);
    }
    public function deleteQuotationUser($id)
    {
        $quotation = User::find($id);
        $quotation->delete();
        return response()->json("success", 200);
    }
    public function getQuotation(Request $request){
        $quotation = User::where('id',$request->id)->first();
        return response()->json($quotation, 200);
    }
}
