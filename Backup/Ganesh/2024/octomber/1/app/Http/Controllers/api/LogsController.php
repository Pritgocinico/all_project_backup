<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\QuotationUpload;
use App\Models\Customer;
use App\Models\WorkshopQuestion;
use App\Models\WorkshopDoneTask;
use App\Models\Project;
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

class LogsController extends Controller
{

    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }

    public function listLogs(Request $request){
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
        $logs = Log::where('user_id', $user->name)->orderBy('id', 'DESC')->get();
        $array_push = array();
        if(!blank($logs)){
            foreach($logs as $log){
                $array = array();
                $array['id']            = $log->id;
                $array['created_by']    = $log->user_id;
                $array['module']        = $log->module;
                $array['log']           = $log->log;
                $array['created_at']    = date('d/m/Y',strtotime($log->created_at));
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'logs'=>$array_push
            ],200);
        }else{
            return response()->json(['status'=>0,'error'=> 'Something went wrong.'],404);
        }
    }

}
