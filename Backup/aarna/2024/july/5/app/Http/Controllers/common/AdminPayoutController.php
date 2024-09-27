<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Category;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Policy;
use App\Models\SourcingAgent;
use App\Models\PayoutList;
use App\Models\PayoutListRecord;
use App\Models\PolicyParameter;
use App\Models\AdminPayout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\CustomerPayoutCsvExport;
use App\Exports\CustomerPayoutPDFExport;
use App\Exports\PayoutPolicyExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use PDF;

class AdminPayoutController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function adminPayout(Request $request){
        $page  = 'Admin Payout';
        $icon  = 'staff.png';
        $companies = Company::where('status',1)->get();
        $sub_categories = Category::where('parent','!=',0)->orderBy('id','Desc')->get();
        $payouts_data = AdminPayout::with('companies')->get();
        $payouts = [];
        if(!blank($payouts_data)){
            foreach($payouts_data as $data){
                $payouts[$data->companies->name][] = $data;
                $payouts[$data->companies->name]['companies'] = $data->companies;
            }
        }
        return view('admin.admin_payout',compact('page','icon','companies','sub_categories','payouts'));
    }
    public function updateAdminPayout(Request $request){
            // echo '<pre>';
            // print_r($request->all());
            // exit;
        if($request->has('company_category')){
            foreach($request->company_category as $key=>$cate){
                $cnt = 0;
                foreach($cate['category'] as $cat_key=>$cat){
                    foreach($cate['payout'] as $payout_key=>$payout){
                        if($cat_key == $payout_key){
                            $hidden_id = 0;
                            if(isset($cate['id'])){
                                foreach($cate['id'] as $key_id=>$h_id){
                                    if($cat_key == $key_id){
                                        $cnt++;
                                        $hidden_id = $h_id;
                                    }
                                }
                            }
                            if($cnt == 0){
                                    $agent_payout = new AdminPayout();
                            }else{
                                if($hidden_id != 0){
                                    $agent_payout = AdminPayout::where('id',$hidden_id)->first();
                                }else{
                                    $agent_payout = new AdminPayout();
                                }
                            }
                            $agent_payout->company  = $key;
                            $agent_payout->category = $cat;
                            foreach($cate['type'] as $type_key=>$type){
                                if($type_key == $cat_key){
                                    $agent_payout->type = $type;
                                }
                            }
                            $agent_payout->value = $payout;
                            $agent_payout->created_by = Auth::user()->id;
                            $agent_payout->save();
                        }
                    }
                }
            }
        }

        return redirect()->back();
    }
    public function deleteAdminPayoutRecord(Request $request,$id){
        $payout = AdminPayout::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Admin Payout';
        $log->log       = 'Admin payout has been deleted';
        $log->save();
        $payout->delete();
        return 1;
    }
}
