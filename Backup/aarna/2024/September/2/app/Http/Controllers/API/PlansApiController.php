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
use App\Models\Plan;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;

class PlansApiController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function Plans(Request $request){
        $plans = Plan::orderBy('id','Desc')->get();
        $array_push = array();
        if(!blank($plans)){
            foreach($plans as $plan){
                $array = array();
                $array['id']            = $plan->id;
                $array['name']          = ($plan->name != NULL)?$plan->name:"";
                $array['company']       = ($plan->company != NULL)?(int)$plan->company:"";
                $array['description']   = ($plan->description != NULL)?$plan->description:"";
                $array['created_at']    = ($plan->created_at != NULL)?$plan->created_at:"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'plans'=>$array_push
            ],200);
        }else{
             return response()->json(['status'=>0,'error'=> 'Health Plans Not Found.'],200);
        }
    }
    public function storePlan(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                => 'required|unique:plans,name',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $plan = new Plan();
            $plan->name         = $request->name;
            $plan->company      = $request->company;
            $plan->description  = $request->description;
            $plan->status       = 1;
            $plan->save();

            if($plan){
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Health Plans';
                $log->log       = 'Health Plan ('.$request->name.') Created.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Health Plan added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
            }
        }
    }
    public function deletePlan(Request $request, $id){
        if(!blank($id)){
            $plan = Plan::where('id',$id)->first();
            if(!blank($plan)){
                $user = User::first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Health Plans';
                $log->log       = 'Health Plan ('.$plan->name.') Deleted.';
                $log->save();
                $plan->delete();
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
            }
            return response()->json(['status'=>1,'message'=>'Health Plan deleted successfully!'],200);
        }else{
           return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
        }
    }
    public function updatePlan(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                => 'required|unique:plans,name,'.$request->plan_id,
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $plan = Plan::where('id',$request->plan_id)->first();
            if(!blank($plan)){
                $plan->name         = $request->name;
                $plan->company      = $request->company;
                $plan->description  = $request->description;
                $plan->status       = $request->status;
                $plan->save();

                if($plan){
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Health Plans';
                    $log->log       = 'Health Plan ('.$request->name.') Updated.';
                    $log->save();
                    return response()->json(['status'=>1,'message'=>'Health Plan updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
            }
        }
    }
}
