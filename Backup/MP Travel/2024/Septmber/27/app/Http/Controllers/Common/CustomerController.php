<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomerRequest;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Log;
use App\Models\ModuleModifiedLogs;
use App\Models\ServicePreference;
use App\Models\ServicePreferenceTag;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PDF;
use Illuminate\Support\Str;
use PDO;

class CustomerController extends Controller
{
    private $customer;
    public function __construct()
    {
        $page = "Customer";
        $this->customer = resolve(Customer::class)->with('lastServiceTake','moduleModifiedLog', 'moduleModifiedLog.userDetail', 'servicePreferenceTagDetail', 'servicePreferenceTagDetail.userDetail', 'servicePreferenceTagDetail.servicePreferenceDetail');
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.customer.index');
    }

    public function customerAjaxList(Request $request)
    {
        $search = $request->search;
        $customerList = $this->customer
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('mobile_number', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%')
                        ->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })->latest()
            ->get();
        return view('admin.customer.ajax_list', compact('customerList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        $customerList = $this->customer
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('mobile_number', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%')
                        ->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })->latest()
            ->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Customer.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Customer Name', 'Phone Number', 'Reference Of', 'Created By', 'Created At', 'Last Modified By', 'Last Modified At');
            $callback = function () use ($customerList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($customerList as $customer) {
                    $name = isset($customer->userDetail) ? ucfirst($customer->userDetail->name) : '';
                    $lastModifiedBy = isset($customer->moduleModifiedLog) ? $customer->moduleModifiedLog->userDetail->name : '-';
                    $lastModifiedAt = isset($customer->moduleModifiedLog) ? UtilityHelper::convertDmyAMPMFormat($customer->moduleModifiedLog->created_at)  : "-";
                    $date = isset($customer->created_at) ? UtilityHelper::convertDmyAMPMFormat($customer->created_at)  : "-";
                    fputcsv($file, array($customer->name, $customer->mobile_number, $customer->reference, $name, $date, $lastModifiedBy, $lastModifiedAt));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.customer', ['customerList' => $customerList, 'setting' => $setting]);
            return $pdf->download('Customer.pdf');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countryList = Country::get();
        $lastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'MP-CUS-' . substr("00000{$lastId}", -6);
        return view('admin.customer.create', compact('countryList', 'customerId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCustomerRequest $request)
    {
        $lastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'MP-CUS-' . substr("00000{$lastId}", -6);
        $customer = new Customer();
        $customer->insurance = $request->insurance;
        $customer->insurance_type = $request->insurance_type;
        $customer->customer_department = $request->customer_department;
        $customer->name = $request->name;
        $customer->customer_id = $customerId;
        $customer->email = $request->email;
        $customer->mobile_number = $request->mobile_number;
        $customer->birth_date = $request->birth_date;
        $customer->pan_card_number = $request->pan_card_number;
        $customer->aadhar_number = $request->aadhar_number;
        $customer->service_preference = $request->service_preference;
        $customer->reference = $request->reference;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->pin_code = $request->pin_code;
        $customer->passport_number = $request->passport_number;
        $customer->created_by = Auth()->user()->id;
        $customer->role_id = 3;

        if ($request->hasFile('gst_certificate')) {
            $file = $request->file('gst_certificate');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $gstCertificatePath = $file->storeAs('customer/gst_certificate', $newFilename, 'public');
            $customer->gst_certificate = $gstCertificatePath;
        }
        if ($request->hasFile('aadhar_card_file')) {
            $file = $request->file('aadhar_card_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $aadharCardFilePath = $file->storeAs('customer/aadhar_card', $newFilename, 'public');
            $customer->aadhar_card_file = $aadharCardFilePath;
        }
        if ($request->hasFile('pan_card_file')) {
            $file = $request->file('pan_card_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $panCardPath = $file->storeAs('customer/pan_card', $newFilename, 'public');
            $customer->pan_card_file = $panCardPath;
        }
        if ($request->hasFile('passport_file')) {
            $file = $request->file('passport_file');
            $passportFileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $passportFilePath = $file->storeAs('customer/passport', $passportFileName, 'public');
            $customer->passport_file = $passportFilePath;
        }
        $insert = $customer->save();
        if ($insert) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Customer',
                'description' => auth()->user()->name . " created a Customer named '" . $request->name . "'"
            ]);
            return redirect()->route('customer.index')->with('success','Customer has been created successfully.');
            // return response()->json(['data'=>$customer,'message'=>'Customer has been created successfully.'],200);
        }
        return redirect()->route('customer.create')->with('message','Something went wrong.');
        // return response()->json(['data'=>[],'message'=>'Something went wrong'],500);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = $this->customer->find($id);
        $leadList = Lead::whereHas('followUpDetail')->where('customer_id',$id)->latest()->get();
        return view('admin.customer.show', compact('leadList','customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $id = $customer->id;
        $customer = $this->customer->find($id);
        $countryList = Country::get();
        return view('admin.customer.edit', compact('customer', 'countryList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCustomerRequest $request, Customer $customer)
    {
        $id = $request->id;
        $customer = $this->customer->findOrFail($id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->mobile_number = $request->mobile_number;
        $customer->birth_date = $request->birth_date;
        $customer->pan_card_number = $request->pan_card_number;
        $customer->aadhar_number = $request->aadhar_number;
        $customer->reference = $request->reference;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->pin_code = $request->pin_code;
        $customer->passport_number = $request->passport_number;
        $customer->created_by = Auth()->user()->id;
        $customer->role_id = 3;

        if ($request->hasFile('gst_certificate')) {
            $file = $request->file('gst_certificate');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $gstCertificatePath = $file->storeAs('customer/gst_certificate', $newFilename, 'public');
            $customer->gst_certificate = $gstCertificatePath;
        }
        if ($request->hasFile('aadhar_card_file')) {
            $file = $request->file('aadhar_card_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $aadharCardFilePath = $file->storeAs('customer/aadhar_card', $newFilename, 'public');
            $customer->aadhar_card_file = $aadharCardFilePath;
        }
        if ($request->hasFile('pan_card_file')) {
            $file = $request->file('pan_card_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $panCardPath = $file->storeAs('customer/pan_card', $newFilename, 'public');
            $customer->pan_card_file = $panCardPath;
        }
        if ($request->hasFile('passport_file')) {
            $file = $request->file('passport_file');
            $passportFileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $passportFilePath = $file->storeAs('customer/passport', $passportFileName, 'public');
            $customer->passport_file = $passportFilePath;
        }
        $changedColumns = $customer->getDirty();
        if (!empty($changedColumns)) {
            foreach ($changedColumns as $column => $newValue) {
                $originalValue = $customer->getOriginal($column);
                if ($originalValue != $newValue) {
                    $modifiedLog = new ModuleModifiedLogs();
                    $modifiedLog->module_name = "Customer";
                    $modifiedLog->module_task_id = $id;
                    $modifiedLog->column_name = $column;
                    $modifiedLog->new_value = $newValue;
                    $modifiedLog->original_value = $originalValue;
                    $modifiedLog->created_by = auth()->user()->id;
                    $modifiedLog->description = "Column '{$column}' has changed from '{$originalValue}' to '{$newValue}'";
                    $modifiedLog->save();
                }
            }
        }
        $update = $customer->save();
        if ($update) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Customer',
                'description' => auth()->user()->name . " Updated a Customer named '" . $request->name . "'"
            ]);
            return redirect()->route('customer.index')->with('success', 'Customer has been Updated successfully.');
        }
        return redirect()->route('customer.edit',$customer->id)->with('error', 'Something went wrong.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = $this->customer->find($id);
        $delete = $customer->delete();
        if ($delete) {
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'Customer',
                'description' => auth()->user()->name . " Deleted Customer named '" . $customer->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Customer has been deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }
    public function departmentCustomer(Request $request)
    {
        $customer = $this->customer->where('insurance', $request->type)->get();
        return response()->json(['status' => 1, 'data' => $customer], 200);
    }

    public function getCustomerDetail(Request $request)
    {
        $customer = $this->customer->with('lastServiceTake','cityDetail', 'stateDetail', 'countryDetail', 'leadDetail', 'leadDetail.userDetail')->find($request->id);
        return response()->json(['status' => 1, 'data' => $customer], 200);
    }

    public function customerProfileChange($id)
    {
        $customerDetail = $this->customer->find($id);
        $servicePreferenceList = ServicePreference::latest()->get();
        return view('admin.customer.profile', compact('customerDetail', 'servicePreferenceList'));
    }

    public function customerProfileUpdate(Request $request)
    {
        $customer = $this->customer->find($request->customer_id);
        if ($customer) {
            $customer->customer_type = $request->customer_type;
            $customer->budget_amount = $request->budget_amount;
            $customer->premium_amount = $request->premium_amount;
            $customer->save();
            if (!empty($request->service_preference_tag_id)) {
                foreach ($request->service_preference_tag_id as $key => $preference) {
                    $servicePreferenceData = ServicePreferenceTag::find($preference);
                    if (isset($servicePreferenceData)) {
                        $servicePreferenceData->customer_id = $request->customer_id;
                        $servicePreferenceData->service_preference_id = $request->preference_id[$key];
                        $servicePreferenceData->status = $request->preference_check[$key] ??0;
                        $servicePreferenceData->created_by = Auth()->user()->id;
                        $servicePreferenceData->save();
                    } else {
                        $servicePreferenceTag =  new ServicePreferenceTag();
                        $servicePreferenceTag->customer_id = $request->customer_id;
                        $servicePreferenceTag->service_preference_id = $request->preference_id[$key];
                        $servicePreferenceTag->status = $request->preference_check[$key] ??0;
                        $servicePreferenceTag->created_by = Auth()->user()->id;
                        $servicePreferenceTag->save();
                    }
                }
            }
            try {
                $modifiedLog = new ModuleModifiedLogs();
                $modifiedLog->module_name = "Customer";
                $modifiedLog->module_task_id = $customer->id;
                $modifiedLog->column_name = 'All Columns';
                $modifiedLog->new_value = 'All Columns';
                $modifiedLog->original_value = "All Columns";
                $modifiedLog->created_by = auth()->user()->id;
                $modifiedLog->description = "Add New Service Preference tag for Customer `{$customer->name}`";
                $modifiedLog->save();
            } catch (\Throwable $th) {
            }
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'Customer',
                'description' => auth()->user()->name . " Updated Customer Preference named '" . $customer->name . "'"
            ]);
            return redirect()->route('customer.index')->with('success', 'Customer preference service tag added successfully');
        }
        return redirect()->route('customer.change-profile',$request->customer_id)->with('error', 'Something went to wrong.');
    }
    public function customerProfileView($id)
    {
        $customer = $this->customer->find($id);
        $countryList = Country::get();
        $servicePreferenceList = ServicePreference::latest()->get();
        return view('admin.customer.view_profile', compact('customer', 'servicePreferenceList', 'countryList'));
    }
    public function removeCustomerTag(Request $request)
    {
        $id = $request->id;
        $customerId = $request->customer_id;
        $customerTag = ServicePreferenceTag::with('servicePreferenceDetail')->find($id);
        if ($customerTag) {
            $preferenceName= isset($customerTag->servicePreferenceDetail) ?$customerTag->servicePreferenceDetail->name : "";
            $customerDetail = Customer::find($customerId);
            $delete = $customerTag->delete();
            if ($delete) {
                Log::create([
                    'user_id' => Auth()->user()->id,
                    'module' => 'Customer',
                    'description' => auth()->user()->name . " removed Customer Preference Tag for this '{$preferenceName}' of customer '{$customerDetail->name}' named ",
                ]);
                return response()->json(['data' => '', 'message' => '', 'status' => 1], 200);
            }
            return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
    public function removeCardCustomer(Request $request){
        $id = $request->id;
        $column = $request->type;
        $user = Customer::find($id);
        $user->$column = null;
        $user->save();
        return $user;
    }
}
