<?php

namespace App\Http\Controllers\Header;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\HeaderWorkshop;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Hash;
use App\Http\Helpers\SmsHelper;

class HeaderWorkshopController extends Controller
{

    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }

    public function create()
    {
        $workshops = User::where('role',5)->get(); // Fetch all workshops, you might need to adjust this based on your requirements
        return view('admin.headerworkshop.workshop', ['workshops' => $workshops]);
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
        $user->role     = 5;
        $user->status   = 1;
        $user->save();

        $role           = new RoleUser;
        $role->user_id  = $user->id;
        $role->role_id  = 5;
        $ins = $role->save();
        $roleData = Role::firstWhere('id',5);
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
        // HeaderWorkshop::create([
        //     'name' => $validatedData['name'],
        //     'mobile' => $validatedData['mobile'],
        //     'email' => $request->email,
        //     'password' => bcrypt($validatedData['password']),
        // ]);

        return redirect()->route('header.workshop.create')->with('success', 'Workshop added successfully');
    }

    public function edit($id)
    {
        $workshop = User::find($id);
        return view('workshop.edit', compact('workshop'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            // 'email' => 'email',
            // 'password' => 'required|string|min:8',
        ]);

        $user = User::where('id',$id)->first();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->mobile;
        $user->original_password = $request->has('password') ? $request->password : $user->original_password;
        $user->password = $request->has('password') ? Hash::make($request->password) : $user->password;
        $user->save();
        $roleData = Role::firstWhere('id',5);
        $setting = Setting::first();
        try {
            $mobileNumber = $request->mobile;
            $password = $user->original_password;
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
        // $workshop = HeaderWorkshop::find($id);
        // $workshop->update([
        //     'name' => $validatedData['name'],
        //     'mobile' => $validatedData['mobile'],
        //     'email' => $request->email,
        //     'password' => $request->has('password') ? Hash::make($request->password) : $measurement->password,
        // ]);
        return response()->json("Workshop updated successfully", 200);
    }
    public function deleteWorkshopUser($id)
    {
        $workshop = User::find($id);
        $workshop->delete();
        return response()->json("success", 200);
    }
    public function getWorkshopUser(Request $request){
        $workshop = User::where('id',$request->id)->first();
        return response()->json($workshop, 200);
    }
}
