<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Validation\Rule;



class AdminProfileController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
   
        view()->share('setting', $setting);
    }

    
    public function show()
    {
        return view('admin.adminprofile');
    }

    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'phone' => 'nullable|string',
                'email' => [
                    'required',
                    'string',
                    'email',
                    Rule::unique('users', 'email')->ignore(Auth::id()),
                ],
            ], [
                'email.unique' => 'The email address is already in use.',
            ]);
            // Update name, phone number, and email
            $user = Auth::user();
            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // $imagePath = $request->file('profile_image')->store('profile_images', 'public');
                if($request->has('profile_image') && $request->file('profile_image') != null){
                    $image = $request->file('profile_image');
                    $destinationPath = 'public/storage/profile_images';
                    $rand=rand(1,100);
                    $docImage = date('YmdHis'). $rand."." . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $docImage);
                    $img=$docImage;
                    $imagePath = '/profile_images/'.$img;
                }
                $user->update(['profile_image' => $imagePath]);
            }

            return redirect()->route('admin.profile.show')->with('success', 'Profile information updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.profile.show')->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|confirmed',
        ]);

        // Check if the old password matches the user's current password
        if (!Hash::check($request->input('old_password'), Auth::user()->password)) {
            return redirect()->route('admin.profile.show')->with('error', 'Old password is incorrect');
        }

        // Update the user's password
        $user = Auth::user();
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return redirect()->route('admin.profile.show')->with('success', 'Password updated successfully');
    }
    
}
