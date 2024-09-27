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
use App\Models\CustAlternateNumber;
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
        $this->customer = resolve(Customer::class)->with('getAlternativeNumber','moduleModifiedLog', 'moduleModifiedLog.userDetail', 'servicePreferenceTagDetail', 'servicePreferenceTagDetail.userDetail', 'servicePreferenceTagDetail.servicePreferenceDetail');
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
        return view('admin.customer.create', compact('countryList', 'customerId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCustomerRequest $request)
    {
        $lastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'SA-CUS-' . substr("00000{$lastId}", -6);
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->customer_id = $customerId;
        $customer->cust_age = $request->cust_age;
        $customer->cust_height = $request->cust_height;
        $customer->cust_weight = $request->cust_weight;
        $customer->wa_exist = $request->wa_exist;
        $customer->cust_disease = $request->cust_disease;
        $customer->mobile_number = $request->mobile_number;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->pin_code = $request->pin_code;
        $customer->created_by = Auth()->user()->id;
        $customer->role_id = 3;

        $insert = $customer->save();
        if(isset($request->cust_alt_num)){
            foreach($request->cust_alt_num as $altNum){
                if($altNum !== null){
                    $cust_alt_num = new CustAlternateNumber();
                    $cust_alt_num->customer_id = $customer->id;
                    $cust_alt_num->cust_alt_num = $altNum;
                    $cust_alt_num->save();
                }
            }
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
        $countryList = Country::get();
        return view('admin.customer.edit', compact('customer', 'countryList', 'alternateNumbers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCustomerRequest $request, Customer $customer)
    {
        // dd($request->all());
        $id = $request->id;
        $customer = $this->customer->findOrFail($id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->mobile_number = $request->mobile_number;
        $customer->cust_age = $request->cust_age;
        $customer->cust_height = $request->cust_height;
        $customer->cust_weight = $request->cust_weight;
        $customer->wa_exist = $request->wa_exist;
        $customer->cust_disease = $request->cust_disease;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->pin_code = $request->pin_code;
        $customer->created_by = Auth()->user()->id;
        $customer->role_id = 3;

        $update = $customer->save();

        if ($request->has('alt_num_ids')){
            foreach($request->alt_num_ids as $key => $id){
                $cust_alt_num = CustAlternateNumber::find($id);
                $cust_alt_num->cust_alt_num = $request->cust_alt_num[$key];
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
                        ]);
                    }
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

    public function departmentCustomer(Request $request)
    {
        $customer = $this->customer->where('insurance', $request->type)->get();
        return response()->json(['status' => 1, 'data' => $customer], 200);
    }

    public function getCustomerDetail(Request $request)
    {
        $customer = $this->customer->with('cityDetail', 'stateDetail', 'countryDetail', 'leadDetail', 'leadDetail.userDetail')->find($request->id);
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

    public function customerAjaxSearch(Request $request){
        $column_name = $request->selectedTextVal;
        $search = $request->global_search;
        if($column_name !== 'order_id'){
            $customerDetail = Customer::where($column_name, 'like', '%' . $search . '%')->get();
            if($customerDetail){
                return response()->json(['data' => $customerDetail], 200);
            }
        }else{
            $customerDetail = "";
            return response()->json(['data' => $customerDetail], 200);
        }
    }
}
