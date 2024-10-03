<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomerRequest;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Log;
use App\Models\ModuleModifiedLogs;
use App\Models\ServicePreference;
use App\Models\ServicePreferenceTag;
use App\Models\Setting;
use App\Models\Village;
use App\Models\CustAlternateNumber;
use App\Models\CustAddress;
use App\Models\Disease;
use App\Models\Lead;
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
        $this->customer = resolve(Customer::class)->with('getAlternativeNumber','moduleModifiedLog', 'moduleModifiedLog.userDetail', 'servicePreferenceTagDetail', 'servicePreferenceTagDetail.userDetail', 'servicePreferenceTagDetail.servicePreferenceDetail', 'customerAddress','custDisease');
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
        $customerId = 'SA-CUS-' . substr("00000{$lastId}", -6);
        $diseases = Disease::get();
        return view('admin.customer.create', compact('countryList', 'customerId', 'diseases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCustomerRequest $request)
    {
        $lastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'SA-CUS-' . substr("00000{$lastId}", -6);
        $customer = new Customer();
        $customer->name = ucwords(strtolower($request->name));
        $customer->email = $request->email;
        $customer->customer_id = $customerId;
        $customer->cust_age = $request->cust_age;
        $customer->cust_height = $request->cust_height;
        $customer->cust_weight = $request->cust_weight;
        $customer->wa_exist = $request->wa_exist;
        $customer->cust_disease = $request->cust_disease;
        $customer->mobile_number = $request->mobile_number;
        $customer->height_unit = $request->height_unit;
        $customer->created_by = Auth()->user()->id;
        $customer->role_id = 3;

        $insert = $customer->save();

        if (isset($request->cust_alt_num) && isset($request->alt_wa_exist)) {
            foreach ($request->cust_alt_num as $index => $altNum) {
                if ($altNum !== null) {
                    $cust_alt_num = new CustAlternateNumber();
                    $cust_alt_num->customer_id = $customer->id;
                    $cust_alt_num->cust_alt_num = $altNum;
                    $cust_alt_num->alt_wa_exist = $request->alt_wa_exist[$index];
                    $cust_alt_num->save();
                }
            }
        }

        foreach ($request->add_type as $index => $add_type) {
            $custAddress = new CustAddress();
            $custAddress->customer_id = $customer->id;
            $custAddress->add_type = $add_type;
            $custAddress->pin_code = $request->pin_code[$index];
            $custAddress->address = ucwords(strtolower($request->address[$index]));
            $custAddress->village = $request->village[$index];
            $custAddress->office_name = $request->office_name[$index];
            $custAddress->dist_state = $request->dist_state[$index];
            $custAddress->dist_city = $request->dist_city[$index];

            $custAddress->save();
        }

        if ($insert) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Customer',
                'description' => auth()->user()->name . " created a Customer named '" . $request->name . "'"
            ]);
            return redirect()->route('customer.index')->with('success', 'Customer has been created successfully.');
        }
        return redirect()->route('customer.create')->with('error', 'Something went wrong.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = $this->customer->find($id);
        $alternateNumbers = CustAlternateNumber::where('customer_id', $id)->get();
        return view('admin.customer.show', compact('customer', 'alternateNumbers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $id = $customer->id;
        $customer = $this->customer->find($id);
        $alternateNumbers = CustAlternateNumber::where('customer_id', $id)->get();
        $custAddresses = CustAddress::where('customer_id', $id)->get();
        $countryList = Country::get();
        $diseases = Disease::get();
        return view('admin.customer.edit', compact('customer', 'countryList', 'alternateNumbers', 'custAddresses', 'diseases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCustomerRequest $request, Customer $customer)
    {
        $id = $request->id;
        $customer = $this->customer->findOrFail($id);
        $customer->name = ucwords(strtolower($request->name));
        $customer->email = $request->email;
        $customer->mobile_number = $request->mobile_number;
        $customer->cust_age = $request->cust_age;
        $customer->cust_height = $request->cust_height;
        $customer->cust_weight = $request->cust_weight;
        $customer->wa_exist = $request->wa_exist;
        $customer->cust_disease = $request->cust_disease;
        // $customer->address = $request->address;
        // $customer->city = $request->city;
        // $customer->state = $request->state;
        // $customer->country = $request->country;
        // $customer->pin_code = $request->pin_code;
        $customer->height_unit = $request->height_unit;
        $customer->created_by = Auth()->user()->id;
        $customer->role_id = 3;

        $update = $customer->save();

        if ($request->has('alt_num_ids')){
            foreach($request->alt_num_ids as $key => $id){
                $cust_alt_num = CustAlternateNumber::find($id);
                $cust_alt_num->cust_alt_num = $request->cust_alt_num[$key];
                $cust_alt_num->alt_wa_exist = $request->alt_wa_exist[$key];
                $cust_alt_num->save();
            }
        }

        if ($request->has('cust_alt_num')) {
            foreach ($request->cust_alt_num as $key => $number) {
                if (!isset($request->alt_num_ids[$key])) {
                    if (!empty($number)) {
                        CustAlternateNumber::create([
                            'customer_id' => $customer->id,
                            'cust_alt_num' => $number,
                            'alt_wa_exist' => $request->alt_wa_exist[$key],
                        ]);
                    }
                }
            }
        }

        if (isset($request->address_id)){
            foreach($request->address_id as $key => $id){
                $cust_address = CustAddress::find($id);
                if(isset($cust_address)){
                    $cust_address->add_type = $request->add_type[$key];
                    $cust_address->pin_code = $request->pin_code[$key];
                    $cust_address->address = ucwords(strtolower($request->address[$key]));
                    $cust_address->village = $request->village[$key];
                    $cust_address->office_name = $request->office_name[$key];
                    $cust_address->dist_state = $request->dist_state[$key];
                    $cust_address->dist_city = $request->dist_city[$key];
                    $cust_address->save();
                } else {
                    $custAddress = new CustAddress();
                    $custAddress->customer_id = $customer->id;
                    $custAddress->add_type = $request->add_type[$key];
                    $custAddress->pin_code = $request->pin_code[$key];
                    $custAddress->address = ucwords(strtolower($request->address[$key]));
                    $custAddress->village = $request->village[$key];
                    $custAddress->office_name = $request->office_name[$key];
                    $custAddress->dist_state = $request->dist_state[$key];
                    $custAddress->dist_city = $request->dist_city[$key];
                    $custAddress->save();
                }
            }
        }

        if ($update) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Customer',
                'description' => auth()->user()->name . " Updated a Customer named '" . $request->name . "'"
            ]);
            return redirect()->route('customer.index')->with('success', 'Customer has been Updated successfully.');
        }
        return redirect()->route('customer.create')->with('error', 'Something went wrong.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = $this->customer->find($id);
        $delete = $customer->delete();
        $custAltnumber = CustAlternateNumber::where('customer_id', $id)->get();
        $custAddresses = CustAddress::where('customer_id', $id)->get();
        if ($custAltnumber) {
            foreach ($custAltnumber as $key => $value) {
                $value->delete();
            }
        }
        if ($custAddresses) {
            foreach ($custAddresses as $key => $value) {
                $value->delete();
            }
        }
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

    public function alt_numDelete(Request $request){
        $id = $request->id;
        $delete = CustAlternateNumber::where('id', $id)->delete();
        if ($delete) {
            return response()->json(['status' => 1, 'message' => 'Number deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }

    public function addressDelete(Request $request){
        $id = $request->id;
        $delete = CustAddress::where('id', $id)->delete();
        if ($delete) {
            return response()->json(['status' => 1, 'message' => 'Address deleted successfully.'], 200);
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
        $customer = $this->customer->where('mobile_number', $request->number)->first();
        
        if (!$customer) {
            $altCustomer = CustAlternateNumber::where('cust_alt_num', $request->number)->first();
            if ($altCustomer) {
                $customer = Customer::where('id', $altCustomer->customer_id)->with('custDisease')->with('customerAddress')->with('getAlternativeNumber')->first();
                // dd($customer, $altCustomer->customer_id);
                return response()->json(['status' => 1, 'data' => $customer], 200);
            }
        }

        if ($customer) {
            return response()->json(['status' => 1, 'data' => $customer], 200);
        }
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
            if (!empty($request->service_preference_tag_id)) {
                foreach ($request->service_preference_tag_id as $key => $preference) {
                    $servicePreferenceData = ServicePreferenceTag::find($preference);
                    if (isset($servicePreferenceData)) {
                        $servicePreferenceData->customer_id = $request->id;
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
        return redirect()->route('customer.index')->with('success', 'Customer preference service tag added successfully');
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
            $customerDetail = Customer::find($customerId);
            $delete = $customerTag->delete();
            if ($delete) {
                Log::create([
                    'user_id' => Auth()->user()->id,
                    'module' => 'Customer',
                    'description' => auth()->user()->name . " removed Customer Preference Tag for this '{$customerTag->servicePreferenceDetail->name}' of customer '{$customerDetail->name}' named ",
                ]);
                return response()->json(['data' => '', 'message' => '', 'status' => 1], 200);
            }
            return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function customerAjaxSearch(Request $request) {
        $column_name = $request->selectedTextVal;
        $search = $request->global_search;
        if ($column_name === 'order_id') {
            return response()->json(['data' => ""], 200);
        }
    
        if ($column_name === 'mobile_number') {
            $customerDetail = Customer::where('mobile_number', 'like', '%' . $search . '%')
                ->orWhereHas('getAlternativeNumber', function($query) use ($search) {
                    $query->where('cust_alt_num', 'like', '%' . $search . '%');
                })
                ->get();
        } else {
            $customerDetail = Customer::where($column_name, 'like', '%' . $search . '%')->get();
        }
    
        if ($customerDetail) {
            return response()->json(['data' => $customerDetail], 200);
        }
    
        return response()->json(['data' => []], 404);
    }

    public function pincodeDetails(Request $request){
        $pincode = $request->pincode;
        $village = Village::where('pincode', $pincode)->get();
        if ($village) {
            return response()->json(['data' => $village], 200);
        }
    
        return response()->json(['data' => []], 404);
        // dd($pincodeData);
    }
    public function checkNumber(Request $request){
        $number = $request->alt_num;
        $customer = Customer::where('mobile_number', $number)->orWhereHas('getAlternativeNumber', function($query) use ($number) {
            $query->where('cust_alt_num', $number);
        });
        if ($customer->exists()) {
            return response()->json(['data' => $customer->get()], 200);
        }
        return response()->json(['data' => []], 404);
    }
    public function checkNumberLead(Request $request){
        $number = $request->alt_num;
        $customer = Customer::where('mobile_number', $number)->orWhereHas('getAlternativeNumber', function($query) use ($number) {
            $query->where('cust_alt_num', $number);
        })->first();
        if ($customer) {
            $leadExist = Lead::where('customer_id', $customer->id)->exists();
            if ($leadExist) {
                return response()->json(['data' => $customer->get()], 200);
            }
            return response()->json(['data' => []], 404);
        }
        return response()->json(['data' => []], 404);
    }
    
}
