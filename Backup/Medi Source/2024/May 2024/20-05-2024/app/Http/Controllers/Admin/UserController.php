<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserDocument;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Customer;

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
        $permissionList = Permission::get();
        return view('admin.users.create',compact('permissionList'));
    }

    public function store(Request $request)
    {
     
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|unique:users,phone',
            'role_name' => 'required',
            'password' => 'required|min:6',
            'permission' => 'required|array',
        ]);
        $user = new User([
            'first_name' => $request->input('first_name'),
            'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role_name' => $request->input('role_name'),
            'password' => bcrypt($request->input('password')),
            'status' => $request->input('status'),
            'profile_image' => '/profile_images/profile.jpg',
            'role' => 2,
            'status' => 'active',
        ]);
    
        $user->save();
        $permissionArray = $request->permission;
        if($user){
            $permissionList = Permission::get();
            foreach ($permissionList as $key => $permission) {
                $insert['permission_id'] = $permission->id;
                $insert['user_id'] = $user->id;
                if(in_array($permission->id,$permissionArray)){
                    $insert['status'] = '1';
                } else {        
                    $insert['status'] = '0';
                }
                UserPermission::create($insert);
            }
            $roleCreate['role_id'] = '2';
            $roleCreate['user_id'] = $user->id;
            RoleUser::create($roleCreate);
        }
        return redirect()->route('admin.users.index')->with('success', 'User added successfully');
    }
    

    public function edit($id)
    {
        $user = User::with('userPermissionData','userPermissionData.permissionName')->findOrFail($id);
        $permissions = Permission::all();
        $userPermissions = $user->userPermissionData;
        return view('admin.users.edit', compact('user', 'permissions', 'userPermissions'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $data = $request->except('password');
    
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
            $data['password1'] = $request->input('password');
        }
    
        $user->update($data);
        $permissionArray = $request->permission;
        $permissions = Permission::all();
        if($user){
            foreach ($permissions as $key => $permission) {
                $permissionList = UserPermission::where('user_id',$id)->where('permission_id',$permission->id)->get();
                if(count($permissionList) == 0 && in_array($permission->id,$permissionArray)){
                    $insert['permission_id'] = $permission->id;
                    $insert['user_id'] = $id;
                    $insert['status'] = '1';
                    UserPermission::create($insert);
                }else if(count($permissionList) > 0 && in_array($permission->id,$permissionArray)){
                    $where['permission_id'] = $permission->id;
                    $where['user_id'] = $id;
                    $update['status'] = '1';
                    UserPermission::where($where)->update($update);
                }else if(count($permissionList) > 0 && !in_array($permission->id,$permissionArray)){
                    $where['permission_id'] = $permission->id;
                    $where['user_id'] = $id;
                    $update['status'] = '0';
                    UserPermission::where($where)->update($update);
                }
            }
        }
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
        // dd($id);
        $user = User::with('userDocument','cardDetail')->findOrFail($id);
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
        return redirect()->route('admin.doctors.index')->with('success', 'User document uploaded successfully');
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
            $roleCreate['role_id'] = '3';
            $roleCreate['user_id'] = $user->id;
            RoleUser::create($roleCreate);
            $email = $user->email;
            $data = [
                'name' => $user->first_name. " ".$user->last_name,
                'password' => $randomString,
                'emailText' =>$user->email,
            ];
            Mail::send('admin.emails.status_active', $data, function($message) use ($email) {
                $message->from('info@medisourcerx.com', 'MedisourceRX')
                        ->to($email)
                        ->subject('Welcome to Medisource');
            });
            try {
                $this->addCustomerToQuickBooks($request, $user);
            } catch (\Throwable $th) {
                Log::error("Error updating customer in QuickBooks: " . $th->getMessage());
            }
        }
        
        $user->update($update);

        return redirect()->route('admin.doctors.index')->with('success', 'User status updated successfully.');
    }
    private function addCustomerToQuickBooks(Request $request, User $user)
    {
        $setting=Setting::first();
        $config = config('quickbooks');
        $refreshToken = $setting->refresh_token;
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' => $config['client_secret'],
            'RedirectURI' => $config['redirect_uri'],
            'refreshTokenKey' => $refreshToken,
            'QBORealmID' => $config['realm_id'],
            'scope' => 'com.intuit.quickbooks.accounting',  
            'baseUrl' => $config['base_url'],
        ]);
        if ($dataService->getOAuth2LoginHelper()) {
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $refreshedToken = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
            $dataService->updateOAuth2Token($refreshedToken);
        }
        $displayname = $user->first_name . ' ' . $user->last_name;
        $query = "SELECT * FROM Customer WHERE DisplayName = '$displayname'";
        $customerData = $dataService->Query($query);
        if (isset($customerData) && !empty($customerData) && count($customerData) > 0) {
            $customer = $customerData[0];
            $customer->Id = $customer->Id;
            $customer->GivenName = $displayname;
            $customer->DisplayName = $displayname;
            $customer->CompanyName = $user->organization_name;
            $customer->BusinessNumber = $user->phone;
            $customer->Mobile = $user->phone;
            if(isset($customer->PrimaryEmailAddr)){
                $customer->PrimaryEmailAddr->Address = $user->email;
            }
            if(isset($customer->PrimaryPhone)){
                $customer->PrimaryPhone->FreeFormNumber = $user->phone;
            }
            try {
                $result = $dataService->Update($customer);
            } catch (ServiceException $ex) {
                Log::error("Error updating customer in QuickBooks: " . $ex->getMessage());
            }
        } else {
            $organization = $user->organization_name;
            $customer = Customer::create([
                "GivenName" => $displayname,
                "DisplayName" => $displayname,
                "CompanyName" => $organization,
                "PrimaryEmailAddr" => [
                    "Address" => $user->email
                ],
                "BillAddr" => [
                    "Line1" => $user->practice_address_street,
                    "City" => $user->city,
                    "CountrySubDivisionCode" => "CA",
                    "PostalCode" => $user->zip_code,
                    "Country" => "USA",
                ],
                "PrimaryPhone" => [
                    "FreeFormNumber" => $user->phone
                ]
            ]);
            try {
                $result = $dataService->Add($customer);
                if (isset($result)) {
                    User::where('id', $user->id)->update(['quick_book_cus_id' => $result->Id]);
                }
            } catch (ServiceException $ex) {
                Log::error("Error adding customer to QuickBooks: " . $ex->getMessage());
            }
        }
    }
}
