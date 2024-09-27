<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomerRequest;
use App\Models\Country;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    private $customer;
    public function __construct()
    {
        $this->customer = resolve(Customer::class);
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
            'mobile_number' => $request->phone,
            'gender' => $request->country,
            'birth_date' => $request->city,
            'pan_card_number' => $request->address,
            'address' => $request->zip_code,
            'city' => $request->state,
            'state' => $request->state,
            'country' => $request->state,
            'pin_code' => $request->state,
            'account_number' => $request->state,
            'account_name' => $request->state,
            'bank_name' => $request->state,
            'branch_name' => $request->state,
            'ifsc_code' => $request->state,
            'card_number' => $request->state,
            'card_type' => $request->state,
            'card_month' => $request->state,
            'card_year' => $request->state,
            'card_cvv' => $request->state,
            'created_by' => Auth()->user()->id,
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('admin.customer.create', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
