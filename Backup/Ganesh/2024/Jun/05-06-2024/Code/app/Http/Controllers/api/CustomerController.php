<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Log;
use App\Models\Setting;
use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use Nnjeim\World\World;
use Nnjeim\World\WorldHelper;
use App\Http\Helpers\SmsHelper;

class CustomerController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function Customers(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
        
        $customers = Customer::orderBy('id','Desc')->get();
        $array_push = array();
        if(!blank($customers)){
            foreach($customers as $customer){
                $array = array();
                $array['id']            = $customer->id;
                $array['name']          = ($customer->name != NULL)?$customer->name:"";
                $array['email']         = ($customer->email != NULL)?$customer->email:"";
                $array['phone']         = ($customer->phone != NULL)?$customer->phone:"";
                $array['address']       = ($customer->address != NULL)?$customer->address:"";
                $array['city']          = ($customer->city != NULL)?$customer->city:"";
                $array['state']         = ($customer->state != NULL)?$customer->state:"";
                $array['zipcode']       = ($customer->zipcode != NULL)?$customer->zipcode:"";
                $array['status']        = ($customer->status != NULL)?(int)$customer->status:0;
                $array['created_at']    = ($customer->created_at != NULL)?date('d/m/Y',strtotime($customer->created_at)):"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'customers'=>$array_push
            ],200);
        }else{
            return response()->json(['status'=>0,'error'=> 'Customers Not Found.'],404);
        }
    }
    public function addCustomer(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                => 'required|unique:customers,name',
            'user_id'             => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $customer = new Customer();
            $customer->name         = $request->name;
            $customer->email        = $request->email;
            $customer->phone        = $request->phone;
            $customer->address      = $request->address;
            $customer->city         = $request->city;
            $customer->state        = $request->state;
            $customer->zipcode      = $request->zipcode;
            $customer->save();

            if($customer){
                $mobileNumber = $customer->phone;
                $message = "Welcome " . $request->name . " to Shree Ganesh Aluminum. Your details has been captured in our system for your upcoming project.";
                $templateid = '1407171593756486272';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Customers';
                $log->log       = 'Customers ('.$request->name.') Created.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Customers added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
            }
        }
    }
    public function updateCustomer(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                => 'required|unique:customers,name,'.$request->customer_id.'id',
            'user_id'             => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $customer = Customer::where('id',$request->customer_id)->first();
            $customer->name         = $request->name;
            $customer->email        = $request->email;
            $customer->phone        = $request->phone;
            $customer->address      = $request->address;
            $customer->city         = $request->city;
            $customer->state        = $request->state;
            $customer->zipcode      = $request->zipcode;
            $customer->save();

            if($customer){
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Customers';
                $log->log       = 'Customers ('.$request->name.') Updated.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Customers updated successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],404);
            }
        }
    }
    public function deletecustomer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $customer = Customer::find($id);
            if(!blank($customer)){
                $query =  Project::where('customer_id',$id)->count();
                if ($query > 0) {
                    return response()->json(["status"=>0,"error"=>"Customer not deleted."]);
                }else{
                    $customer->delete();
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Customers';
                    $log->log       = 'Customers ('.$request->name.') deleted.';
                    $log->save();
                    return response()->json(["status"=>1,"message"=>'Customer Deleted Successfully.']);
                }
            }else{
                return response()->json(["status"=>0,"error"=>"Customer not found."]);
            }
        }
    }

    public function listState(WorldHelper $world, Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            try {
                $authorization = $request->header('Authorization');
                $accessToken = AccessToken::where('access_token', $authorization)->first();
                $user = User::where('id', $accessToken->user_id)->first();
                $role = $user->role;
                $this->world = $world;
                $state_action = $this->world->states([
                    'filters' => [
                        'country_id' => 102,
                    ],
                ]);
                if ($state_action->success) {
                    return json_decode($state_action->data);
                }else{
                    return response()->json(["status"=>0,"error"=>"States not found."]);
                }    
            } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
          }
        }
    }

    public function listCity(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            try {
                $authorization = $request->header('Authorization');
                $accessToken = AccessToken::where('access_token', $authorization)->first();
                $user = User::where('id', $accessToken->user_id)->first();
                $role = $user->role;
                $cities = City::where('country_id', 102)->get();
                if(!blank($cities)){
                    return json_decode($cities);
                }else{
                    return response()->json(["status"=>0,"error"=>"Cities not found."]);
                }
            } catch (\Throwable $th) {
                return $this->errorResponse($e->getMessage(), $e->getCode());
            }
        }
    }
}
