<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomerRequest;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $customerList = $this->customer->latest()->paginate(10);
        return view('admin.customer.index', compact('customerList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countryList = Country::get();
        return view('admin.customer.create',compact('countryList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCustomerRequest $request)
    {
        $lastId =  Customer::all()->last()?Customer::all()->last()->id+1:1;
        $customerId = 'MP-CUS-' . substr("00000{$lastId}", -6);
        $data = [
            'name' => $request->name,
            'customer_id' => $customerId,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'original_password' => $request->password,
            'mobile_number' => $request->mobile_number,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'pan_card_number' => $request->pan_card_number,
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
            'created_by' => Auth()->user()->id,
        ];

        $insert = $this->customer->create($data);
        if($insert){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Customer',
                'description' => auth()->user()->name . " created a Customer named '" . $request->name . "'"
            ]);
            return redirect()->route('customer.index')->with('success', 'Customer has been created successfully.');
        }
        return redirect()->route('customer.create')->with('success', 'Something went wrong.');
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
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'original_password' => $request->password,
            'mobile_number' => $request->mobile_number,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'pan_card_number' => $request->pan_card_number,
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
        return redirect()->route('customer.create')->with('success', 'Something went wrong.');
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
}
