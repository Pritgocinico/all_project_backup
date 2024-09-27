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
use App\Models\Policy;
use App\Models\PolicyParameter;
use App\Models\PolicyAttachment;
use App\Models\PolicyDocument;
use App\Models\PolicyPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use URL;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CancelPolicyExport;
use Notification;
use App\Notifications\Notifications;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use App\Models\UserPermission;

class PolicyController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function getPolicyData(Request $request)
    {
        if ($request->ajax()) {
            
            // Fetch necessary data
            $customers = Customer::where('status', 1)->get();
            $categories = Category::where('parent', 0)->get();
            $id = $categories[0]['id'];
            $sub_categories = Category::where('parent', $id)->get();
            $userid = Auth::user()->id;
            $user = User::where('id', $userid)->first();
            $permissions = UserPermission::where('user_id', $user->id)->get();
            $data = Policy::orderBy('created_at', 'desc')->where('status', '!=', 2)->where('business_type', 1);
    
            // Define mappings
            $businessTypeMap = [
                1 => 'New',
                2 => 'Renewal',
                3 => 'Rollover',
                4 => 'Used',
                'New' => 1,
                'Renewal' => 2,
                'Rollover' => 3,
                'Used' => 4,
                'Portability' => 3, // Assuming Portability maps to 3 for health insurance
            ];
    
            $insuranceTypeMap = [
                1 => 'Non Helth',
                2 => 'Health',
                'Non Helth' => 1,
                'Health' => 2,
            ];
    
            return Datatables::of($data)
                ->addColumn('policy_no', function($row) {
                    return '<a href="' . route('admin.view_policy', $row->id) . '" style="color:blue !important;">' . $row->policy_no . '</a>';
                })
                ->addColumn('insurance_type', function($row) use ($insuranceTypeMap) {
                    return $insuranceTypeMap[$row->insurance_type] ?? '';
                })
                ->addColumn('customer', function($row) use ($customers) {
                    $customer = $customers->firstWhere('id', $row->customer);
                    return $customer ? $customer->name : '';
                })
                ->addColumn('category', function($row) use ($sub_categories) {
                    if ($row->insurance_type == 1) {
                        $category = $sub_categories->firstWhere('id', $row->sub_category);
                        return $category ? $category->name : '';
                    } else {
                        return $row->health_category;
                    }
                })
                ->addColumn('business_type', function($row) use ($businessTypeMap) {
                    return $businessTypeMap[$row->business_type] ?? '';
                })
                ->addColumn('vehicle_make', function($row) {
                    return $row->vehicle_make .' - '. $row->vehicle_model;
                })
                ->addColumn('vehicle_registration_no', function($row) {
                    return $row->vehicle_registration_no;
                })
                ->addColumn('vehicle_chassis_no', function($row) {
                    return $row->vehicle_chassis_no;
                })
                ->addColumn('net_premium_amount', function($row) {
                    return $row->net_premium_amount;
                })
                ->addColumn('risk_start_date', function($row) {
                    return date('d-m-Y', strtotime($row->risk_start_date)) . '/ ' . date('d-m-Y', strtotime($row->risk_end_date));
                })
                ->addColumn('action', function($row) use ($permissions) {
                    $actionBtn = '<ul class="action">';
                    if (Auth::user()->role == 1) {
                        $actionBtn .= '<li class="edit"><a href="' . route('admin.view_policy', $row->id) . '" title="View Policy"><i class="icon-eye"></i></a></li>';
                        $actionBtn .= '<li class="edit"><a href="' . route('admin.claims', $row->id) . '" title="Claim"><i class="icon-save-alt"></i></a></li>';
                        $actionBtn .= '<li class="edit"><a href="' . route('admin.endorsement', $row->id) . '" title="Endorsement"><i class="icon-check-box"></i></a></li>';
                        $actionBtn .= '<li class="edit"><a href="' . route('admin.renew_policy', $row->id) . '" title="Renew"><i class="icon-reload"></i></a></li>';
                        $actionBtn .= '<li class="edit"><a href="' . route('admin.edit_policy', $row->id) . '"><i class="icon-pencil-alt"></i></a></li>';
                        $actionBtn .= '<li class="delete"><a href="javascript:void(0);" data-id="' . $row->id . '" class="delete-btn" title="Delete"><i class="icon-trash"></i></a></li>';
                        $actionBtn .= '<li class="edit"><a href="javascript:void(0);" data-bs-toggle="modal" data-policy="' . $row->id . '" data-bs-target="#staticBackdrop" class="cancelPolicy"><i class="icon-close"></i></a></li>';
                    } elseif (Auth::user()->role == 3) {
                        $actionBtn .= '<a href="' . route('admin.view_policy', $row->id) . '" title="View Policy"><i class="bi bi-eye-fill ed_btn me-2"></i></a></li>';
                    } else {
                        foreach ($permissions as $permission) {
                            if ($permission->capability == 'policy-own-view' && $permission->value == 1) {
                                $actionBtn .= '<li class="edit"><a href="' . route('admin.view_policy', $row->id) . '" title="View Policy"><i class="icon-eye"></i></a></li>';
                            }
                            if ($permission->capability == 'policy-claims' && $permission->value == 1) {
                                $actionBtn .= '<li class="edit"><a href="' . route('admin.claims', $row->id) . '" title="Claim"><i class="icon-save-alt"></li>';
                            }
                            if ($permission->capability == 'policy-endorsement' && $permission->value == 1) {
                                $actionBtn .= '<li class="edit"><a href="' . route('admin.endorsement', $row->id) . '" title="Endorsement"><i class="icon-check-box"></i></a></li>';
                            }
                            if ($permission->capability == 'policy-renew' && $permission->value == 1) {
                                $actionBtn .= '<li class="edit"><a href="' . route('admin.renew_policy', $row->id) . '" title="Renew"><i class="icon-reload"></i></a></li>';
                            }
                            if ($permission->capability == 'policy-edit' && $permission->value == 1) {
                                $actionBtn .= '<li class="edit"><a href="' . route('admin.edit_policy', $row->id) . '"><i class="icon-pencil-alt"></i></a></li>';
                            }
                            if ($permission->capability == 'policy-delete' && $permission->value == 1) {
                                $actionBtn .= '<li class="delete"><a href="javascript:void(0);" data-id="' . $row->id . '" class="delete-btn" title="Delete"><i class="icon-trash"></i></a></li>';
                            }
                            if ($permission->capability == 'policy-cancel' && $permission->value == 1) {
                                $actionBtn .= '<li class="edit"><a href="javascript:void(0);" data-bs-toggle="modal" data-policy="' . $row->id . '" data-bs-target="#staticBackdrop" class="cancelPolicy"><i class="icon-close"></i></a></li>';
                            }
                        }
                    }
                    $actionBtn .= '</ul>';
                    return $actionBtn;
                })
                ->rawColumns(['policy_no', 'action'])
                ->order(function ($query) use ($request) {
                    $order = $request->get('order');
                    $columns = $request->get('columns');
                    
                    foreach ($order as $o) {
                        $columnIndex = $o['column'];
                        $dir = $o['dir'];
                        $column = $columns[$columnIndex]['data'];
    
                        switch ($column) {
                            case 'insurance_type':
                                $query->orderBy('insurance_type', $dir);
                                break;
                            case 'customer':
                                $customerIds = Customer::where('status', 1)->pluck('id')->toArray();
                                $query->orderByRaw("FIELD(customer, " . implode(',', $customerIds) . ") $dir");
                                break;
                            case 'category':
                                $query->orderBy('sub_category', $dir);
                                break;
                            case 'business_type':
                                $query->orderBy('business_type', $dir);
                                break;
                            case 'vehicle_make':
                                $query->orderBy('vehicle_make', $dir);
                                break;
                            case 'vehicle_registration_no':
                                $query->orderBy('vehicle_registration_no', $dir);
                                break;
                            case 'vehicle_chassis_no':
                                $query->orderBy('vehicle_chassis_no', $dir);
                                break;
                            case 'net_premium_amount':
                                $query->orderBy('net_premium_amount', $dir);
                                break;
                            case 'risk_start_date':
                                $query->orderBy('risk_start_date', $dir);
                                break;
                            default:
                                $query->orderBy($column, $dir);
                                break;
                        }
                    }
                })
                ->filter(function ($query) use ($request, $businessTypeMap, $insuranceTypeMap, $customers, $sub_categories) {
                    if ($request->has('search')) {
                        $search = $request->get('search')['value'];
                        $query->where(function ($q) use ($search, $businessTypeMap, $insuranceTypeMap, $customers, $sub_categories) {
                            $q->whereRaw("LOWER(CONCAT(policies.policy_no, ' ', policies.vehicle_make, ' ', policies.vehicle_registration_no, ' ', policies.vehicle_chassis_no)) LIKE '%" . strtolower($search) . "%'");
    
                            // Handle business type text search
                            if (array_key_exists($search, $businessTypeMap)) {
                                $q->orWhere('policies.business_type', $businessTypeMap[$search]);
                            }
                            
                            // Handle insurance type text search
                            if (array_key_exists($search, $insuranceTypeMap)) {
                                $q->orWhere('policies.insurance_type', $insuranceTypeMap[$search]);
                            }
                            
                            // Handle customer name text search
                            $customerIds = $customers->filter(function($customer) use ($search) {
                                return stripos($customer->name, $search) !== false;
                            })->pluck('id');
                            if ($customerIds->isNotEmpty()) {
                                $q->orWhereIn('policies.customer', $customerIds);
                            }
                            
                            // Handle category name text search
                            $categoryIds = $sub_categories->filter(function($category) use ($search) {
                                return stripos($category->name, $search) !== false;
                            })->pluck('id');
                            if ($categoryIds->isNotEmpty()) {
                                $q->orWhereIn('policies.sub_category', $categoryIds);
                            }
                        });
                    }
                })
                ->addIndexColumn()
                ->make(true);
        }
    }
    
public function policies(Request $request){
        if($request->has('search')){
            if(Auth::user()->role == 1 || Auth::user()->role == 2){
                $query = Policy::latest()->where('status','!=',2);
            }elseif(Auth::user()->role == 3){
                $query = Policy::latest()->where('status','!=',2)->where('agent',Auth::user()->id);
            }else{
                $query = Policy::latest()->where('status','!=',2)->where('created_by',Auth::user()->id);
            }
            // $query->where('policy_type' ,1);
            if($request->has('policy_no') && $request->policy_no != ''){
               $query->where('policy_no', 'like', '%' . $request->policy_no . '%');
            }
            if($request->has('insurance_type') && $request->insurance_type != ''){
                if($request->insurance_type == 'Motor Insurance' || $request->insurance_type == 'Motor' || $request->insurance_type == 'motor'){
                    $insurance_type = 1;
                }elseif($request->insurance_type == 'health Insurance' || $request->insurance_type == 'Health' || $request->insurance_type == 'health'){
                    $insurance_type = 2;
                }
                $query->where('insurance_type',$insurance_type);
            }
            if($request->has('customer') && $request->customer != ''){
                $query = $query->whereHas('customers', function($query1) use ($request){
                    $query1->where('name','LIKE','%'.$request->customer.'%');
                });
            }
            if($request->has('category') && $request->category != ''){
                $query = $query->whereHas('category', function($query1) use ($request){
                    $query1->where('name','LIKE','%'.$request->category.'%');
                });
            }
            if(isset($request->policy_source) && $request->policy_source != ''){
            
                $query->when($request->policy_source == "no",function($query){
                    $query->where('payout_restricted','no')->orWhereNull('payout_restricted');
                });
                $query->when($request->policy_source == "yes",function($query){
                    $query->where('payout_restricted','yes');
                });
            }
            if($request->has('business_type') && $request->business_type != ''){
                if($request->business_type == 'new' || $request->business_type == 'New' || $request->business_type == 'NEW'){
                    $business_type = 1;
                }elseif($request->business_type == 'renew' || $request->business_type == 'Renew' || $request->business_type == 'RENEW' || $request->business_type == 'Renewal'){
                    $business_type = 2;
                }elseif($request->business_type == 'rollover' || $request->business_type == 'Rollover' || $request->business_type == 'ROLLOVER'){
                    $business_type = 3;
                }elseif($request->business_type == 'used' || $request->business_type == 'Used' || $request->business_type == 'USED'){
                    $business_type = 4;
                }elseif($request->business_type == 'Portability' || $request->business_type == 'Portability' || $request->business_type == 'PORTABILITY'){
                    $business_type = 3;
                }else{
                    $business_type = '';
                }
                $query->where('business_type',$business_type);
            }
            if ($request->has('vehicle_make') && !empty($request->vehicle_make)) {
                $query->where(function($query) use ($request) {
                    $query->where('vehicle_make', 'like', '%' . $request->vehicle_make . '%')
                          ->orWhere('vehicle_model', 'like', '%' . $request->vehicle_make . '%');
                });
            }
            if($request->has('vehicle_registration_no') && $request->vehicle_registration_no != ''){
                $query->where('vehicle_registration_no', 'like', '%' . $request->vehicle_registration_no . '%');
            }
            if($request->has('vehicle_chassis_no') && $request->vehicle_chassis_no != ''){
                $query->where('vehicle_chassis_no', 'like', '%' . $request->vehicle_chassis_no . '%');
            }
            if($request->has('net_premium') && $request->net_premium != ''){
                $query->where('net_premium_amount', $request->net_premium);
            }
            if ($request->has('risk_start_date') && !empty($request->risk_start_date)) {
                $riskStartDate = $request->risk_start_date;
            
                $query->orWhere(function($query) use ($riskStartDate) {
                    $query->whereDate('risk_start_date', $riskStartDate)
                          ->orWhereDate('risk_end_date', $riskStartDate);
                });
            }

            $policies = $query->orderBy('created_at','Desc')->paginate(20);
        }else{
            // $policies = Policy::latest()->get()->where('business_type',1)->where('status','!=',2);
            if(Auth::user()->role == 1 || Auth::user()->role == 2){
                $query = Policy::where('status','!=',2);
                // $query = Policy::where('status','!=',2)->where('policy_type',1);
                // $policies = Policy::latest()->where('status','!=',2)->orderBy('id','Desc')->paginate(10);
            }elseif(Auth::user()->role == 3){
                $query = Policy::latest()->where('agent',Auth::user()->id)->where('status','!=',2);
            }else{
                $query = Policy::latest()->where('created_by',Auth::user()->id)->where('status','!=',2);
            }
            $policies = $query->orderBy('id','Desc')->paginate(20);
        }   

        $business_source = BusinessSource::orderBy('id','Desc')->get();
        $page  = 'Policy';
        $icon  = 'policy.png';
        $categories = Category::where('parent','==',0)->get();
        $sub_categories = Category::where('parent','!=',0)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        // if(Auth::user()->role == 1){
            return view('admin.policy.policies',compact('policies','business_source','page','icon','categories','sub_categories','companies','customers','plans'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addPolicy(Request $request){
        $page = 'Add Policy';
        $icon = 'policy.png';
        $categories = Category::where('parent','==',0)->get();
        $id = $categories[0]['id'];
        $sub_categories = Category::where('parent',$id)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $business_source = BusinessSource::orderBy('id','Desc')->get();
        $sourcing_agents = SourcingAgent::all();
        $team_lead = User::where('team_lead',1)->where('status',1)->get();
        $managed_by = User::where('role',2)->where('status',1)->get();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        // if(Auth::user()->role == 1){
            return view('admin.policy.add_policy',compact('page','icon','categories','sub_categories','companies','customers','plans','business_source','sourcing_agents','team_lead','managed_by'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addPolicyData(Request $request){  
       if ($request->has('insurance_type') && $request->insurance_type == 1) {
            $validator = Validator::make($request->all(), [
                'category' => 'required',
                'subcategory' => 'required',
                'insurance_company' => 'required',
                'customer' => 'required|not_in:0',
                'vehicle_model' => 'required',
                'vehicle_engine' => 'required',
                'vehicle_make' => 'required',
                'year_of_manufacture' => 'required',
                'vehicle_chassis_no' => 'required',
                'idv_amount' => 'required',
                'business_type' => 'required',
                'sourcing_agent' => 'required|not_in:0',
                'gross_premium_amount' => 'required',
                'net_premium_amount' => 'required',
                'risk_start_date' => 'required',
                'risk_end_date' => 'required',
                'ncb' => 'required',
                'team_lead' => 'required|not_in:0',
                'managed_by' => 'required|not_in:0',
                'motor_pyp_no' => 'required_if:business_type,2,required_if:business_type,3',
                'motor_pyp_insurance_company' => 'required_if:business_type,2,required_if:business_type,3',
                'motor_pyp_expiry_date' => 'required_if:business_type,2,required_if:business_type,3',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'insurance_company' => 'required',
                'customer' => 'required|not_in:0',
                'health_plan' => 'required|not_in:0',
                'health_category' => 'required|not_in:0',
                'business_type' => 'required',
                'sourcing_agent' => 'required|not_in:0',
                'gross_premium_amount' => 'required',
                'sum_insured_amount' => 'required',
                'net_premium_amount' => 'required',
                'risk_start_date' => 'required',
                'risk_end_date' => 'required',
                'ncb' => 'required',
                'team_lead' => 'required|not_in:0',
                'managed_by' => 'required|not_in:0',
                'pyp_no' => 'required_if:business_type,2,required_if:business_type,3',
                'pyp_insurance_company' => 'required_if:business_type,2,required_if:business_type,3',
                'pyp_expiry_date' => 'required_if:business_type,2,required_if:business_type,3',
            ]);
        }
        
        if ($validator->fails()) {
             return back()->withErrors($validator->errors())->withInput();
        }
        
        if($request->insurance_type == 1){
            $pyp_no                 = $request->motor_pyp_no;
            $pyp_insurance_company  = $request->motor_pyp_insurance_company;
            $pyp_expiry_date        = $request->motor_pyp_expiry_date;
            $business_type          = $request->business_type;
        }else{
            $pyp_no                 = $request->pyp_no;
            $pyp_insurance_company  = $request->pyp_insurance_company;
            $pyp_expiry_date        = $request->pyp_expiry_date;
            $business_type          = $request->health_business_type;
        }
        $policy = new Policy();
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
        $policy->created_date               = $request->created_date;
        $policy->customer                = $request->customer;
        $policy->payout_restricted                = $request->payout_restricted;
        if($request->payout_restricted == "yes"){
            $policy->payout_restricted_remark           = $request->payout_restricted_remark;
        }
        if($request->has('vehicle_model')){
            $policy->vehicle_model           = $request->vehicle_model;
        }
        if($request->has('vehicle_engine')){
            $policy->vehicle_engine          = $request->vehicle_engine;
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
        if($request->has('business_source')){
            $policy->business_source            = $request->business_source;
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
        if($request->has('team_lead')){
            $policy->team_lead                  = $request->team_lead;
        }
        if($request->has('managed_by')){
            $policy->managed_by              = $request->managed_by;
        }
        if($request->has('tp')){
            $policy->tp                     = $request->tp;
        }
        if($request->has('od')){
            $policy->od                     = $request->od;
        }
        $policy->net_premium_amount         = $request->net_premium_amount;
        $policy->sum_insured_amount         = $request->sum_insured_amount;
        if($request->has('business_amount')){
            $policy->business_amount         = $request->business_amount;
        }
        if($request->has('pyp_no')){
            $policy->pyp_no                  = $pyp_no;
        }
        if($request->has('pyp_insurance_company')){
            $policy->pyp_insurance_company   = $pyp_insurance_company;
        }
        if($request->has('pyp_expiry_date')){
            $policy->pyp_expiry_date         = $pyp_expiry_date;
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
        if($request->has('ncb')){
            $policy->ncb                    = $request->ncb;
        }
        if($request->has('rto_name')){
            $policy->rto_name                    = $request->rto_name;
        }
        $policy->gross_premium_amount    = $request->gross_premium_amount;
        $policy->risk_start_date         = $request->risk_start_date;
        $policy->risk_end_date           = $request->risk_end_date;
        // $policy->payment_type            = $request->payment_type;
        $policy->business_type           = $business_type;
        // if($request->has('cheque_no')){
        //     $policy->cheque_no               = $request->cheque_no;
        // }
        // if($request->has('cheque_date')){
        //     $policy->cheque_date             = $request->cheque_date;
        // }
        // if($request->has('bank_name')){
        //     $policy->bank_name               = $request->bank_name;
        // }
        // if($request->has('transaction_no')){
        //     $policy->transaction_no          = $request->transaction_no;
        // }
        // $policy->policy_document         = $img;
        $policy->remarks                 = $request->remarks;
        $policy->created_by              = Auth::user()->id;
        $policy->status                  = 1;
        $policy->save();
        if($request->has('file')){
            foreach($request->file as $file){
                if($file && $file != null){
                    $fileName= $file->getClientOriginalName();
                    $image_file = $file;
                    $destinationPath1 = 'public/policy_attachment/';
                    $rand1=rand(1,100);
                    $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                    $image_file->move($destinationPath1, $docImage1);
                    $img_attachment=$docImage1;
                }else{
                    $fileName = "";
                    $img_attachment='';
                }
                $attachment = new PolicyAttachment();
                $attachment->policy_id = $policy->id;
                $attachment->file = $img_attachment;
                $attachment->file_name = $fileName;
                $attachment->save();
            }
        }
        if($request->has('policy_document')){
            foreach($request->policy_document as $document){
                $image = $document;
                $destinationPath = 'public/policy_document/';
                $rand=rand(1,100);
                $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $docImage);
                $image_name = $image->getClientOriginalName();
                $img=$docImage;
                $policy_document = new PolicyDocument();
                $policy_document->policy_id = $policy->id;
                $policy_document->file      = $img;
                $policy_document->file_name = $image_name;
                $policy_document->save();
            }
        }
        // if($request->has('policy_document')){
        //     foreach($request->policy_document as $document){
        //         $image = $document;
        //         $destinationPath = 'public/policy_document/';
        //         $rand=rand(1,100);
        //         $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
        //         $image->move($destinationPath, $docImage);
        //         $image_name = $image->getClientOriginalName();
        //         $img=$docImage;
        //         $policy_document = new PolicyDocument();
        //         $policy_document->policy_id = $policy->id;
        //         $policy_document->file      = $img;
        //         $policy_document->file_name = $image_name;
        //         $policy_document->save();
        //     }
        // }
        if($request->has('param')){
            foreach($request->param as $key=>$value){
                if($key == 'taxi'){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 3;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['cc']['value'];
                    $parameter->taxi_cc                 = $value['cc']['value'];
                    $parameter->seating_capacity        = $value['seating_capacity']['value'];
                    $parameter->paid_driver             = $value['paid_driver']['value'];
                    $parameter->save();
                }elseif($key == 'cc'){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 5;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->save();
                }elseif($key == 'pa_to_passanger'){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 6;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->save();
                }elseif($key == 'public'){
                    $parameter = new PolicyParameter();
                    $parameter->type                    = 1;
                    $parameter->policy_id               = $policy->id;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->value                   = $value['value'];
                    $parameter->save();
                }elseif($key == 'private'){
                    $parameter = new PolicyParameter();
                    $parameter->type                    = 2;
                    $parameter->policy_id               = $policy->id;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->value                   = $value['value'];
                    $parameter->save();
                }elseif($key == 'custom'){
                    foreach($value as $custom_key=>$custom_value){
                        $parameter = new PolicyParameter();
                        $parameter->type                    = 7;
                        $parameter->policy_id               = $policy->id;
                        $parameter->sub_category_id         = $request->subcategory;
                        $parameter->parameter_id            = $custom_key;
                        $parameter->value                   = $custom_value['value'];
                        $parameter->save();
                    }
                }elseif($key == 'bus'){
                    foreach($value as $bus_key=>$bus_value){

                        $parameter = new PolicyParameter();
                        $parameter->type                    = 4;
                        $parameter->policy_id               = $policy->id;
                        $parameter->sub_category_id         = $request->subcategory;
                        $parameter->parameter_id            = $bus_key;
                        $parameter->value                   = $bus_value['value'];
                        $parameter->save();
                    }
                }
            }
        }
        if($request->has('payment_type')){
            foreach($request->payment_type as $key=>$payment){
                $payment_type = $payment;
                $chq_no = '';
                $chq_date = '';
                $bak_name = '';
                $transaction = '';
                $date = '';
                $made_by = '';
                if($request->has('cheque_no')){
                    foreach($request->cheque_no as $cheque_no_key=>$cheque_no){
                        if($cheque_no_key == $key){
                            $chq_no = $cheque_no;
                        }
                    }
                }
                if($request->has('cheque_date')){
                    foreach($request->cheque_date as $cheque_date_key=>$cheque_date){
                        if($cheque_date_key == $key){
                            $chq_date = $cheque_date;
                        }
                    }
                }
                if($request->has('bank_name')){
                    foreach($request->bank_name as $bank_name_key=>$bank_name){
                        if($bank_name_key == $key){
                            $bak_name = $bank_name;
                        }
                    }
                }
                if($request->has('transaction_no')){
                    foreach($request->transaction_no as $transaction_no_key=>$transaction_no){
                        if($transaction_no_key == $key){
                            $transaction = $transaction_no;
                        }
                    }
                }
                if($request->has('payment_date')){
                    foreach($request->payment_date as $payment_date_key=>$payment_date){
                        if($payment_date_key == $key){
                            $date = date('Y-m-d',strtotime($payment_date));
                        }
                    }
                }
                if($request->has('payment_made_by')){
                    foreach($request->payment_made_by as $payment_made_by_key=>$payment_made_by){
                        if($payment_made_by_key == $key){
                            $made_by = $payment_made_by;
                        }
                    }
                }
                $policy_payment = new PolicyPayment();
                $policy_payment->policy_id      = $policy->id;
                $policy_payment->payment_type   = $payment_type;
                $policy_payment->cheque_no      = $chq_no;
                $policy_payment->cheque_date    = $chq_date;
                $policy_payment->bank_name      = $bak_name;
                $policy_payment->transaction_no = $transaction;
                $policy_payment->payment_date   = $date;
                $policy_payment->made_by        = $made_by;
                $policy_payment->status         = 1;
                $policy_payment->save();
            }
        }
        try {
                // Send notification to Admin
                $userSchema = User::first();
                $details = [
                    'name'  => 'Policy Created.',
                    'type'  => 'Policy',
                    'body'  => $policy->policy_no . ' created by ' . Auth::user()->name,
                    'url'   => route('admin.policies'),
                ];
                Notification::send($userSchema, new Notifications($details));
            
                if (Auth::user()->id != 1) {
                    $userSchema11 = User::find(Auth::user()->id);
                    $details11 = [
                        'name'  => 'Policy Added.',
                        'type'  => 'Policy',
                        'body'  => $policy->policy_no . ' added by ' . Auth::user()->name,
                        'url'   => route('admin.policies'),
                    ];
                    Notification::send($userSchema11, new Notifications($details11));
                }
            
                $agent = SourcingAgent::find($request->sourcing_agent);
                if ($agent) {
                    $userSchema1 = User::find($agent->user_id);
                    if ($userSchema1) {
                        $details1 = [
                            'name'  => 'Policy Created.',
                            'type'  => 'Policy',
                            'body'  => $policy->policy_no . ' created by ' . Auth::user()->name,
                            'url'   => route('admin.policies'),
                        ];
                        Notification::send($userSchema1, new Notifications($details1));
                    }
                }
            }catch(\Exception $e){

        }
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.policies')->with('success', 'Policy Added Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function deletePolicy($id){
        $policy = Policy::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Policy';
        $log->log       = 'Policy has been Deleted';
        $log->save();
        $policy->delete();
        return 1;
    }
    public function viewPolicy(Request $request, $id = NULL){
        $page = 'View Policy';
        $icon = 'policy.png';
        $categories = Category::where('parent','==',0)->get();
        $companies = Company::all();
        $business_source = BusinessSource::orderBy('id','Desc')->get();
        $sourcing_agents = SourcingAgent::all();
        $policy = Policy::where('id',$id)->first();
        $plans = Plan::where('status',1)->where('company',$policy->company)->first();
        $attachments = PolicyAttachment::where('policy_id',$policy->id)->get();
        $documents = PolicyDocument::where('policy_id',$policy->id)->get();
        $customer = Customer::where('id',$policy->customer)->first();
        $sub_categories = Category::where('parent',$policy->category)->get();
        $parameters = PolicyParameter::where('policy_id',$policy->id)->get();
        $payments = PolicyPayment::where('policy_id',$policy->id)->get();
        // if(Auth::user()->role == 1){
            return view('admin.policy.view_policy',compact('page','icon','policy','categories','sub_categories','companies','customer','plans','business_source','sourcing_agents','attachments','parameters','documents','payments'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function editPolicy(Request $request, $id = NULL){
        $page = 'Edit Policy';
        $icon = 'policy.png';
        $categories = Category::where('parent','==',0)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $business_source = BusinessSource::orderBy('id','Desc')->get();
        $sourcing_agents = SourcingAgent::all();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        $policy = Policy::where('id',$id)->first();
        $team_lead = User::where('team_lead',1)->where('status',1)->get();
        $managed_by = User::where('role',2)->where('status',1)->get();
        if($policy->insurance_type == 1){
            $cat_id = $policy->category;
        }else{
            $cat_id = 0;
        }
        $sub_categories = Category::where('parent',$cat_id)->get();
        if(!blank($policy)){
            $documents = PolicyDocument::where('policy_id',$policy->id)->get();
            $parameters = PolicyParameter::where('policy_id',$policy->id)->get();
        }else{
            $parameters = [];
            $documents = [];
        }
        $policy_payments = PolicyPayment::where('policy_id',$policy->id)->get();
        // if(Auth::user()->role == 1){
            return view('admin.policy.edit_policy',compact('page','icon','policy','categories','sub_categories','companies','customers','plans','business_source','sourcing_agents','documents','policy_payments','team_lead','managed_by'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function getPolicyAttachment(Request $request, $id = NULL){
        $attachments = PolicyAttachment::where('policy_id',$id)->get();
        $mocks = [];
        foreach ($attachments as $attachment) {
            $mock['name'] = $attachment->file;
            $mock['id'] = $attachment->id;
            $dirUrl = URL::asset('policy_attachment/');
            $mock['url'] = $dirUrl.'/'.$attachment->file;
            $mocks[] = $mock;
        }
        $attachments = json_encode($mocks);
        return response()->json($mocks, 200);
    }
    public function editPolicyData(Request $request){
        // echo '<pre>';
        // print_R($request->All());
        // exit;
        if($request->has('insurance_type') && $request->insurance_type == 1){
            $request->validate([
                'category'              => 'required',
                'subcategory'           => 'required',
                'insurance_company'     => 'required',
                'customer'              => 'required|not_in:0',
                'vehicle_model'         => 'required',
                'vehicle_make'          => 'required',
                'vehicle_engine'        => 'required',
                'year_of_manufacture'   => 'required',
                'vehicle_chassis_no'    => 'required',
                // 'idv_amount'            => 'required',
                'business_type'         => 'required',
                'sourcing_agent'        => 'required|not_in:0',
                'gross_premium_amount'  => 'required',
                'net_premium_amount'    => 'required',
                'ncb'                   => 'required',
                'team_lead'             => 'required|not_in:0',
                'managed_by'            => 'required|not_in:0',
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
                'ncb'                   => 'required',
                'team_lead'             => 'required|not_in:0',
                'managed_by'            => 'required|not_in:0',
                'pyp_no'                => 'required_if:health_business_type,2|required_if:health_business_type,3',
                'pyp_insurance_company' => 'required_if:health_business_type,2|required_if:health_business_type,3',
                'pyp_expiry_date'       => 'required_if:health_business_type,2|required_if:health_business_type,3',
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
        if($request->insurance_type == 1){
            $pyp_no                 = $request->pyp_no;
            $pyp_insurance_company  = $request->pyp_insurance_company;
            $pyp_expiry_date        = $request->pyp_expiry_date;
            $business_type          = $request->business_type;
        }else{
            $pyp_no                 = $request->pyp_no;
            $pyp_insurance_company  = $request->pyp_insurance_company;
            $pyp_expiry_date        = $request->pyp_expiry_date;
            $business_type          = $request->health_business_type;
        }
        $policy = Policy::where('id',$request->id)->first();
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
        $policy->payout_restricted                = $request->payout_restricted;
        if($request->payout_restricted == "yes"){
            $policy->payout_restricted_remark           = $request->payout_restricted_remark;
        }
        if($request->has('vehicle_model')){
            $policy->vehicle_model           = $request->vehicle_model;
        }
        if($request->has('vehicle_engine')){
            $policy->vehicle_engine           = $request->vehicle_engine;
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
        if($request->has('ncb')){
            $policy->ncb                    = $request->ncb;
        }
        if($request->has('team_lead')){
            $policy->team_lead              = $request->team_lead;
        }
        if($request->has('managed_by')){
            $policy->managed_by             = $request->managed_by;
        }
        if($request->has('rto_name')){
            $policy->rto_name                    = $request->rto_name;
        }
        if($request->has('tp')){
            $policy->tp                     = $request->tp;
        }
        if($request->has('od')){
            $policy->od                     = $request->od;
        }
        $policy->net_premium_amount         = (float)$request->net_premium_amount;
        $policy->sum_insured_amount         = $request->sum_insured_amount;
        if($request->has('business_amount')){
            $policy->business_amount         = $request->business_amount;
        }
        if($request->has('pyp_no')){
            $policy->pyp_no                  = $pyp_no;
        }
        if($request->has('pyp_insurance_company')){
            $policy->pyp_insurance_company   = $pyp_insurance_company;
        }
        if($request->has('pyp_expiry_date')){
            $policy->pyp_expiry_date         = $pyp_expiry_date;
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
        // $policy->payment_type            = $request->payment_type;
        $policy->business_type           = $business_type;
        // $policy->policy_document         = $img;
        $policy->status                  = 1;
        // if($request->has('cheque_no')){
        //     $policy->cheque_no               = $request->cheque_no;
        // }
        // if($request->has('cheque_date')){
        //     $policy->cheque_date             = $request->cheque_date;
        // }
        // if($request->has('bank_name')){
        //     $policy->bank_name               = $request->bank_name;
        // }
        // if($request->has('transaction_no')){
        //     $policy->transaction_no          = $request->transaction_no;
        // }
        $policy->save();
        if($request->has('file')){
            foreach($request->file as $file){
                if($file && $file != null){
                    $fileName = $file->getClientOriginalName();
                    $image_file = $file;
                    $destinationPath1 = 'public/policy_attachment/';
                    $rand1=rand(1,100);
                    $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                    $image_file->move($destinationPath1, $docImage1);
                    $img_attachment=$docImage1;
                }else{
                    $fileName = "";
                    $img_attachment='';
                }
                $attachment = new PolicyAttachment();
                $attachment->file_name = $fileName;
                $attachment->policy_id = $policy->id;
                $attachment->file = $img_attachment;
                $attachment->save();
            }
        }
        if($request->has('policy_document')){
            foreach($request->policy_document as $document){
                $image = $document;
                $destinationPath = 'public/policy_document/';
                $rand=rand(1,100);
                $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $docImage);
                $image_name = $image->getClientOriginalName();
                $img=$docImage;
                $policy_document = new PolicyDocument();
                $policy_document->policy_id = $policy->id;
                $policy_document->file      = $img;
                $policy_document->file_name = $image_name;
                $policy_document->save();
            }
        }
        $parameterDelete = PolicyParameter::where('policy_id',$request->id)->delete();
        if($request->has('param')){
            foreach($request->param as $key=>$value){
                if($key == 'taxi'){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 3;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['cc']['value'];
                    $parameter->taxi_cc                 = $value['cc']['value'];
                    $parameter->seating_capacity        = $value['seating_capacity']['value'];
                    $parameter->paid_driver             = $value['paid_driver']['value'];
                    $parameter->save();
                }elseif($key == 'cc'){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 5;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->save();
                }elseif($key == 'pa_to_passanger'){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 6;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->save();
                }elseif($key == 'public'){
                    $parameter = new PolicyParameter();
                    $parameter->type                    = 1;
                    $parameter->policy_id               = $policy->id;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->value                   = $value['value'];
                    $parameter->save();
                }elseif($key == 'private'){
                    $parameter = new PolicyParameter();
                    $parameter->type                    = 2;
                    $parameter->policy_id               = $policy->id;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->value                   = $value['value'];
                    $parameter->save();
                }elseif($key == 'custom'){
                    foreach($value as $custom_key=>$custom_value){
                        $parameter = new PolicyParameter();
                        $parameter->type                    = 7;
                        $parameter->policy_id               = $policy->id;
                        $parameter->sub_category_id         = $request->subcategory;
                        $parameter->parameter_id            = $custom_key;
                        $parameter->value                   = $custom_value['value'];
                        $parameter->save();
                    }
                }elseif($key == 'bus'){
                    foreach($value as $bus_key=>$bus_value){

                        $parameter = new PolicyParameter();
                        $parameter->type                    = 4;
                        $parameter->policy_id               = $policy->id;
                        $parameter->sub_category_id         = $request->subcategory;
                        $parameter->parameter_id            = $bus_key;
                        $parameter->value                   = $bus_value['value'];
                        $parameter->save();
                    }
                }
            }
        }
        if($request->has('payment_type')){
            foreach($request->payment_type as $key=>$payment){
                $payment_type = $payment;
                $chq_no = '';
                $chq_date = '';
                $bak_name = '';
                $transaction = '';
                $date = '';
                $made_by = '';
                if($request->has('cheque_no')){
                    foreach($request->cheque_no as $cheque_no_key=>$cheque_no){
                        if($cheque_no_key == $key){
                            $chq_no = $cheque_no;
                        }
                    }
                }
                if($request->has('cheque_date')){
                    foreach($request->cheque_date as $cheque_date_key=>$cheque_date){
                        if($cheque_date_key == $key){
                            $chq_date = $cheque_date;
                        }
                    }
                }
                if($request->has('bank_name')){
                    foreach($request->bank_name as $bank_name_key=>$bank_name){
                        if($bank_name_key == $key){
                            $bak_name = $bank_name;
                        }
                    }
                }
                if($request->has('transaction_no')){
                    foreach($request->transaction_no as $transaction_no_key=>$transaction_no){
                        if($transaction_no_key == $key){
                            $transaction = $transaction_no;
                        }
                    }
                }
                if($request->has('payment_date')){
                    foreach($request->payment_date as $payment_date_key=>$payment_date){
                        if($payment_date_key == $key){
                            $date = date('Y-m-d',strtotime($payment_date));
                        }
                    }
                }
                if($request->has('payment_made_by')){
                    foreach($request->payment_made_by as $payment_made_by_key=>$payment_made_by){
                        if($payment_made_by_key == $key){
                            $made_by = $payment_made_by;
                        }
                    }
                }
                $i = 0;
                if($request->has('hidden')){

                    foreach($request->hidden as $hidden_key=>$hidden){
                        if($hidden_key == $key){
                            $i++;
                            $policy_payment = PolicyPayment::where('id',$hidden)->first();
                            $policy_payment->policy_id      = $policy->id;
                            $policy_payment->payment_type   = $payment_type;
                            $policy_payment->cheque_no      = $chq_no;
                            $policy_payment->cheque_date    = $chq_date;
                            $policy_payment->bank_name      = $bak_name;
                            $policy_payment->transaction_no = $transaction;
                            $policy_payment->payment_date   = $date;
                            $policy_payment->made_by        = $made_by;
                            $policy_payment->status         = 1;
                            $policy_payment->save();
                        }
                    }
                }
                if($i == 0){
                    $policy_payment = new PolicyPayment();
                    $policy_payment->policy_id      = $policy->id;
                    $policy_payment->payment_type   = $payment_type;
                    $policy_payment->cheque_no      = $chq_no;
                    $policy_payment->cheque_date    = $chq_date;
                    $policy_payment->bank_name      = $bak_name;
                    $policy_payment->transaction_no = $transaction;
                    $policy_payment->payment_date   = $date;
                    $policy_payment->made_by        = $made_by;
                    $policy_payment->status         = 1;
                    $policy_payment->save();
                }
            }
        }
        try{
            //send notification to Admin
            $userSchema = User::first();
            $details = [
                'name'  => 'Policy Updated.',
                'type'  => 'Policy',
                'body'  => $policy->policy_no.' '.'updated by '. Auth::user()->name,
                'url'   => route('admin.policies'),
            ];
            Notification::send($userSchema, new Notifications($details));
            if(Auth::user()->id != 1){
                $userSchema11 = User::where('id',Auth::user()->id)->first();
                $details11 = [
                    'name'  => 'Policy Updated.',
                    'type'  => 'Policy',
                    'body'  => $policy->policy_no.' '.'updated by '. Auth::user()->name,
                    'url'   => route('admin.policies'),
                ];
                Notification::send($userSchema11, new Notifications($details11));
            }
            $agent = SourcingAgent::where('id',$request->sourcing_agent)->first();
            $userSchema1 = User::where('id',$agent->user_id)->first();
            if ($userSchema1) {
                $details1 = [
                    'name'  => 'Policy Updated.',
                    'type'  => 'Policy',
                    'body'  => $policy->policy_no.' '.'updated by '. Auth::user()->name,
                    'url'   => route('admin.policies'),
                ];
                Notification::send($userSchema1, new Notifications($details1));
            }
        }catch(\Exception $e){

        }
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.policies')->with('success', 'Policy Updated Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function renewPolicy(Request $request, $id = NULL){
        $page = 'Policy';
        $icon = 'policy.png';
        $categories = Category::where('parent','==',0)->get();
       
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $business_source = BusinessSource::orderBy('id','Desc')->get();
        $sourcing_agents = SourcingAgent::all();
       
        $policy = Policy::where('id',$id)->first();
        $parameters = PolicyParameter::where('policy_id',$policy->id)->get();
        $team_lead = User::where('team_lead',1)->where('status',1)->get();
        $managed_by = User::where('role',2)->where('status',1)->get();
        // $cat_id = $categories[0]['id'];
        $sub_categories = Category::where('parent',$policy->category)->get();
        $plans = Plan::where('status',1)->where('company',$policy->company)->get();
        // if(Auth::user()->role == 1){
            return view('admin.policy.renew_policy',compact('page','icon','policy','categories','sub_categories','companies','customers','plans','business_source','sourcing_agents','team_lead','managed_by'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function renewPolicyData(Request $request){
        if($request->has('insurance_type') && $request->insurance_type == 1){
            $request->validate([
                'category'              => 'required',
                'subcategory'           => 'required',
                'insurance_company'     => 'required',
                'customer'              => 'required|not_in:0',
                'vehicle_model'         => 'required',
                'vehicle_make'          => 'required',
                'vehicle_engine'        => 'required',
                'year_of_manufacture'   => 'required',
                'vehicle_chassis_no'    => 'required',
                'idv_amount'            => 'required',
                'business_type'         => 'required',
                'sourcing_agent'        => 'required|not_in:0',
                'gross_premium_amount'  => 'required',
                'net_premium_amount'    => 'required',
                'risk_start_date'       => 'required',
                'risk_end_date'         => 'required',
                'policy_no'             => 'required',
                'ncb'                   => 'required',
                'team_lead'             => 'required|not_in:0',
                'managed_by'            => 'required|not_in:0',
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
                'policy_no'             => 'required',
                'ncb'                   => 'required',
                'team_lead'             => 'required|not_in:0',
                'managed_by'            => 'required|not_in:0',
            ]);
        }
        
        $policy = new Policy();
        $policy->insurance_type          = $request->insurance_type;
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
        $policy->policy_no                  = $request->policy_no;
        $policy->customer                   = $request->customer;
        $policy->created_date               = $request->created_date;
        if($request->has('vehicle_model')){
            $policy->vehicle_model           = $request->vehicle_model;
        }
        if($request->has('vehicle_engine')){
            $policy->vehicle_engine           = $request->vehicle_engine;
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
        if($request->has('ncb')){
            $policy->ncb                    = $request->ncb;
        }
        if($request->has('team_lead')){
            $policy->team_lead              = $request->team_lead;
        }
        if($request->has('managed_by')){
            $policy->managed_by             = $request->managed_by;
        }
        if($request->has('tp')){
            $policy->tp                     = $request->tp;
        }
        if($request->has('od')){
            $policy->od                     = $request->od;
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
            $policy->pyp_expiry_date         = date("Y-m-d", strtotime($request->pyp_expiry_date));
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
        $policy->risk_start_date         = date("Y-m-d", strtotime($request->risk_start_date));
        $policy->risk_end_date           = date("Y-m-d", strtotime($request->risk_end_date));
        // $policy->payment_type            = $request->payment_type;
        $policy->business_type           = $request->business_type;
        $policy->remarks                 = $request->remarks;
        $policy->status                  = 1;
        $policy->created_by             = Auth::user()->id;
        $policy->save();
        if($request->has('file')){
            foreach($request->file as $file){
                if($file && $file != null){
                    $image_file = $file;
                    $destinationPath1 = 'public/policy_attachment/';
                    $rand1=rand(1,100);
                    $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                    $image_file->move($destinationPath1, $docImage1);
                    $img_attachment=$docImage1;
                }else{
                    $img_attachment='';
                }
                $attachment = new PolicyAttachment();
                $attachment->policy_id = $policy->id;
                $attachment->file = $img_attachment;
                $attachment->save();
            }
        }
        if($request->has('policy_document')){
            foreach($request->policy_document as $document){
                $image = $document;
                $destinationPath = 'public/policy_document/';
                $rand=rand(1,100);
                $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $docImage);
                $image_name = $image->getClientOriginalName();
                $img=$docImage;
                $policy_document = new PolicyDocument();
                $policy_document->policy_id = $policy->id;
                $policy_document->file      = $img;
                $policy_document->file_name = $image_name;
                $policy_document->save();
            }
        }
        $parameterDelete = PolicyParameter::where('policy_id',$request->id)->delete();
        if($request->has('param')){
            foreach($request->param as $key=>$value){
                if($key == 'taxi'){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 3;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['cc']['value'];
                    $parameter->taxi_cc                 = $value['cc']['value'];
                    $parameter->seating_capacity        = $value['seating_capacity']['value'];
                    $parameter->paid_driver             = $value['paid_driver']['value'];
                    $parameter->save();
                }elseif($key == 'cc'){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 5;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->save();
                }elseif($key == 'pa_to_passanger'){
                    $parameter = new PolicyParameter();
                    $parameter->policy_id               = $policy->id;
                    $parameter->type                    = 6;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->save();
                }elseif($key == 'public'){
                    $parameter = new PolicyParameter();
                    $parameter->type                    = 1;
                    $parameter->policy_id               = $policy->id;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->value                   = $value['value'];
                    $parameter->save();
                }elseif($key == 'private'){
                    $parameter = new PolicyParameter();
                    $parameter->type                    = 2;
                    $parameter->policy_id               = $policy->id;
                    $parameter->sub_category_id         = $request->subcategory;
                    $parameter->parameter_id            = $value['value'];
                    $parameter->value                   = $value['value'];
                    $parameter->save();
                }elseif($key == 'custom'){
                    foreach($value as $custom_key=>$custom_value){
                        $parameter = new PolicyParameter();
                        $parameter->type                    = 7;
                        $parameter->policy_id               = $policy->id;
                        $parameter->sub_category_id         = $request->subcategory;
                        $parameter->parameter_id            = $custom_key;
                        $parameter->value                   = $custom_value['value'];
                        $parameter->save();
                    }
                }elseif($key == 'bus'){
                    foreach($value as $bus_key=>$bus_value){
                        $parameter = new PolicyParameter();
                        $parameter->type                    = 4;
                        $parameter->policy_id               = $policy->id;
                        $parameter->sub_category_id         = $request->subcategory;
                        $parameter->parameter_id            = $bus_key;
                        $parameter->value                   = $bus_value['value'];
                        $parameter->save();
                    }
                }
            }
        }
        if($request->has('payment_type')){
            foreach($request->payment_type as $key=>$payment){
                $payment_type = $payment;
                $chq_no = '';
                $chq_date = '';
                $bak_name = '';
                $transaction = '';
                $date = '';
                $made_by = '';
                if($request->has('cheque_no')){
                    foreach($request->cheque_no as $cheque_no_key=>$cheque_no){
                        if($cheque_no_key == $key){
                            $chq_no = $cheque_no;
                        }
                    }
                }
                if($request->has('cheque_date')){
                    foreach($request->cheque_date as $cheque_date_key=>$cheque_date){
                        if($cheque_date_key == $key){
                            $chq_date = $cheque_date;
                        }
                    }
                }
                if($request->has('bank_name')){
                    foreach($request->bank_name as $bank_name_key=>$bank_name){
                        if($bank_name_key == $key){
                            $bak_name = $bank_name;
                        }
                    }
                }
                if($request->has('transaction_no')){
                    foreach($request->transaction_no as $transaction_no_key=>$transaction_no){
                        if($transaction_no_key == $key){
                            $transaction = $transaction_no;
                        }
                    }
                }
                if($request->has('payment_date')){
                    foreach($request->payment_date as $payment_date_key=>$payment_date){
                        if($payment_date_key == $key){
                            $date = date('Y-m-d',strtotime($payment_date));
                        }
                    }
                }
                if($request->has('payment_made_by')){
                    foreach($request->payment_made_by as $payment_made_by_key=>$payment_made_by){
                        if($payment_made_by_key == $key){
                            $made_by = $payment_made_by;
                        }
                    }
                }
                $policy_payment = new PolicyPayment();
                $policy_payment->policy_id      = $policy->id;
                $policy_payment->payment_type   = $payment_type;
                $policy_payment->cheque_no      = $chq_no;
                $policy_payment->cheque_date    = $chq_date;
                $policy_payment->bank_name      = $bak_name;
                $policy_payment->transaction_no = $transaction;
                $policy_payment->payment_date   = $date;
                $policy_payment->made_by        = $made_by;
                $policy_payment->status         = 1;
                $policy_payment->save();
            }
        }
        try {
            // Send notification to Admin
            $userSchema = User::first();
            $details = [
                'name'  => 'Policy Renew.',
                'type'  => 'Policy',
                'body'  => $policy->policy_no . ' renew by ' . Auth::user()->name,
                'url'   => route('admin.policies'),
            ];
            Notification::send($userSchema, new Notifications($details));
        
            if (Auth::user()->id != 1) {
                $userSchema11 = User::find(Auth::user()->id);
                if ($userSchema11) {
                    $details11 = [
                        'name'  => 'Policy Renew.',
                        'type'  => 'Policy',
                        'body'  => $policy->policy_no . ' renew by ' . Auth::user()->name,
                        'url'   => route('admin.policies'),
                    ];
                    Notification::send($userSchema11, new Notifications($details11));
                }
            }
        
            $agent = SourcingAgent::find($request->sourcing_agent);
            if ($agent) {
                $userSchema1 = User::find($agent->user_id);
                if ($userSchema1) {
                    $details1 = [
                        'name'  => 'Policy Renew.',
                        'type'  => 'Policy',
                        'body'  => $policy->policy_no . ' renew by ' . Auth::user()->name,
                        'url'   => route('admin.policies'),
                    ];
                    Notification::send($userSchema1, new Notifications($details1));
                }
            }
        }catch(\Exception $e){

        }
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.policies')->with('success', 'Policy Renew Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function policyHistory(Request $request, $id = NULL){
        $policy = Policy::where('id',$id)->first();
        $pypNo = $policy->policy_no;
        // echo $policy->pyp_no;
        // exit;
        if($policy->pyp_no != ''){
            $policies = Policy::getPoliciesRecursively($policy->pyp_no,$policy->id);
        }else{
            $policies = Policy::where('id',$id)->get();
        }
       
        // $policies = Policy::where('customer',$policy->customer)->where('insurance_type',$policy->insurance_type)->get();
        $business_source = BusinessSource::orderBy('id','Desc')->get();
        $page  = 'Policy History';
        $icon  = 'policy.png';
        $categories = Category::where('parent','==',0)->get();
        $sub_categories = Category::where('parent','!=',0)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        // if(Auth::user()->role == 1){
            return view('admin.policy.history',compact('policy','policies','business_source','page','icon','categories','sub_categories','companies','customers','plans'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function deletePolicyDocument($id){
        $policy = PolicyDocument::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Policy';
        $log->log       = 'Policy Document has been Deleted';
        $log->save();
        $policy->delete();
        return 1;
    }
    public function deletePolicyPayment($id){
        $policy = PolicyPayment::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Policy';
        $log->log       = 'Policy Payment has been Deleted';
        $log->save();
        $policy->delete();
        return 1;
    }
    public function cancelledPolicies(Request $request){
        if(Auth::user()->role == 1 || Auth::user()->role == 2){
            // $policies = Policy::latest()->get()->where('business_type',1)->where('status','==',2);
            $policies = Policy::latest()->get()->where('status','==',2);
        }else{
            // $policies = Policy::latest()->get()->where('business_type',1)->where('created_by',Auth::user()->id)->where('status','==',2);
            $policies = Policy::latest()->get()->where('created_by',Auth::user()->id)->where('status','==',2);
        }
        $business_source = BusinessSource::orderBy('id','Desc')->get();
        $page  = 'Cancelled Policy';
        $icon  = 'policy.png';
        $categories = Category::where('parent','==',0)->get();
        $sub_categories = Category::where('parent','!=',0)->get();
        $companies = Company::all();
        $customers = Customer::where('status',1)->get();
        $plans = Plan::where('status',1)->where('company',$companies[0]['id'])->get();
        // if(Auth::user()->role == 1){
            return view('admin.policy.cancel_policy',compact('policies','business_source','page','icon','categories','sub_categories','companies','customers','plans'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function cancelPolicy(Request $request){
        $policy = Policy::where('id',$request->id)->first();
        $policy->status         = 2;
        $policy->cancel_date    = $request->cancel_date;
        $policy->cancel_reason  = $request->reason;
        $policy->save();
        try{
            //send notification to Admin
            $userSchema = User::first();
            $details = [
                'name'  => 'Policy Cancelled.',
                'type'  => 'Policy',
                'body'  => $policy->policy_id.' '.'cancel by '. Auth::user()->name,
                'url'   => route('admin.policies'),
            ];
            Notification::send($userSchema, new Notifications($details));
            if(Auth::user()->id != 1){
                $userSchema = User::where('id',Auth::user()->id)->first();
                $details = [
                    'name'  => 'Policy Cancelled.',
                    'type'  => 'Policy',
                    'body'  => $policy->policy_id.' '.'cancel by '. Auth::user()->name,
                    'url'   => route('admin.policies'),
                ];
                Notification::send($userSchema, new Notifications($details));
            }
            $agent = SourcingAgent::where('id',$request->sourcing_agent)->first();
            $userSchema1 = User::where('id',$agent->user_id)->first();
            $details1 = [
                'name'  => 'Policy Cancelled.',
                'type'  => 'Policy',
                'body'  => $policy->policy_id.' '.'cancel by '. Auth::user()->name,
                'url'   => route('admin.policies'),
            ];
            Notification::send($userSchema1, new Notifications($details1));
        }catch(\Exception $e){

        }
        return redirect()->back()->with('success', 'Policy Cancelled Successfully.');
    }
    public function cancelPolicyReport(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        return Excel::download(new CancelPolicyExport($start_date, $end_date), 'cancelled-policies-report.xlsx');
    }

    public function removePolicyAttachment($id){
        PolicyAttachment::where('id',$id)->delete();
        return response()->json('success',200);
    }
}
