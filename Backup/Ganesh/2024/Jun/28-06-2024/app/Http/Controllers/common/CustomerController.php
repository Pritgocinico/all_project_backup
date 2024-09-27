<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\Log;
use App\Models\User;
use App\Models\Setting;
use App\Models\Project;
use App\Models\RoleUser;
use App\Models\City;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Nnjeim\World\World;
use Nnjeim\World\WorldHelper;
use App\Models\Customer;
use Notification;
use App\Notifications\OffersNotification;
use App\Http\Helpers\SmsHelper;

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
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);
        
        if ($state_action->success) {
            $states = $state_action->data;
        }

        $cities = City::where('state_id', 1650)->get();

        $page = 'Customers';
        $icon = 'customer.png';
        $customer = Customer::orderBy('id', 'DESC')->get();
        $projectCount = Customer::all()->last()?Customer::all()->last()->id:1;
        $projectCount++;
        $projectIdPadding = str_pad($projectCount, 5, '0', STR_PAD_LEFT);
        $customerId = 'SGA_CUS' . '_' . $projectIdPadding;
        return view('admin.customers.customers',compact('page','icon','customer','states','cities','customerId'));
    }
    public function addCustomer( Request $request){
       
        $page = 'Customers';
        $icon = 'customer.png';
        $customer = Customer::all();
        return view('admin.customers.add_customer',compact('page','icon','customer'));
    }
    public function addCustomerData(WorldHelper $world ,Request $request, $id = NULL){
        // print_r($request->all());exit;
        $request->validate([
            'name'          => 'required',
            'city'          => 'required',
            'state'          => 'required',
        ]);

        $this->world = $world;
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);
        
        if ($state_action->success) {
            $customer_states = $state_action->data;
        }
        $projectCount = DB::table('customers')->get()->last()?DB::table('customers')->get()->last()->id +1:1;
        $projectIdPadding = str_pad($projectCount, 5, '0', STR_PAD_LEFT);
        $customerId = 'SGA_CUS' . '_' . $projectIdPadding;
        $customer = new Customer();
        $customer->name         = $request->name;
        $customer->phone        = $request->phone;
        $customer->email        = $request->email;
        $customer->address      = $request->address;
        $customer->role      = 9;
        $customer->app_user_active      = 1;
        $customer->state        = $request->state;
        $customer->password        = Hash::make($request->password);
        $customer->original_password        = $request->password;
        $customer->city         = $request->city;
        $customer->customer_id         = $customerId;
        $customer->zipcode      = $request->zipcode;
        $insert             = $customer->save();
$role = new RoleUser;
                $role->user_id = $customer->id;
                $role->role_id = 9;
                $ins = $role->save();
        $customer_cities = City::where('state_id', $customer->state)->get();

        $user = User::where('id',Auth::user()->id)->first();


        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Customer';
        $log->log       = 'Customer Added';
        $log->save();

        if($insert){
        $notificationData = [
            'type' => 'message',
            'title' => 'New Customer Added - ',
            'text' => 'Name: '.$customer->name,
            'url' => route('admin.customers'),
        ];
  
        Notification::send($user, new OffersNotification($notificationData));
        
            try {
                // $mobileNumber = $request->phone;
                // $message = "Welcome " . $request->name . " to Shree Ganesh Aluminum. Your details has been captured in our system for your upcoming project.";
                // $templateid = '1407171593756486272';
                // $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $roleData = Role::firstWhere('id',9);
                $mobileNumber = $request->phone;
            $password = $request->password;
            $message = "Your user id of " . $roleData->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $customerId . " , Password: " . $password;
            $templateid = '1407171593745579639';
            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                
            } catch (Exception $e) {
                echo 'Message: ' . $e->getMessage();
            }

            if(!blank($id) && $id != ''){
                return response()->json([
                    'customer' => $customer,
                    'customer_states' => $customer_states,
                    'customer_cities' => $customer_cities,
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
        $customer = Customer::where('id', $id)->first();
        $this->world = $world;
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);
        
        if ($state_action->success) {
            $states = $state_action->data;
        }

        $editcities = City::where('state_id', $customer->statename)->get();
        
        $page = 'Customers';
        $icon = 'customer.png';
        return view('admin.customers.edit_customer', compact('page', 'icon', 'customer','states'));
    }    
    public function updateCustomer(WorldHelper $world, Request $request, $id){
        $request->validate([
            'name'  => 'required',
            'stateedit'  => 'required',
            'cityedit'    => 'required',
            
        ]);
        $customer = Customer::find($id);
        $customer->name  = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        // $customer->role  = $request->role;
        $customer->address = $request->address;
        $customer->state  = $request->stateedit ?? '';
        $customer->city  = $request->cityedit ?? '';
        $customer->zipcode = $request->zipcode;
    
        // if ($request->has('password')) {
        //     $user->password = Hash::make($request->password);
        // }
        $customer->save();

        $user = User::where('id',Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Customer Updated - ',
            'text' => 'Name: '.$customer->name,
            'url' => route('admin.customers'),
        ];
        Notification::send($user, new OffersNotification($notificationData));
        
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Customer';
        $log->log       = 'Customer Updated';
        $log->save();

        return response()->json([
            'customer' => $customer,
            'success'=>true,
        ],200);
    
        // return redirect()->route('admin.users');
    }
    public function getCustomer(WorldHelper $world ,Request $request){
        $this->world = $world;
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);
        
        if ($state_action->success) {
            $states = $state_action->data;
        }

        $customer = Customer::where('id', $request->id)->first();
        // dd($customer);

        // Get the cities related to the customer's state
        $editcities = City::where('state_id', $customer->state)->get();

        // Combine customer data and cities data into an array
        $responseData = [
            'customer' => $customer,
            'editcities' => $editcities,
            'states' => $states
        ];

        return response()->json($responseData, 200);
    }
    public function deletecustomer($id)
    {
        $customer = Customer::find($id);
        // $query = $customer->delete();
        $query =  Project::where('customer_id',$id)->count();
        if ($query > 0) {
             return response()->json("error", 200);
        }else{
            $customer->delete();
            $user = User::where('id',Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Customer Deleted - ',
                'text' => 'Name: '.$customer->name,
                'url' => route('admin.customers'),
            ];
            Notification::send($user, new OffersNotification($notificationData));
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Customer';
            $log->log       = 'Customer Deleted';
            $log->save();
             return response()->json("success", 200);
        }
    }
}
