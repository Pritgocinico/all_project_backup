<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
   
        view()->share('setting', $setting);
    }

    public function index()
    {
        // Logic for listing users
        $users = User::where('role',2)->whereIn('status', ['active', 'inactive'])->get();
        return view('admin.users.index', ['users' => $users]);
    }

    public function create()
    {
        // Logic for showing the create user form
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
     
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|unique:users,phone',
            'address' => 'required',
            'password' => 'required|min:6',
            // 'status' => 'required|in:active,inactive',

        ]);
    
        // Create a new user in the database
        $user = new User([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'password' => bcrypt($request->input('password')),
            'status' => $request->input('status'),
            'role' => 2,
            'status' => 'active',
        ]);
    
        $user->save();
    
        // Redirect or respond as needed
        return redirect()->route('admin.users.index')->with('success', 'User added successfully');
    }
    

    public function edit($id)
    {
        // Logic for showing the edit user form
        $user = User::findOrFail($id);
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $data = $request->except('password');
    
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }
    
        $user->update($data);
    
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        // Logic for deleting a user
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    public function show($id){
        $user = User::with('userDocument')->findOrFail($id);
        return view('admin.users.show',compact('user','id'));
    }

    public function uploadDocument(Request $request){
        if ($request->has('upload_document') && $request->file('upload_document') != null) {
            foreach ($request->upload_document as $file) {
                $destinationPath = 'public/storage/user_document';
                $docImage = time() . "." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $docImage);
                $filePath = "user_document/" . $docImage;
                UserDocument::create([
                    'user_id' => $request->user_id,
                    'document' => $filePath,
                ]);
            }
        }
        return redirect()->route('admin.users.index')->with('success', 'User document uploaded successfully');
    }

    public function updateUserStatus(Request $request,$id){
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);
        $randomString = Str::random(9);
        $user = User::findOrFail($id);
        $update = [
            'status' => $request->input('status'),
        ];
        if($request->input('status') == "active"){
            $update = [
                'password' => Hash::make($randomString),
                'password1' =>$randomString,
                'status' => $request->input('status'),
            ];
            $email = $user->email;
            $data = [
                'name' => $user->last_name. " ".$user->first_name,
                'password' => $randomString,
                'emailText' =>$user->email,
            ];
            Mail::send('admin.emails.status_active', $data, function($message) use ($email) {
                $message->to($email)
                        ->subject('Welcome to Medisource');
            });
        }
        
        $user->update($update);

        return redirect()->route('admin.users.index')->with('success', 'User status updated successfully.');
    }
}
