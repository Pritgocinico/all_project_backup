<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Nnjeim\World\World;
use Nnjeim\World\WorldHelper;
use App\Models\Customer;

class CustomerController extends Controller
{
    protected $world;
    public function __construct() {
        $setting=Setting::first();
        $customer = Customer::first();
        view()->share('setting', $setting);
    }
    public function customers(WorldHelper $world ,Request $req){
        $this->world = $world;
        $action = $this->world->cities([
            'filters' => [
                'state_id' => 1650,
            ],
        ]);
        if ($action->success) {
            $cities = $action->data;
        }   
        $page = 'Customers';
        $icon = 'customer.png';
        $customer = Customer::where('role','!=',1)->get();
        // print_r($cities);
        // exit;
        return view('admin.customers.customers',compact('page','icon','customer','cities'));
    }
    public function addCustomer( Request $request){
       
        $page = 'Customers';
        $icon = 'customer.png';
        $customer = Customer::where('role','!=',1)->get();
        return view('admin.customers.add_customer',compact('page','icon','customer'));
    }
    public function addCustomerData(Request $request, $id = NULL){
        // print_r($request->all());exit;
        $request->validate([
            'name'          => 'required',
            'city'          => 'required',
        ]);
        
        $customer = new Customer();
        $customer->name         = $request->name;
        $customer->phone        = $request->phone;
        $customer->email        = $request->email;
        $customer->address      = $request->address;
        $customer->city         = $request->city;
        $customer->zipcode      = $request->zipcode;
        $customer->status       = 1;
        $insert             = $customer->save();

        if($insert){
            if(!blank($id) && $id != ''){
                return response()->json([
                    'customer' => $customer,
                    'success'=>true,
                ],200);
            }else{
                return redirect()->route('admin.customers');
            }
        }else{
            Session::flash('alert','Something Went Wrong.');
            return redirect()->route('admin.add_customer');
        }
    }
    public function editCustomer(WorldHelper $world, Request $request, $id = NULL){
        $this->world = $world;
        $action = $this->world->cities([
            'filters' => [
                'state_id' => 1650,
            ],
        ]);
        if ($action->success) {
            $cities = $action->data;
        }   
        
        $page = 'Customers';
        $icon = 'customer.png';
        $customer = Customer::where('id', $id)->first();
        return view('admin.customers.edit_customer', compact('page', 'icon', 'customer','cities'));
    }    
    public function updateCustomer(WorldHelper $world, Request $request, $id){
        $request->validate([
            'name'  => 'required',
            'city'    => 'required',
        ]);

        $customer = Customer::find($id);
        $customer->name  = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->role  = $request->role;
        $customer->address = $request->address;
        $customer->city  = $request->city;
        $customer->zipcode = $request->zipcode;
    
        // if ($request->has('password')) {
        //     $user->password = Hash::make($request->password);
        // }
        $customer->save();

        return response()->json([
            'customer' => $customer,
            'success'=>true,
        ],200);
    
        // return redirect()->route('admin.users');
    }
    public function getCustomer(WorldHelper $world ,Request $request){
        $this->world = $world;
       
        $customer = Customer::where('id',$request->id)->first();
        $action = $this->world->cities([
            'filters' => [
                'state_id' => 1650,
            ],
        ]);
        if ($action->success) {
            $cities = $action->data;
        }   
        return response()->json($customer, 200);
    }
    public function deletecustomer($id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        return response()->json("success", 200);
    }
}
