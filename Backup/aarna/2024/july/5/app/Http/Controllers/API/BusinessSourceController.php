<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Company;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\BusinessSource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;

class BusinessSourceController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function getBusinessSourceList(Request $request){
        if($request->has('id')){
            $source = BusinessSource::where('id',$request->id)->orderBy('id','Desc')->first();
            if(!blank($source)){
                $array = array();
                $array['id']                        = $source->id;
                $array['insurance_type']            = ($source->insurance_type != NULL)?$source->insurance_type:"";
                $array['category']                  = ($source->category != NULL)?(int)$source->category:"";
                $category = Category::where('id',$source->category)->first();
                $array['category_name']             = $category->name;
                $array['business_type']             = ($source->business_type != NULL)?$source->business_type:"";
                $array['pyp_no']                    = ($source->pyp_no != NULL)?$source->pyp_no:"";
                $array['pyp_insurance_company']     = ($source->pyp_insurance_company != NULL)?$source->pyp_insurance_company:"";
                $array['pyp_expiry_date']           = ($source->pyp_expiry_date != NULL)?$source->pyp_expiry_date:"";
                $array['customer']                  = ($source->customer != NULL)?$source->customer:"";
                $customer = Customer::where('id',$source->customer)->first();
                if(!blank($customer)){
                    $array['customer_name']             = $customer->name;
                }
                $array['sub_category']              = ($source->sub_category != NULL)?$source->sub_category:"";
                $sub_category = Category::where('id',$source->sub_category)->first();
                if(!blank($sub_category)){
                    $array['sub_category_name']         = $sub_category->name;
                }
                $array['company']                   = ($source->company != NULL)?$source->company:"";
                $company = Company::where('id',$source->company)->first();
                if(!blank($company)){
                    $array['company_name']          = $company->name;
                }
                $array['risk_start_date']           = ($source->risk_start_date != NULL)?$source->risk_start_date:"";
                $array['vehicle_chassic_no']        = ($source->vehicle_chassic_no != NULL)?$source->vehicle_chassic_no:"";
                $array['gross_premium_amount']      = ($source->gross_premium_amount != NULL)?$source->gross_premium_amount:"";
                $array['net_premium_amount']        = ($source->net_premium_amount != NULL)?$source->net_premium_amount:"";
                $array['health_plan']               = ($source->health_plan != NULL)?$source->health_plan:"";
                $plan = Plan::where('id',$source->health_plan)->first();
                if(!blank($plan)){
                    $array['health_plan_name']      = $plan->name;
                }
                $array['status']                    = ($source->status != NULL)?$source->status:0;
                $array['created_at']                = ($source->created_at != NULL)?$source->created_at:"";
                return response()->json([
                    'status' => 1,
                    'business_source'=>$array,
                    'result'        => 200,
                    'description'   => 'Business Source',
                    'message'       => 'Success!'
                ],200);
            }else{
                return response()->json([
                    'status'=>0,'error'=> 'Business Source Not Found.'],200);
            } 
        }else{
            $business_source = BusinessSource::orderBy('id','Desc')->get();
            $array_push = array();
            if(!blank($business_source)){
                foreach($business_source as $source){
                    $array = array();
                    $array['id']                        = $source->id;
                $array['insurance_type']            = ($source->insurance_type != NULL)?$source->insurance_type:"";
                $array['category']                  = ($source->category != NULL)?(int)$source->category:"";
                $category = Category::where('id',$source->category)->first();
                if(!blank($category)){
                    $array['category_name']             = $category->name;
                }
                $array['business_type']             = ($source->business_type != NULL)?$source->business_type:"";
                $array['pyp_no']                    = ($source->pyp_no != NULL)?$source->pyp_no:"";
                $array['pyp_insurance_company']     = ($source->pyp_insurance_company != NULL)?$source->pyp_insurance_company:"";
                $array['pyp_expiry_date']           = ($source->pyp_expiry_date != NULL)?$source->pyp_expiry_date:"";
                $array['customer']                  = ($source->customer != NULL)?$source->customer:"";
                $customer = Customer::where('id',$source->customer)->first();
                if(!blank($customer)){
                    $array['customer_name']             = $customer->name;
                }
                $array['sub_category']              = ($source->sub_category != NULL)?$source->sub_category:"";
                $sub_category = Category::where('id',$source->sub_category)->first();
                if(!blank($sub_category)){
                    $array['sub_category_name']         = $sub_category->name;
                }
                $array['company']                   = ($source->company != NULL)?$source->company:"";
                $company = Company::where('id',$source->company)->first();
                if(!blank($company)){
                    $array['company_name']          = $company->name;
                }
                $array['risk_start_date']           = ($source->risk_start_date != NULL)?$source->risk_start_date:"";
                $array['vehicle_chassic_no']        = ($source->vehicle_chassic_no != NULL)?$source->vehicle_chassic_no:"";
                $array['gross_premium_amount']      = ($source->gross_premium_amount != NULL)?$source->gross_premium_amount:"";
                $array['net_premium_amount']        = ($source->net_premium_amount != NULL)?$source->net_premium_amount:"";
                $array['health_plan']               = ($source->health_plan != NULL)?$source->health_plan:"";
                $plan = Plan::where('id',$source->health_plan)->first();
                if(!blank($plan)){
                    $array['health_plan_name']      = $plan->name;
                }
                $array['status']                    = ($source->status != NULL)?$source->status:0;
                $array['created_at']                = ($source->created_at != NULL)?$source->created_at:"";
                    array_push($array_push,$array);
                }
                return response()->json([
                    'status' => 1,
                    'business_source'=>$array_push
                ],200);
            }else{
                return response()->json(['result'=>200,'status'=>0,'description'=>'Something went wrong.','error'=> 'Business Source Not Found.'],200);
            } 
        }
    }
    public function addBusinessSource(Request $request){
        if($request->has('insurance_type') && $request->insurance_type == 1){
            $validator = Validator::make($request->all(), [
                'customer_id'                   => 'required|not_in:0',
                'vehicle_chassis_no'            => 'required',
                'gross_premium_amount'          => 'required',
                'net_premium_amount'            => 'required',
                'risk_start_date'               => 'required',
                'pyp_no'                        => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_insurance_company'         => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_expiry_date'               => 'required_if:business_type,2|required_if:business_type,3',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'customer_id'                   => 'required|not_in:0',
                'health_plan'                   => 'required|not_in:0',
                'gross_premium_amount'          => 'required',
                'net_premium_amount'            => 'required',
                'risk_start_date'               => 'required',
                'pyp_no'                        => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_insurance_company'         => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_expiry_date'               => 'required_if:business_type,2|required_if:business_type,3',
            ]);
        }
        if ($validator->fails()) {
            return response()->json(['result'=>200,'status'=>0,'description'=>'error','message'=>'error','error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            if($request->insurance_type == 1){
                $category               = $request->category;
                $sub_category           = $request->subcategory;
                $insurance_company      = $request->insurance_company;
                $customer               = $request->customer_id;
                $business_type          = $request->business_type;
                $risk_start_date        = date("Y-m-d", strtotime($request->risk_start_date));
                $gross_premium_amount   = $request->gross_premium_amount;
                $net_premium_amount     = $request->net_premium_amount;
                $pyp_no                 = $request->pyp_no;
                $pyp_insurance_company  = $request->pyp_insurance_company;
                $pyp_expiry_date        = date("Y-m-d", strtotime($request->pyp_expiry_date));
            }else{
                $category               = $request->category;
                $sub_category           = 0;
                $insurance_company      = $request->insurance_company;
                $customer               = $request->customer_id;
                $business_type          = $request->business_type;
                $risk_start_date        = date("Y-m-d", strtotime($request->risk_start_date));
                $gross_premium_amount   = $request->gross_premium_amount;
                $net_premium_amount     = $request->net_premium_amount;
                $pyp_no                 = $request->pyp_no;
                $pyp_insurance_company  = $request->pyp_insurance_company;
                $pyp_expiry_date        = date("Y-m-d", strtotime($request->pyp_expiry_date));
            }
            $source = new BusinessSource();
            $source->insurance_type         = $request->insurance_type;
            $source->category               = $category;
            $source->business_type          = $business_type;
            $source->customer               = $customer;
            $source->sub_category           = $sub_category;
            $source->company                = $insurance_company;
            $source->risk_start_date        = $risk_start_date;
            if($request->insurance_type == 1){
                $source->vehicle_chassic_no     = $request->vehicle_chassis_no;
            }
            $source->gross_premium_amount   = $gross_premium_amount;
            $source->net_premium_amount     = $net_premium_amount;
            if($request->insurance_type == 2){
                $source->health_plan            = $request->health_plan;
            }
            $source->pyp_no                 = $pyp_no;
            $source->pyp_insurance_company  = $pyp_insurance_company;
            $source->pyp_expiry_date        = $pyp_expiry_date;
            $source->status                 = 1;
            $source->save();
            if($source){
                $user1 = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user1->name;
                $log->module    = 'Business Source';
                $log->log       = 'Business Source Created';
                $log->save();
                return response()->json(
                    [
                        'result'        => 200,
                        'description'   => 'Business Source added successfully.',
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
    public function updateBusinessSource(Request $request){
        if($request->has('insurance_type') && $request->insurance_type == 1){
            $validator = Validator::make($request->all(), [
                'customer_id'                   => 'required|not_in:0',
                'vehicle_chassis_no'            => 'required',
                'gross_premium_amount'          => 'required',
                'net_premium_amount'            => 'required',
                'risk_start_date'               => 'required',
                'pyp_no'                        => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_insurance_company'         => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_expiry_date'               => 'required_if:business_type,2|required_if:business_type,3',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'customer_id'                   => 'required|not_in:0',
                'health_plan'                   => 'required|not_in:0',
                'gross_premium_amount'          => 'required',
                'net_premium_amount'            => 'required',
                'risk_start_date'               => 'required',
                'pyp_no'                        => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_insurance_company'         => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_expiry_date'               => 'required_if:business_type,2|required_if:business_type,3',
            ]);
        }
        if ($validator->fails()) {
            return response()->json(['result'=>200,'status'=>0,'description'=>'error','message'=>'error','error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            if($request->insurance_type == 1){
                $category               = $request->category;
                $sub_category           = $request->subcategory;
                $insurance_company      = $request->insurance_company;
                $customer               = $request->customer_id;
                $business_type          = $request->business_type;
                $risk_start_date        = date("Y-m-d", strtotime($request->risk_start_date));
                $gross_premium_amount   = $request->gross_premium_amount;
                $net_premium_amount     = $request->net_premium_amount;
                $pyp_no                 = $request->pyp_no;
                $pyp_insurance_company  = $request->pyp_insurance_company;
                if(!blank($request->pyp_expiry_date)){
                    $pyp_expiry_date        = date("Y-m-d", strtotime($request->pyp_expiry_date));
                }else{
                    $pyp_expiry_date        = NULL;
                }
            }else{
                $category               = $request->category;
                $sub_category           = 0;
                $insurance_company      = $request->insurance_company;
                $customer               = $request->customer_id;
                $business_type          = $request->business_type;
                $risk_start_date        = date("Y-m-d", strtotime($request->risk_start_date));
                $gross_premium_amount   = $request->gross_premium_amount;
                $net_premium_amount     = $request->net_premium_amount;
                $pyp_no                 = $request->pyp_no;
                $pyp_insurance_company  = $request->pyp_insurance_company;
                if(!blank($request->pyp_expiry_date)){
                    $pyp_expiry_date        = date("Y-m-d", strtotime($request->pyp_expiry_date));
                }else{
                    $pyp_expiry_date        = NULL;
                }
            }
            $source = BusinessSource::where('id',$request->business_source_id)->first();
            if(!blank($source)){
                $source->insurance_type         = $request->insurance_type;
                $source->category               = $category;
                $source->business_type          = $business_type;
                $source->customer               = $customer;
                $source->sub_category           = $sub_category;
                $source->company                = $insurance_company;
                $source->risk_start_date        = $risk_start_date;
                if($request->insurance_type == 1){
                    $source->vehicle_chassic_no     = $request->vehicle_chassis_no;
                }
                $source->gross_premium_amount   = $gross_premium_amount;
                $source->net_premium_amount     = $net_premium_amount;
                if($request->insurance_type == 2){
                    $source->health_plan            = $request->health_plan;
                }
                $source->pyp_no                 = $pyp_no;
                $source->pyp_insurance_company  = $pyp_insurance_company;
                $source->pyp_expiry_date        = $pyp_expiry_date;
                $source->status                 = 1;
                $source->save();
                if($source){
                    if($request->has('user_id')){
                        $user1 = User::where('id',$request->user_id)->first();
                        if(!blank($user1)){
                            $log = new Log();
                            $log->user_id   = $user1->name;
                            $log->module    = 'Business Source';
                            $log->log       = 'Business Source Updated';
                            $log->save();
                        }
                        return response()->json(
                            [
                                'result'        => 200,
                                'description'   => 'Business Source updated successfully.',
                                'status'        => 1,
                                'message'       => 'Success!'
                            ],
                            200
                        );
                    }
                }else{
                    return response()->json(['result'=> 200,'description'=>'Something went wrong.','status'=>0,'error'=>'Something went wrong.'],200);
                }
            }else{
                return response()->json(['result'=> 200,'description'=>'Business source not found.','status'=>0,'error'=>'Business source not found.'],200);
            }
        }
    }
    public function deleteBusinessSource(Request $request, $id = NULL){
        if(!blank($id)){
            $business_source = BusinessSource::where('id',$id)->first();
            if(!blank($business_source)){
                if($request->has('user_id')){
                    $user = User::where('id',$request->user_id)->first();
                    if(!blank($user)){
                        $log = new Log();
                        $log->user_id   = $user->name;
                        $log->module    = 'Business Source';
                        $log->log       = 'Business Source ('.$business_source->name.') Deleted.';
                        $log->save();
                    }
                }
                $business_source->delete();
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
            }
            return response()->json(['status'=>1,'message'=>'Business Source deleted successfully!'],200);
        }else{
           return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
        }
    }
}
