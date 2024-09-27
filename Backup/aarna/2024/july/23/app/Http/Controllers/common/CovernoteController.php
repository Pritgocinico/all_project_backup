<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Company;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\BusinessSource;
use App\Models\SourcingAgent;
use App\Models\Covernote;
use App\Models\CovernoteAttachment;
use App\Models\CovernoteParameter;
use App\Models\Policy;
use App\Models\PolicyAttachment;
use App\Models\PolicyParameter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use URL;
use File;

class CovernoteController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function covernote(){
         if(Auth::user()->role == 1 || Auth::user()->role == 2){
            $covernotes = Covernote::orderBy('id','Desc')->get();
        }else{
            $covernotes = Covernote::where('id',Auth::user()->id)->orderBy('id','Desc')->get();  
        }
        $business_source = BusinessSource::orderBy('id','Desc')->get();
        $page  = 'Coverotes';
        $icon  = 'cover-note.png';
        $categories = Category::where('parent','==',0)->get();
        $sub_categories = Category::where('parent','!=',0)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        // if(Auth::user()->role == 1){
            return view('admin.covernote.covernotes',compact('covernotes','business_source','page','icon','categories','sub_categories','companies','customers','plans'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addCovernote(Request $request, $ids = null){
        $page = 'Add Coverotes';
        $icon = 'cover-note.png';
        $categories = Category::where('parent','==',0)->get();
        $id = $categories[0]['id'];
        $sub_categories = Category::where('parent',$id)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $business_source = BusinessSource::orderBy('id','Desc')->get(); 
        $sourcing_agents = SourcingAgent::all();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        $policy = Policy::where('id',$ids)->first();
        // if(Auth::user()->role == 1){
            return view('admin.covernote.add_covernote',compact('page', 'policy','icon','categories','sub_categories','companies','customers','plans','business_source','sourcing_agents'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addCovernoteData(Request $request){
        if($request->has('insurance_type') && $request->insurance_type == 1){
            $request->validate([
                'category'              => 'required',
                'subcategory'           => 'required',
                'insurance_company'     => 'required',
                'customer'              => 'required|not_in:0',
                'vehicle_model'         => 'required',
                'vehicle_make'          => 'required',
                'year_of_manufacture'   => 'required',
                'vehicle_chassis_no'    => 'required',
                'idv_amount'            => 'required',
                'business_type'         => 'required',
                'sourcing_agent'        => 'required|not_in:0',
                'gross_premium_amount'  => 'required',
                'net_premium_amount'    => 'required',
                'risk_start_date'       => 'required',
                'risk_end_date'         => 'required',
                'pyp_no'                => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_insurance_company' => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_expiry_date'       => 'required_if:business_type,2|required_if:business_type,3',
            ]);
        }else{
            $request->validate([
                'insurance_company'     => 'required',
                'customer'              => 'required|not_in:0',
                'health_plan'           => 'required|not_in:0',
                'health_category'       => 'required|not_in:0',
                'business_type'         => 'required',
                'sourcing_agent'        => 'required|not_in:0',
                'gross_premium_amount'  => 'required',
                'sum_insured_amount'    => 'required',
                'net_premium_amount'    => 'required',
                'risk_start_date'       => 'required',
                'risk_end_date'         => 'required',
            ]);
        }
        $policy = new Covernote();
        $policy->insurance_type          = $request->insurance_type;
        // $policy->business_source         = $request->business_source;
        if($request->has('category')){
            $policy->category                = $request->category;
        }
        if($request->has('policy_type')){
            $policy->policy_type             = $request->policy_type;
        }
        if($request->has('policy_individual_rate')){
            $policy->policy_individual_rate  = $request->policy_individual_rate;
        }
        if($request->has('category')){
            $policy->category                = $request->category;
        }
        if($request->has('subcategory')){
            $policy->sub_category            = $request->subcategory;
        }
        if($request->has('insurance_company')){
            $policy->company                 = $request->insurance_company;
        }
        if($request->has('covernote_no')){
            $policy->covernote_no            = $request->covernote_no;
        }
        $policy->policy_no               = $request->policy_no;
        $policy->customer                = $request->customer;
        if($request->has('vehicle_model')){
            $policy->vehicle_model           = $request->vehicle_model;
        }
        if($request->has('vehicle_chassis_no')){
            $policy->vehicle_chassis_no      = $request->vehicle_chassis_no;
        }
        if($request->has('health_category')){
            $policy->health_category        = $request->health_category;
        }
        if($request->has('health_plan')){
            $policy->health_plan            = $request->health_plan;
        }
        if($request->has('cc')){
            $policy->cc                      = $request->cc;
        }
        if($request->has('paid_driver')){
            $policy->paid_driver             = $request->paid_driver;
        }
        if($request->has('idv_amount')){
            $policy->idv_amount              = $request->idv_amount;
        }
        $policy->net_premium_amount         = $request->net_premium_amount;
        $policy->sum_insured_amount         = $request->sum_insured_amount;
        if($request->has('business_amount')){
            $policy->business_amount         = $request->business_amount;
        }
        if($request->has('pyp_no')){
            $policy->pyp_no                  = $request->pyp_no;
        }
        if($request->has('pyp_insurance_company')){
            $policy->pyp_insurance_company   = $request->pyp_insurance_company;
        }
        if($request->has('pyp_expiry_date')){
            $policy->pyp_expiry_date         = $request->pyp_expiry_date;
        }
        $policy->agent                       = $request->sourcing_agent;
        if($request->has('vehicle_make')){
            $policy->vehicle_make            = $request->vehicle_make;
        }
        if($request->has('vehicle_registration_no')){
            $policy->vehicle_registration_no = $request->vehicle_registration_no;
        }
        if($request->has('year_of_manufacture')){
            $policy->year_of_manufacture     = $request->year_of_manufacture;
        }
        if($request->has('seating_capacity')){
            $policy->seating_capacity        = $request->seating_capacity;
        }
        if($request->has('pa_to_passenger')){
            $policy->pa_to_passenger         = $request->pa_to_passenger;
        }
        $policy->gross_premium_amount    = $request->gross_premium_amount;
        $policy->risk_start_date         = $request->risk_start_date;
        $policy->risk_end_date           = $request->risk_end_date;
        $policy->payment_type            = $request->payment_type;
        $policy->business_type           = $request->business_type;
        if($request->has('cheque_no')){
            $policy->cheque_no               = $request->cheque_no;
        }
        if($request->has('cheque_date')){
            $policy->cheque_date             = $request->cheque_date;
        }
        if($request->has('bank_name')){
            $policy->bank_name               = $request->bank_name;
        }
        if($request->has('transaction_no')){
            $policy->transaction_no          = $request->transaction_no;
        }
        // $policy->policy_document         = $img;
        $policy->remarks                 = $request->remarks;
        $policy->created_by              = Auth::user()->id;
        $policy->status                  = 1;
        $policy->save();
        if($request->has('file')){
            foreach($request->file as $file){
                if($file && $file != null){
                    $fileName = $file->getClientOriginalName();
                    $image_file = $file;
                    $destinationPath1 = 'public/covernote_attachment/';
                    $rand1=rand(1,100);
                    $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                    $image_file->move($destinationPath1, $docImage1);
                    $img_attachment=$docImage1;
                }else{
                    $fileName = "";
                    $img_attachment='';
                }
                $attachment = new CovernoteAttachment();
                $attachment->file_name = $fileName;
                $attachment->covernote_id = $policy->id;
                $attachment->file = $img_attachment;
                $attachment->save();
            }
        }
        if($request->has('param')){
            foreach($request->param as $key=>$value){
                if($key == 'taxi'){
                    $parameter = new CovernoteParameter();
                    $parameter->covernote_id            = $policy->id;
                    $parameter->type                    = 3;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['cc']['value'];
                    $parameter->taxi_cc                 = $value['cc']['value'];
                    $parameter->seating_capacity        = $value['seating_capacity']['value'];
                    $parameter->paid_driver             = $value['paid_driver']['value'];
                    $parameter->save();
                }elseif($key == 'cc'){
                    $parameter = new CovernoteParameter();
                    $parameter->covernote_id            = $policy->id;
                    $parameter->type                    = 5;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->save();
                }elseif($key == 'pa_to_passanger'){
                    $parameter = new CovernoteParameter();
                    $parameter->covernote_id            = $policy->id;
                    $parameter->type                    = 6;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->save();
                }elseif($key == 'public'){
                    $parameter = new CovernoteParameter();
                    $parameter->type                    = 1;
                    $parameter->covernote_id            = $policy->id;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->value                   = $value['value'];
                    $parameter->save();
                }elseif($key == 'private'){
                    $parameter = new CovernoteParameter();
                    $parameter->type                    = 2;
                    $parameter->covernote_id            = $policy->id;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->value                   = $value['value'];
                    $parameter->save();
                }elseif($key == 'custom'){
                    foreach($value as $custom_key=>$custom_value){
                        $parameter = new CovernoteParameter();
                        $parameter->type                    = 7;
                        $parameter->covernote_id            = $policy->id;
                        $parameter->sub_category_id         = $request->subcategory;
                        $parameter->parameter_id            = $custom_key;
                        $parameter->value                   = $custom_value['value'];
                        $parameter->save();
                    }
                }elseif($key == 'bus'){
                    foreach($value as $bus_key=>$bus_value){
                        $parameter = new CovernoteParameter();
                        $parameter->type                    = 4;
                        $parameter->covernote_id               = $policy->id;
                        $parameter->sub_category_id         = $request->subcategory;
                        $parameter->parameter_id            = $bus_key;
                        $parameter->value                   = $bus_value['value'];
                        $parameter->save();
                    }
                }
            }
        }
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.covernote')->with('success', 'Covernote Added Successfully.');;
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function deleteCovernote($id){
        $covernote = Covernote::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Covernote';
        $log->log       = 'Covernote has been Deleted';
        $log->save();
        $covernote->delete();
        return 1;
    }
    public function editCovernote(Request $request, $id = NULL){
        $page = 'Edit Covernote';
        $icon = 'cover-note.png';
        $categories = Category::where('parent','==',0)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $business_source = BusinessSource::orderBy('id','Desc')->get();
        $sourcing_agents = SourcingAgent::all();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        $policy = Covernote::where('id',$id)->first();
        $sub_categories = Category::where('parent',$policy->category)->get();
        if(!blank($policy)){
            $parameters = CovernoteParameter::where('covernote_id',$policy->id)->get();
        }else{
            $parameters = [];
        }

        // if(Auth::user()->role == 1){
            return view('admin.covernote.edit_covernote',compact('page','icon','policy','categories','sub_categories','companies','customers','plans','business_source','sourcing_agents'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function viewCovernote(Request $request,$id = NULL){
        $page = 'View Covernote';
        $icon = 'cover-note.png';
        $policy = Covernote::where('id',$id)->first();
        $categories = Category::where('parent','==',0)->get();
        $companies = Company::all();
        $business_source = BusinessSource::orderBy('id','Desc')->get();
        $sourcing_agents = SourcingAgent::all();
        $plans = Plan::where('status',1)->where('company',$policy->company)->first();
        $attachments = CovernoteAttachment::where('covernote_id',$policy->id)->get();
        $customer = Customer::where('id',$policy->customer)->first();
        $sub_categories = Category::where('parent',$policy->category)->get();
        $parameters = PolicyParameter::where('policy_id',$policy->id)->get();
        return view('admin.covernote.view_convernote',compact('parameters','sub_categories','customer','attachments','sourcing_agents','business_source','companies','categories','policy','page','icon'));
    }
    public function editCovernoteData(Request $request){

        if($request->has('insurance_type') && $request->insurance_type == 1){
            $request->validate([
                'category'              => 'required',
                'subcategory'           => 'required',
                'insurance_company'     => 'required',
                'customer'              => 'required|not_in:0',
                'vehicle_model'         => 'required',
                'vehicle_make'          => 'required',
                'year_of_manufacture'   => 'required',
                'vehicle_chassis_no'    => 'required',
                'idv_amount'            => 'required',
                'business_type'         => 'required',
                'sourcing_agent'        => 'required|not_in:0',
                'gross_premium_amount'  => 'required',
                'net_premium_amount'    => 'required',
                'risk_start_date'       => 'required',
                'risk_end_date'         => 'required',
                'pyp_no'                => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_insurance_company' => 'required_if:business_type,2|required_if:business_type,3',
                'pyp_expiry_date'       => 'required_if:business_type,2|required_if:business_type,3',
            ]);
        }else{
            $request->validate([
                'insurance_company'     => 'required',
                'customer'              => 'required|not_in:0',
                'health_plan'           => 'required|not_in:0',
                'health_category'       => 'required|not_in:0',
                'business_type'         => 'required',
                'sourcing_agent'        => 'required|not_in:0',
                'gross_premium_amount'  => 'required',
                'sum_insured_amount'    => 'required',
                'net_premium_amount'    => 'required',
                'risk_start_date'       => 'required',
                'risk_end_date'         => 'required',
            ]);
        }
        // if($request->has('policy_document') && $request->file('policy_document') != null){
        //     $image = $request->file('policy_document');
        //     $destinationPath = 'public/policy_document/';
        //     $rand=rand(1,100);
        //     $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
        //     $image->move($destinationPath, $docImage);
        //     $img=$docImage;
        // }else{
        //     $img=$request->hidden_document;
        // }
        $policy = Covernote::where('id',$request->id)->first();
        $policy->insurance_type          = $request->insurance_type;
        // $policy->business_source         = $request->business_source;
        if($request->has('category')){
            $policy->category                = $request->category;
        }
        if($request->has('policy_type')){
            $policy->policy_type             = $request->policy_type;
        }
        if($request->has('policy_individual_rate')){
            $policy->policy_individual_rate  = $request->policy_individual_rate;
        }
        if($request->has('category')){
            $policy->category                = $request->category;
        }
        if($request->has('subcategory')){
            $policy->sub_category            = $request->subcategory;
        }
        if($request->has('insurance_company')){
            $policy->company                 = $request->insurance_company;
        }
        if($request->has('covernote_no')){
            $policy->covernote_no            = $request->covernote_no;
        }
        $policy->policy_no               = $request->policy_no;
        $policy->customer                = $request->customer;
        if($request->has('vehicle_model')){
            $policy->vehicle_model           = $request->vehicle_model;
        }
        if($request->has('vehicle_chassis_no')){
            $policy->vehicle_chassis_no      = $request->vehicle_chassis_no;
        }
        if($request->has('health_category')){
            $policy->health_category        = $request->health_category;
        }
        if($request->has('health_plan')){
            $policy->health_plan            = $request->health_plan;
        }
        if($request->has('cc')){
            $policy->cc                      = $request->cc;
        }
        if($request->has('paid_driver')){
            $policy->paid_driver             = $request->paid_driver;
        }
        if($request->has('idv_amount')){
            $policy->idv_amount              = $request->idv_amount;
        }
        $policy->net_premium_amount         = $request->net_premium_amount;
        $policy->sum_insured_amount         = $request->sum_insured_amount;
        if($request->has('business_amount')){
            $policy->business_amount         = $request->business_amount;
        }
        if($request->has('pyp_no')){
            $policy->pyp_no                  = $request->pyp_no;
        }
        if($request->has('pyp_insurance_company')){
            $policy->pyp_insurance_company   = $request->pyp_insurance_company;
        }
        if($request->has('pyp_expiry_date')){
            $policy->pyp_expiry_date         = $request->pyp_expiry_date;
        }
        $policy->agent                       = $request->sourcing_agent;
        if($request->has('vehicle_make')){
            $policy->vehicle_make            = $request->vehicle_make;
        }
        if($request->has('vehicle_registration_no')){
            $policy->vehicle_registration_no = $request->vehicle_registration_no;
        }
        if($request->has('year_of_manufacture')){
            $policy->year_of_manufacture     = $request->year_of_manufacture;
        }
        if($request->has('seating_capacity')){
            $policy->seating_capacity        = $request->seating_capacity;
        }
        if($request->has('pa_to_passenger')){
            $policy->pa_to_passenger         = $request->pa_to_passenger;
        }
        $policy->gross_premium_amount    = $request->gross_premium_amount;
        $policy->risk_start_date         = $request->risk_start_date;
        $policy->risk_end_date           = $request->risk_end_date;
        $policy->payment_type            = $request->payment_type;
        $policy->business_type           = $request->business_type;
        // $policy->policy_document         = $img;
        $policy->status                  = 1;
        if($request->has('cheque_no')){
            $policy->cheque_no               = $request->cheque_no;
        }
        if($request->has('cheque_date')){
            $policy->cheque_date             = $request->cheque_date;
        }
        if($request->has('bank_name')){
            $policy->bank_name               = $request->bank_name;
        }
        if($request->has('transaction_no')){
            $policy->transaction_no          = $request->transaction_no;
        }
        $policy->save();
        if($request->has('file')){
            foreach($request->file as $file){
                if($file && $file != null){
                    $fileName = $file->getClientOriginalName();
                    $image_file = $file;
                    $destinationPath1 = 'public/covernote_attachment/';
                    $rand1=rand(1,100);
                    $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                    $image_file->move($destinationPath1, $docImage1);
                    $img_attachment=$docImage1;
                }else{
                    $fileName = "";
                    $img_attachment='';
                }
                $attachment = new CovernoteAttachment();
                $attachment->file_name = $fileName;
                $attachment->covernote_id = $policy->id;
                $attachment->file = $img_attachment;
                $attachment->save();
            }
        }
        $parameterDelete = CovernoteParameter::where('covernote_id',$request->id)->delete();
        if($request->has('param')){
            foreach($request->param as $key=>$value){
                if($key == 'taxi'){
                    $parameter = new CovernoteParameter();
                    $parameter->covernote_id            = $policy->id;
                    $parameter->type                    = 3;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['cc']['value'];
                    $parameter->taxi_cc                 = $value['cc']['value'];
                    $parameter->seating_capacity        = $value['seating_capacity']['value'];
                    $parameter->paid_driver             = $value['paid_driver']['value'];
                    $parameter->save();
                }elseif($key == 'cc'){
                    $parameter = new CovernoteParameter();
                    $parameter->covernote_id               = $policy->id;
                    $parameter->type                    = 5;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->save();
                }elseif($key == 'pa_to_passanger'){
                    $parameter = new CovernoteParameter();
                    $parameter->covernote_id               = $policy->id;
                    $parameter->type                    = 6;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->save();
                }elseif($key == 'public'){
                    $parameter = new CovernoteParameter();
                    $parameter->type                    = 1;
                    $parameter->covernote_id               = $policy->id;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->value                   = $value['value'];
                    $parameter->save();
                }elseif($key == 'private'){
                    $parameter = new CovernoteParameter();
                    $parameter->type                    = 2;
                    $parameter->covernote_id            = $policy->id;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->value                   = $value['value'];
                    $parameter->save();
                }elseif($key == 'custom'){
                    foreach($value as $custom_key=>$custom_value){
                        $parameter = new CovernoteParameter();
                        $parameter->type                    = 7;
                        $parameter->covernote_id            = $policy->id;
                        $parameter->sub_category_id         = $request->subcategory;
                        $parameter->parameter_id            = $custom_key;
                        $parameter->value                   = $custom_value['value'];
                        $parameter->save();
                    }
                }elseif($key == 'bus'){
                    foreach($value as $bus_key=>$bus_value){

                        $parameter = new CovernoteParameter();
                        $parameter->type                    = 4;
                        $parameter->covernote_id               = $policy->id;
                        $parameter->sub_category_id         = $request->subcategory;
                        $parameter->parameter_id            = $bus_key;
                        $parameter->value                   = $bus_value['value'];
                        $parameter->save();
                    }
                }
            }
        }
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.covernote')->with('success', 'Covernote Updated Successfully.');;
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function getCovernoteAttachment(Request $request, $id = NULL){
        $attachments = CovernoteAttachment::where('covernote_id',$id)->get();
        $mocks = [];
        foreach ($attachments as $attachment) {
            $mock['name'] = $attachment->file;
            $dirUrl = URL::asset('covernote_attachment/');
            $mock['url'] = $dirUrl.'/'.$attachment->file;
            $mocks[] = $mock;
        }
        $attachments = json_encode($mocks);
        return response()->json($mocks, 200);
    }
    public function covernoteCovernote(Request $request){
        $id = $request->id;
        $covernote = Covernote::where('id',$id)->first();

        $policy = new Policy();
        $policy->insurance_type          = $covernote->insurance_type;
        $policy->business_source         = $covernote->business_source;
        $policy->category                = $covernote->category;
        $policy->policy_type             = $covernote->policy_type;
        $policy->policy_individual_rate  = $covernote->policy_individual_rate;
        $policy->category                = $covernote->category;
        $policy->sub_category            = $covernote->sub_category;
        $policy->company                 = $covernote->company;
        $policy->covernote_no            = $covernote->covernote_no;
        $policy->policy_no               = $covernote->covernote_no;
        $policy->customer                = $covernote->customer;
        $policy->vehicle_model           = $covernote->vehicle_model;
        $policy->vehicle_chassis_no      = $covernote->vehicle_chassis_no;
        $policy->health_category         = $covernote->health_category;
        $policy->health_plan             = $covernote->health_plan;
        $policy->cc                      = $covernote->cc;
        $policy->paid_driver             = $covernote->paid_driver;
        $policy->idv_amount              = $covernote->idv_amount;
        $policy->net_premium_amount      = $covernote->net_premium_amount;
        $policy->sum_insured_amount      = $covernote->sum_insured_amount;
        $policy->business_amount         = $covernote->business_amount;
        $policy->pyp_no                  = $covernote->pyp_no;
        $policy->pyp_insurance_company   = $covernote->pyp_insurance_type;
        $policy->pyp_expiry_date         = $covernote->pyp_expiry_date;
        $policy->agent                   = $covernote->agent;
        $policy->vehicle_make            = $covernote->vehicle_make;
        $policy->vehicle_registration_no = $covernote->vehicle_registration_no;
        $policy->year_of_manufacture     = $covernote->year_of_manufacture;
        $policy->seating_capacity        = $covernote->seating_capacity;
        $policy->pa_to_passenger         = $covernote->pa_to_passenger;
        $policy->gross_premium_amount    = $covernote->gross_premium_amount;
        $policy->risk_start_date         = $covernote->risk_start_date;
        $policy->risk_end_date           = $covernote->risk_end_date;
        $policy->payment_type            = $covernote->payment_type;
        $policy->business_type           = $covernote->business_type;
        $policy->cheque_no               = $covernote->cheque_no;
        $policy->cheque_date             = $covernote->cheque_date;
        $policy->bank_name               = $covernote->bank_name;
        $policy->transaction_no          = $covernote->transaction_no;
        $policy->policy_document         = '';
        $policy->remarks                 = $covernote->remarks;
        $policy->created_by              = Auth::user()->id;
        $policy->status                  = 1;
        $policy->save();
        $attachments = CovernoteAttachment::where('covernote_id',$id)->get();
        foreach($attachments as $attachment){
            $oldPath = 'public/covernote_attachment/'.$attachment->file;
            $rand = rand(1,100);
            $fileExtension = \File::extension($oldPath);
            $newName = date('YmdHis').$rand.'.'.$fileExtension;
            $newPathWithName = "public/policy_attachment/".$newName;
            if (\File::move($oldPath , $newPathWithName)) {
                $policy_attachment = new PolicyAttachment();
                $policy_attachment->policy_id    = $policy->id;
                $policy_attachment->file         = $newName;
                $policy_attachment->save();
            }
        }
        $parameters = CovernoteParameter::where('covernote_id',$id)->get();
        if(!blank($parameters)){
            foreach($parameters as $param){
                if($param->type == 3){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 3;
                    $parameter->sub_category_id         = $param->sub_category_id;
                    $parameter->parameter_id            = $param->parameter_id;
                    $parameter->taxi_cc                 = $param->taxi_cc;
                    $parameter->seating_capacity        = $param->seating_capacity;
                    $parameter->paid_driver             = $param->paid_driver;
                    $parameter->save();
                }elseif($param->type == 5){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 5;
                    $parameter->sub_category_id         = $param->sub_category_id;
                    $parameter->parameter_id            = $param->parameter_id;
                    $parameter->save();
                }elseif($param->type == 6){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 6;
                    $parameter->sub_category_id         = $param->sub_category_id;
                    $parameter->parameter_id            = $param->parameter_id;
                    $parameter->save();
                }elseif($param->type == 1){
                    $parameter = new PolicyParameter();
                    $parameter->type                    = 1;
                    $parameter->policy_id               = $policy->id;
                    $parameter->sub_category_id         = $param->sub_category_id;
                    $parameter->parameter_id            = $param->parameter_id;
                    $parameter->value                   = $param->value;
                    $parameter->save();
                }elseif($param->type == 2){
                    $parameter = new PolicyParameter();
                    $parameter->type                    = 2;
                    $parameter->policy_id               = $policy->id;
                    $parameter->sub_category_id         = $param->sub_category_id;
                    $parameter->parameter_id            = $param->parameter_id;
                    $parameter->value                   = $param->parameter_id;
                    $parameter->save();
                }elseif($param->type == 7){
                    $parameter = new PolicyParameter();
                    $parameter->type                    = 7;
                    $parameter->policy_id               = $policy->id;
                    $parameter->sub_category_id         = $param->sub_category_id;
                    $parameter->parameter_id            = $param->parameter_id;
                    $parameter->value                   = $param->value;
                    $parameter->save();
                }elseif($param->type == 4){
                    $parameter = new PolicyParameter();
                    $parameter->type                    = 4;
                    $parameter->policy_id               = $policy->id;
                    $parameter->sub_category_id         = $param->sub_category_id;
                    $parameter->parameter_id            = $param->parameter_id;
                    $parameter->value                   = $param->value;
                    $parameter->save();
                }
            }
        }
        $covernote_delete = Covernote::where('id',$id)->delete();
        return response()->json($policy->id, 200);
    }
}
