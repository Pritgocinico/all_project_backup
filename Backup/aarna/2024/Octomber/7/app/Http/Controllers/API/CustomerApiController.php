<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Customer;
use App\Models\SourcingAgent;
use App\Models\Policy;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;

class CustomerApiController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function Customers(Request $request){
         $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            if(!blank($accessToken)){
                $currentUser = User::where('id',$accessToken->user_id)->first();
                
            if($currentUser->role == 3){
                $agent = SourcingAgent::where('user_id',$currentUser->id)->first(); 
                $policy = Policy::where('status','!=',2)->where('agent',$agent->id)->orderBy('id','Desc')->get();
                $policy_customer_ids = $policy->pluck('customer');
                $query = Customer::whereIn('id', $policy_customer_ids)->orderBy('id','Desc')->where('status',1);
            }else{
                $query = Customer::orderBy('id','Desc')->where('status',1);
            }    
           
            if($request->has('id') && $request->id != ''){
                $query->where('id',$request->id);
            }
            
        
        if($request->has('customerId')){
            $query->where('id',$request->customerId);
        }
        if($request->has('customerName')){
            $query->where('name', 'like', '%'.$request->customerName.'%');
        }
        if($request->has('customerEmail')){
            $query->where('email',$request->customerEmail);
        }
        if($request->has('customerMobile')){
            $query->where('phone',$request->customerMobile);
        }
        $customers = $query->get();
        $array_push = array();
        if(!blank($customers)){
            foreach($customers as $customer){
                $array = array();
                $array['customerId']            = $customer->id;
                $array['customerName']          = ($customer->name != NULL)?$customer->name:"";
                $array['customerEmail']         = ($customer->email != NULL)?$customer->email:"";
                $array['customerMobile']        = ($customer->phone != NULL)?$customer->phone:"";
                $array['status']                = ($customer->status != NULL)?(int)$customer->status:0;
                $array['created_at']            = ($customer->created_at != NULL)?$customer->created_at:"";
                array_push($array_push,$array);
            }
            return response()->json([
                'result'            => 200,
                'description'       => 'Customer List',
                'status'            => 1,
                'customerListJson'  => json_encode($array_push),
                'customerListCount' => count($customers)
            ],200);
        }else{
             return response()->json(['result'=>200,'description'=>'Customers Not Found.','status'=>0,'error'=> 'Customers Not Found.'],200);
        }
        }else{
             return response()->json(['result'=>200,'description'=>'Authorization Faield.','status'=>0,'error'=> 'Authorization Not Found.'],200);
        }
    }
    public function storeCustomer(Request $request){
        $validator = Validator::make($request->all(), [
            'customerName'                => 'required|unique:customers,name',
        ]);
        if ($validator->fails()) {
            return response()->json(['result'=>200,'status'=>0,'description'=>implode(" ", $validator->errors()->all()),'message'=>'error','error'=>'error'], 200);
        }else{
           
            $customer = new Customer();
            $customer->name         = $request->customerName;
            $customer->email        = $request->customerEmail;
            $customer->phone        = $request->customerPhone;
            $customer->status       = 1;
            $customer->save();

            if($customer){
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Customers';
                $log->log       = 'Customers ('.$request->name.') Created.';
                $log->save();
                return response()->json(
                    [
                        'result'        => 200,
                        'description'   => 'Customer added successfully.',
                        'status'        => 1,
                        'message'       => 'Success!'
                    ],
                    200
                );
            }else{
                return response()->json(['result'=> 200,'description'=>'Something went wrong.','status'=>0,'error'=>'Something went wrong.'],200);
            }
        }
    }
    public function deleteCustomer(Request $request, $id){
        if(!blank($id)){
            $customer = Customer::where('id',$id)->first();
            if(!blank($customer)){
                $user = User::first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Customers';
                $log->log       = 'Customer ('.$customer->name.') Deleted.';
                $log->save();
                $customer->delete();
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
            }
            return response()->json(['status'=>1,'message'=>'Customer deleted successfully!'],200);
        }else{
           return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
        }
    }
    public function updateCustomer(Request $request){
        $validator = Validator::make($request->all(), [
            'customerName'                => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $customer = Customer::where('id',$request->customer_id)->first();
            if(!blank($customer)){
                $customer->name         = $request->customerName;
                $customer->email        = $request->customerEmail;
                $customer->phone        = $request->customerPhone;
                $customer->status       = $request->status;
                $customer->save();

                if($customer){
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Customers';
                    $log->log       = 'Customer ('.$request->name.') Updated.';
                    $log->save();
                    return response()->json(['status'=>1,'message'=>'Customer updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
            }
        }
    }
}
