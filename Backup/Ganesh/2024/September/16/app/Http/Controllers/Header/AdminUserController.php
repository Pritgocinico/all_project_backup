<?php

namespace App\Http\Controllers\Header;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Role;
use Hash;
use App\Http\Helpers\SmsHelper;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        // $user = User::first();
        view()->share('setting', $setting);
    }
    public function showAddAdminForm()
    {
        $admins = User::where('role', 1)->whereNot('id', 1)->get();
        return view('admin.headeradmin.admin', compact('admins'));
    }

    public function addAdmin(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users,phone,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:8',
        ]);

        // $admin = UserAdmin::create([
        //     'name' => $validatedData['name'],
        //     'mobile' => $validatedData['mobile'],
        //     'email' => $request->email,
        //     'password' => bcrypt($validatedData['password']),
        // ]);

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->mobile;
        $user->original_password    = $request->password;
        $user->password = Hash::make($request->password);
        $user->role     = 1;
        $user->status     = 1;
        $user->save();

        $role           = new RoleUser;
        $role->user_id  = $user->id;
        $role->role_id  = 1;
        $ins = $role->save();
        $role = Role::where('id', 1)->first();
        try {
            $mobileNumber = $request->phone;
            $password = $request->password;
            $message = "Your user id of " . $role->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $user->phone . " , Password: " . $password;
            $templateid = '1407171593745579639';
            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        return redirect()->route('admin.add.admin')->with('success', 'Admin added successfully');
    }
    public function showEditAdminForm($id)
    {
        $admin = User::findOrFail($id);
        return view('admin.headeradmin.admin', compact('admin'));
    }
    public function updateAdmin(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'phone' => [
                'required',
                function ($attribute, $value, $fail) use ($id) {
                    $existsInUsers = User::where('phone', $value)->where('id', '<>', $id)->exists();
                    $existsInCustomers = Customer::where('phone', $value)->where('id', '<>', $id)->exists();

                    if ($existsInUsers || $existsInCustomers) {
                        $fail('The ' . $attribute . ' must be unique across both users and customers.');
                    }
                },
            ],
        ]);

        // $admin = UserAdmin::findOrFail($id);

        // $admin->update([
        //     'name' => $validatedData['name'],
        //     'mobile' => $validatedData['mobile'],
        //     'email' => $request->email,
        //     'password' => $request->has('password') ? Hash::make($request->password) : $admin->password,
        // ]);

        $user = User::where('id', $id)->first();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->mobile;
        $user->original_password = $request->has('password') ? $request->password : $user->original_password;
        $user->password = $request->has('password') ? Hash::make($request->password) : $admin->password;
        $user->role     = 1;
        $user->status =  $request->status == "on" ? '1' : '0';
        $user->save();
        $role = Role::where('id', 1)->first();
        try {
            $mobileNumber = $user->phone;
            $password = $request->original_password;
            $message = "Your user id of " . $role->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $user->phone . " , Password: " . $password;
            $templateid = '1407171593745579639';
            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        return response()->json("Admin updated successfully", 200);
    }
    public function deleteAdmin($id)
    {
        $admin = User::find($id);
        $admin->delete();
        return response()->json("success", 200);
    }
    public function getAdmin(Request $request)
    {
        $admin = User::where('id', $request->id)->first();
        return response()->json($admin, 200);
    }
}
