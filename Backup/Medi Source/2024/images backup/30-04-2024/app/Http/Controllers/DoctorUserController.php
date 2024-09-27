<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Product;
use App\Models\ProductPackage;
use App\Models\DoctorPrice;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Log;
use Nnjeim\World\World;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Data\IPPCustomer;
use QuickBooksOnline\API\Facades\Customer;

class DoctorUserController extends Controller
{

    public function __construct()
    {
        $setting = Setting::first();

        view()->share('setting', $setting);
    }

    public function showRegistrationForm()
    {
        $usaCities = World::cities([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaCities->success) {
            $cities = $usaCities->data;
        }
        $usaStates = World::states([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaStates->success) {
            $states = $usaStates->data;
        }
        if ($usaStates->success) {
            $states = $usaStates->data;
        }
        return view('frontend.register', compact('cities',  'states'));
    }

    public function register(Request $request)
    {
        
        $request->validate([
            'organization_name' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|unique:users,phone',
            // 'npi' => 'required',
            // 'business_license_number' => 'required',
            // 'prescriber_state_license_number' => 'required',
            // 'dea_number' => 'required',
            'speciality' => 'required',
            'practice_address_street' => 'required',
            // 'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'zip_code' => 'required',
            'fileupload' => 'required|array',
            'fileupload.*' => 'file|mimes:jpeg,png,gif,pdf,doc,docx',
            'consent' => 'accepted',
            'pharmacy_agreement' => 'accepted',
            'terms_condition' => 'accepted',
        ]);
        // Generate a random password
        // $password = Str::random(8);
        $user = User::create([
            'organization_name' => $request->input('organization_name'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'npi' => $request->input('npi'),
            'business_license_number' => $request->input('business_license_number'),
            'prescriber_state_license_number' => $request->input('prescriber_state_license_number'),
            'dea_number' => $request->input('dea_number'),
            'speciality' => $request->input('speciality'),
            'practice_address_street' => $request->input('practice_address_street'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'password' =>Hash::make('123'),
            // 'state' => $request->input('state'),
            'zip_code' => $request->input('zip_code'),
            'role' => 3, // Default role ID for doctor
            'status' => 'inactive', // Default status for doctor
            'consent' => $request->input('consent') ? 1 : 0, // Store 1 if consent is given, 0 otherwise
        ]);
        try {
            $this->addCustomerToQuickBooks($request, $user);
        } catch (\Throwable $th) {
            Log::error("Error updating customer in QuickBooks: " . $th->getMessage());
        }
        if ($user) {
            if ($request->has('fileupload') && $request->file('fileupload') != null) {
                foreach ($request->fileupload as $file) {
                    $destinationPath = 'public/storage/user_document';
                    $docImage = time() . "." . $file->getClientOriginalExtension();
                    $file->move($destinationPath, $docImage);
                    $filePath = "user_document/" . $docImage;
                    UserDocument::create([
                        'user_id' => $user->id,
                        'document' => $filePath,
                    ]);
                }
            }
            $email = $request->input('email');
            $data = [
                'name' => $user->last_name. " ".$user->first_name,
            ];
            if(isset($email)){
                Mail::send('admin.emails.register', $data, function($message) use ($email) {
                    $message->to($email)
                    ->subject('Welcome to Medisource');
                });
            }
            
        }
        $request->session()->flash('account_approval_notification', true);
        return redirect()->route('logindoctor')->with('success', 'Account created successfully. Please log in.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'organization_name' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'speciality' => 'required',
            'practice_address_street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
        ]);

        // Find the user in your application's database
        $user = User::findOrFail($id);

        // Update the user in your application's database
        $user->update([
            'organization_name' => $request->input('organization_name'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'speciality' => $request->input('speciality'),
            'practice_address_street' => $request->input('practice_address_street'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'zip_code' => $request->input('zip_code'),
            'consent' => $request->input('consent_checkbox') ? 1 : 0,
        ]);

        // Update the user in QuickBooks
        // $this->updateCustomerInQuickBooks($request, $user);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor User updated successfully.');
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
            $customer = Customer::create([
                "GivenName" => $displayname,
                "DisplayName" => $displayname,
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

    private function updateCustomerInQuickBooks(Request $request, User $user)
    {
        $config = config('quickbooks');
        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' => $config['client_secret'],
            'RedirectURI' => $config['redirect_uri'],
            'accessTokenKey' => $config['access_token'],
            'refreshTokenKey' => $config['refresh_token'],
            'QBORealmID' => $config['realm_id'],
            'baseUrl' => $config['base_url'],
        ]);

        $displayname = $user->first_name . ' ' . $user->last_name;
        $query = "SELECT * FROM Customer WHERE DisplayName = '[$displayname]'";
        $customer = $dataService->Query($query);

        if (isset($customer) && !empty($customer) && count($customer) > 0) {
            $customer = $customer[0];
            $customer->Id = $customer->Id;
            $customer->GivenName = $displayname;
            $customer->DisplayName = $displayname;
            $customer->CompanyName = $user->organization_name;
            $customer->BusinessNumber = $user->phone;
            $customer->Mobile = $user->phone;
            $customer->PrimaryEmailAddr->Address = $user->email;
            $customer->PrimaryPhone->FreeFormNumber = $user->phone;

            try {
                $result = $dataService->Update($customer);
                // Log or handle the update operation
            } catch (ServiceException $ex) {
                // Handle the exception
                Log::error("Error updating customer in QuickBooks: " . $ex->getMessage());
            }
        } else {
            // If the customer does not exist, create a new one
            $this->addCustomerToQuickBooks($request, $user);
        }
    }
    // public function sendWelcomeEmail($email, $password)
    // {
    //     $content = "Welcome to Medisourcerx!\n\n";
    //     $content .= "Your account has been created with the following credentials:\n";
    //     $content .= "Email: $email\n";
    //     $content .= "Password: $password\n";

    //     Mail::raw($content, function ($message) use ($email) {
    //         $message->to($email)->subject('Welcome to Medisource');
    //     });
    // }

    public function index()
    {
        $doctors = User::where('role', 3)->get();

        return view('admin.doctor.index', compact('doctors'));
    }

    // DoctorUserController.php

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $usaCities = World::cities([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaCities->success) {
            $cities = $usaCities->data;
        }

        // Fetch states in the USA
        $usaStates = World::states([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaStates->success) {
            $states = $usaStates->data;
        }
        return view('admin.doctor.edit', compact('user', 'cities', 'states'));
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'organization_name' => 'required',
    //         'first_name' => 'required',
    //         'last_name' => 'required',
    //         'email' => 'required|email',
    //         'phone' => 'required|numeric',
    //         // 'status' => 'required|in:active,inactive',
    //         // 'npi' => 'nullable', // Add more validation rules as needed
    //         // 'business_license_number' => 'nullable',
    //         // 'prescriber_state_license_number' => 'nullable',
    //         // 'dea_number' => 'nullable',
    //         'speciality' => 'required',
    //         'practice_address_street' => 'required',
    //         'city' => 'required',
    //         'state' => 'required',
    //         'zip_code' => 'required',
    //         // Add more fields as needed
    //     ]);

    //     $user = User::findOrFail($id);
    //     // $password = Str::random(8);
    //     if ($request->has('password') && $request->password != '') {
    //         $password = Hash::make($request->password);
    //     } else {
    //         $password = $user->password;
    //     }
    //     $user->update([
    //         'organization_name' => $request->input('organization_name'),
    //         'first_name' => $request->input('first_name'),
    //         'last_name' => $request->input('last_name'),
    //         'email' => $request->input('email'),
    //         'phone' => $request->input('phone'),
    //         'status' => $request->input('status'),
    //         'npi' => $request->input('npi'),
    //         'business_license_number' => $request->input('business_license_number'),
    //         'prescriber_state_license_number' => $request->input('prescriber_state_license_number'),
    //         'dea_number' => $request->input('dea_number'),
    //         'speciality' => $request->input('speciality'),
    //         'practice_address_street' => $request->input('practice_address_street'),
    //         'city' => $request->input('city'),
    //         'state' => $request->input('state'),
    //         'zip_code' => $request->input('zip_code'),
    //         'password' => $password,
    //         'consent' => $request->input('consent_checkbox') ? 1 : 0,

    //         // Update more fields as needed
    //     ]);

    //     return redirect()->route('admin.doctors.index')->with('success', 'Doctor User updated successfully.');
    // }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($id);
        $user->update(['status' => $request->input('status')]);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor User status updated successfully.');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor User deleted successfully.');
    }

    // DoctorUserController.php

    public function create()
    {
        $usaCities = World::cities([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaCities->success) {
            $cities = $usaCities->data;
        }

        // Fetch states in the USA
        $usaStates = World::states([
            'filters' => [
                'country_code' => 'US',
            ],
        ]);
        if ($usaStates->success) {
            $states = $usaStates->data;
        }
        return view('admin.doctor.create', compact('cities',  'states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'organization_name' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|unique:users,phone',
            // 'npi' => 'required',
            // 'business_license_number' => 'required',
            // 'prescriber_state_license_number' => 'required',
            // 'dea_number' => 'required',
            'speciality' => 'required',
            'practice_address_street' => 'required',
            // 'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'password' => 'required|min:8', // Add any other password validation rules you need
            'confirm_password' => 'required|same:password',
        ]);

        // Generate a random password
        $password = Str::random(8);

        $doctorUser = User::create([
            'organization_name' => $request->input('organization_name'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'npi' => $request->input('npi'),
            'business_license_number' => $request->input('business_license_number'),
            'prescriber_state_license_number' => $request->input('prescriber_state_license_number'),
            'dea_number' => $request->input('dea_number'),
            'speciality' => $request->input('speciality'),
            'practice_address_street' => $request->input('practice_address_street'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'zip_code' => $request->input('zip_code'),
            'password' => Hash::make($password),
            'role' => 3, // Default role ID for doctor
            'status' => 'inactive', // Default status for doctor
            'consent' => $request->input('consent_checkbox') ? 1 : 0, // Store 1 if consent is given, 0 otherwise
        ]);
        // Send welcome email
        // $this->sendWelcomeEmail($doctorUser->email, $password);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor User created successfully.');
    }
    public function getCitiesForState(Request $request)
    {
        $state = $request->input('state');

        $cities = World::cities([
            'filters' => [
                'country_code' => 'US',  // Adjust this based on your country code
                'state_code' => $state,
            ],
        ]);
        $city_data = array();
        foreach ($cities->data as $city) {
            $city_data[] = $city;
        }
        return $city_data;
    }


    public function doctorLogin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Check if the user is a doctor and has an active status
                if ($user->role == 3 && $user->status == 'active') {
                    // Redirect to the doctor dashboard or any other page
                    return redirect()->route('home')->with('success', 'Login successful.');
                } else {
                    // If not a doctor or inactive status, logout and show a SweetAlert
                    Auth::logout();
                    Alert::error('Invalid credentials or account not active.')->persistent(true, true);
                    return redirect()->route('logindoctor');
                }
            } else {
                // Authentication failed, show a SweetAlert
                Alert::error('Invalid email or password.')->persistent(true, true);
                return redirect()->route('logindoctor');
            }
        } catch (ValidationException $e) {
            return redirect()->route('logindoctor')->withErrors($e->errors())->withInput();
        }
    }

    public function show($id)
    {
        $products = Product::all(); // Fetch all products from the database
        $existingData = DoctorPrice::where('doctor_id', $id)->get();
        $doctor = User::findOrFail($id);

        // Assuming you have a logic to check if the user is a doctor
        $isDoctor = $doctor->role == 3 && $doctor->status == 'active';

        return view('admin.doctor.show', compact('products', 'existingData', 'doctor', 'id', 'isDoctor',));
    }

    public function getproductPackage(Request $request)
    {
        $product_packages = ProductPackage::where('product_id', $request->productID)->get();
        return response()->json($product_packages);
    }




    public function submitForm(Request $request, $id)
    {
        try {
            \DB::beginTransaction();

            // Process and save the form data
            if ($request->has('products')) {
                foreach ($request->input('products') as $key => $product) {
                    $price = $request->input('prices.' . $key);
                    $package = $request->input('packages.' . $key);
                    $existingFieldId = $request->input('existingFields.' . $key);

                    if (!empty($existingFieldId)) {
                        // Update the existing field
                        $existingField = DoctorPrice::find($existingFieldId);
                        if ($existingField) {
                            $existingField->update([
                                'doctor_id' => $id,
                                'product_id' => $product,
                                'package_id' => $package,
                                'price' => $price,
                            ]);
                        }
                    } else {
                        // Create a new field
                        DoctorPrice::create([
                            'doctor_id' => $id,
                            'product_id' => $product,
                            'package_id' => $package,
                            'price' => $price,
                        ]);
                    }
                }
            }

            // Process and delete removed fields
            if ($request->has('removedFields')) {
                foreach ($request->input('removedFields') as $removedFieldId) {
                    $removedField = DoctorPrice::find($removedFieldId);

                    if ($removedField) {
                        $removedField->delete();
                    }
                }
            }

            // Process and save the new fields
            if ($request->has('newProducts')) {
                foreach ($request->input('newProducts') as $key => $newProduct) {
                    $newPrice = $request->input('newPrices.' . $key);
                    $newPackage = $request->input('newPackages.' . $key);
                    // dd($request->all(), $newPackage, $newPrice);

                    DoctorPrice::create([
                        'doctor_id' => $id,
                        'product_id' => $newProduct,
                        'package_id' => $newPackage,
                        'price' => $newPrice,
                    ]);
                }
            }

            \DB::commit();

            // Redirect or do any other action after saving the data
            return redirect()->route('admin.doctors.show', ['id' => $id])->with('success', 'Prices updated successfully.');
        } catch (\Exception $e) {
            \DB::rollback();
            // Handle the exception, log it, and return a response (e.g., redirect with an error message)
            return redirect()->route('admin.doctors.show', ['id' => $id])->with('error', 'An error occurred while updating prices.');
        }
    }

    public function removeField(Request $request)
    {
        try {
            $removedFieldId = $request->input('removedFieldId');

            if ($removedFieldId) {
                $removedField = DoctorPrice::find($removedFieldId);

                if ($removedField) {
                    $removedField->delete();
                    return response()->json(['success' => true], 200);
                }
            }

            return response()->json(['success' => false, 'message' => 'Field not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }
}
