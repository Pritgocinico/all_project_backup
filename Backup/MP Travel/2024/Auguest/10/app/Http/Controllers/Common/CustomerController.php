<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomerRequest;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Dompdf\Dompdf;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    private $customer;
    public function __construct()
    {
        $page = "Customer";
        $this->customer = resolve(Customer::class);
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type = "0";
        $customerList = $this->customer->latest()->paginate(10);
        return view('admin.customer.index', compact('customerList','type'));
    }

    public function generalInsuranceCustomer(Request $request){
        $type = "1";
        return view('admin.customer.general_insurance_customer', compact('type'));
    }
    public function travelCustomer(Request $request){
        $type = "2";
        return view('admin.customer.travel_customer', compact('type'));
    }

    public function customerAjaxList(Request $request){
        $search = $request->search;
        $type = $request->type;
        $customerList = $this->customer
        ->when($type || $type == 0,function($query) use($type){
            $query->where('insurance_type',$type);
        })
        ->when(Auth()->user()->role_id == 2,function($query){
            $query->where('created_by',Auth()->user()->id);
        })
        ->when($search, function($query, $search) {
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
        ->paginate(10);
        return view('admin.customer.ajax_list',compact('customerList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        $type = $request->type;
        $customerList = $this->customer
        ->when($type || $type == 0,function($query) use($type){
            $query->where('insurance_type',$type);
        })
        ->when(Auth()->user()->role_id == 2,function($query){
            $query->where('created_by',Auth()->user()->id);
        })
        ->when($search, function($query, $search) {
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

            $columns = array('Customer Name', 'Email', 'Phone Number', 'Role Name','Created By', 'Created At');
            $callback = function () use ($customerList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($customerList as $customer) {
                    $date = "";
                    if (isset($customer->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($customer->created_at);
                    }
                    $role = isset($customer->roleDetail) ? ucfirst($customer->roleDetail->name) : '';
                    $name = isset($customer->userDetail) ? ucfirst($customer->userDetail->name) : '';
                    fputcsv($file, array($customer->name, $customer->email, $customer->mobile_number, $role, $name,$date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $mainLogoUrl = asset('storage/logo/UQcj8rCZqMmTVJ89a4zx0Vzz7UZ5AtbbWazxf2cd.png');
            $html = view('admin.pdf.customer', ['customerList' => $customerList, 'mainLogoUrl' => $mainLogoUrl])->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response()->streamDownload(
                function () use ($dompdf) {
                    echo $dompdf->output();
                },
                'Customer.pdf'
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countryList = Country::get();
        $lastId =  Customer::all()->last()?Customer::all()->last()->id+1:1;
        $customerId = 'MP-CUS-' . substr("00000{$lastId}", -6);
        return view('admin.customer.create',compact('countryList','customerId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCustomerRequest $request)
    {
        $customer = new Customer();
        $customer->insurance = $request->insurance;
        $customer->insurance_type = $request->insurance_type;
        $customer->customer_department = $request->customer_department;
        $customer->name = $request->name;
        $customer->customer_id = $request->customer_id;
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
        $customer->created_by = Auth()->user()->id;
        $customer->role_id = 3;
        
        if($request->hasFile('gst_certificate')){
            $file = $request->file('gst_certificate');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $gstCertificatePath = $file->storeAs('customer/gst_certificate', $newFilename, 'public');
            $customer->gst_certificate = $gstCertificatePath;
        }
        if($request->hasFile('aadhar_card_file')){
            $file = $request->file('aadhar_card_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $aadharCardFilePath = $file->storeAs('customer/aadhar_card', $newFilename, 'public');
            $customer->aadhar_card_file = $aadharCardFilePath;
        }
        if($request->hasFile('pan_card_file')){
            $file = $request->file('pan_card_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $panCardPath = $file->storeAs('customer/pan_card', $newFilename, 'public');
            $customer->pan_card_file = $panCardPath;
        }
        $insert = $customer->save();
        if($insert){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Customer',
                'description' => auth()->user()->name . " created a Customer named '" . $request->name . "'"
            ]);
            if($request->insurance == "1"){
                return redirect()->route('general-insurance-customer')->with('success', 'Customer has been created successfully.');
            } else if($request->insurance == "2"){
                return redirect()->route('travel-customer')->with('success', 'Customer has been created successfully.');
            }
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
        return view('admin.customer.show',compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $id = $customer->id;
        $customer = $this->customer->find($id);
        $countryList = Country::get();
        return view('admin.customer.create', compact('customer','countryList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $id = $request->id;
        $customer = $this->customer->findOrFail($id);
        $data = [
            'insurance' => $request->insurance,
            'insurance_type' => $request->insurance_type,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'original_password' => $request->password,
            'mobile_number' => $request->mobile_number,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'pan_card_number' => $request->pan_card_number,
            'aadhaar_number' => $request->aadhaar_number,
            'service_preference' => $request->service_preference,
            'reference' => $request->reference,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pin_code' => $request->pin_code,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'ifsc_code' => $request->ifsc_code,
            'card_number' => $request->card_number,
            'card_name' => $request->card_name,
            'card_month' => $request->card_month,
            'card_year' => $request->card_year,
            'card_cvv' => $request->card_cvv,
            'role_id' => 3,
            'updated_by' => Auth()->user()->id,
        ];
        $update = $customer->update($data);
        if($update){
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
    public function departmentCustomer(Request $request){
        $customer = $this->customer->where('insurance',$request->type)->get();
        return response()->json(['status' => 1, 'data' => $customer],200);
    }

    public function getCustomerDetail(Request $request){
        $customer = $this->customer->with('cityDetail','stateDetail','countryDetail','leadDetail','leadDetail.userDetail')->find($request->id);
        return response()->json(['status' => 1, 'data' => $customer],200);
    }
}
