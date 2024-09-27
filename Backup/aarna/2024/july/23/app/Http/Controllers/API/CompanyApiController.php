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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;

class CompanyApiController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function Companies(Request $request){
        $companies = Company::orderBy('id','Desc')->get();
        $array_push = array();
        if(!blank($companies)){
            foreach($companies as $company){
                $array = array();
                $array['id']            = $company->id;
                $array['name']          = ($company->name != NULL)?$company->name:"";
                $array['created_at']    = ($company->created_at != NULL)?$company->created_at:"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'companies'=>$array_push
            ],200);
        }else{
             return response()->json(['status'=>0,'error'=> 'Insurance Companies Not Found.'],200);
        }
    }
    public function storeInsuranceCompany(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                => 'required|unique:companies,name',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $company = new Company();
            $company->name         = $request->name;
            $company->status       = 1;
            $company->save();

            if($company){
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Insurance Companies';
                $log->log       = 'Insurance Company ('.$request->name.') Created.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Insurance Company added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
            }
        }
    }
    public function deleteInsuranceCompany(Request $request, $id){
        if(!blank($id)){
            $company = Company::where('id',$id)->first();
            if(!blank($company)){
                $user = User::first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Insurance Company';
                $log->log       = 'Insurance Company ('.$company->name.') Deleted.';
                $log->save();
                $company->delete();
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
            }
            return response()->json(['status'=>1,'message'=>'Insurance Company deleted successfully!'],200);
        }else{
           return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
        }
    }
    public function updateInsuranceCompany(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                => 'required|unique:companies,name,'.$request->company_id,
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $company = Company::where('id',$request->company_id)->first();
            if(!blank($company)){
                $company->name         = $request->name;
                $company->status       = $request->status;
                $company->save();

                if($company){
                    $user = User::where('id',$request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Insurance Companies';
                    $log->log       = 'Insurance Company ('.$request->name.') Updated.';
                    $log->save();
                    return response()->json(['status'=>1,'message'=>'Insurance Company updated successfully.'],200);
                }else{
                    return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
            }
        }
    }
}
