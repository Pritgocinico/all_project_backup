<?php

namespace App\Http\Controllers\header;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Role;
use Hash;
use App\Http\Helpers\SmsHelper;

class PurchaseController extends Controller
{
    protected $world;
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }

    public function create(Request $request){
        $purchaseUser = User::where('role',8)->get();
        return view('admin.headerpurchase.purchase', compact('purchaseUser'));
    }

    public function storePurchaseUser(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users,phone,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:8',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->mobile;
        $user->original_password = $request->password;
        $user->password = Hash::make($request->password);
        $user->role     = 8;
        $user->status   = 1;
        $user->save();

        $role           = new RoleUser;
        $role->user_id  = $user->id;
        $role->role_id  = 8;
        $role->save();

        $roleData = Role::firstWhere('id',8);
        try {
            $mobileNumber = $request->phone;
            $password = $request->password;
            $message = "Your user id of " . $roleData->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $user->phone . " , Password: " . $password;
            $templateid = '1407171593745579639';
            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        return redirect()->route('header.purchase.create')->with('success', 'Purchase added successfully');
    }

    public function edit(Request $request){
        $purchase = User::where('id',$request->id)->first();
        return response()->json($purchase, 200);
    }

    public function update(Request $request, $id){
        $validatedData = $request->validate([
            'name'      => 'required|string|max:255',
            'mobile'    => 'required|string|max:20',
        ]);
        
        $user = User::where('id',$id)->first();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->mobile;
        if($request->password !== null){
            $user->original_password    = $request->password;
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $roleData = Role::firstWhere('id',8);
        try {
            $mobileNumber = $user->phone;
            $password = $user->original_password;
            $message = "Your user id of " . $roleData->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $user->phone . " , Password: " . $password;
            $templateid = '1407171593745579639';
            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        return response()->json("Purchase updated successfully", 200);
    }

    public function deletePurchaseUser($id){
        $purchase = User::find($id);
        $purchase->delete();
        return response()->json("success", 200);
    }
    
}
