<?php

namespace App\Http\Controllers\API;

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
use App\Models\Parameter;
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
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use Illuminate\Support\LazyCollection;
use App\Exports\BusinessSourceReport;
use App\Exports\endorsementCSVExport;
use App\Exports\claimCSVExport;
use App\Exports\policyCSVExport;
use App\Exports\policyHealthCSVExport;
use App\Models\Endorsement;
use App\Models\Claim;
use PDF;
use Response;
use File;
use Storage; 
class ReportController extends Controller
{
    public function getMotorInsurancePolicyReport(Request $request){
        $type = 'CSV';
        if($request->has('fileFormat') && $request->fileFormat != ''){
            $type = $request->fileFormat;
        }
        $query = Policy::where('insurance_type',1)->orderBy('id','DESC');
        if($request->has('policyNo') && $request->policyNo != ''){
            $query->where('policy_no',$request->policyNo);
        }
        if($request->has('covernoteNo') && $request->covernoteNo != ''){
            $query->where('covernote_no',$request->covernoteNo);
        }
        if($request->has('vehicleMake') && $request->vehicleMake != ''){
            $query->where('vehicle_make',$request->vehicleMake);
        }
        if($request->has('vehicleModel') && $request->vehicleModel != ''){
            $query->where('vehicle_make',$request->vehicleModel);
        }
        if($request->has('vehicleRegistrationNo') && $request->vehicleRegistrationNo != ''){
            $query->where('vehicle_registration_no',$request->vehicleRegistrationNo);
        }
        if($request->has('vehicleChassisNo') && $request->vehicleChassisNo != ''){
            $query->where('vehicle_chassis_no',$request->vehicleChassisNo);
        }
        if($request->has('vehicleEngine') && $request->vehicleEngine != ''){
            $query->where('vehicle_engine',$request->vehicleEngine);
        }
        if($request->has('policyCreatedStartDate') && $request->policyCreatedStartDate != '' && $request->has('policyCreatedEndDate') && $request->policyCreatedEndDate != ''){
            $policyCreatedStartDate =  \DateTime::createFromFormat('d/m/Y',$request->policyCreatedStartDate);
            $policyCreatedEndDate =  \DateTime::createFromFormat('d/m/Y',$request->policyCreatedEndDate);
            $query->whereBetween('created_at',[$policyCreatedStartDate,$policyCreatedEndDate]);
        }
        if($request->has('policyExpiryStartDate') && $request->policyExpiryStartDate != '' && $request->has('policyExpiryEndDate') && $request->policyExpiryEndDate != ''){
            $policyExpiryStartDate =  \DateTime::createFromFormat('d/m/Y',$request->policyExpiryStartDate);
            $policyExpiryStartDate =  \DateTime::createFromFormat('d/m/Y',$request->policyExpiryStartDate);
            $query->whereBetween('risk_end_date',[$policyCreatedStartDate,$policyCreatedEndDate]);
        }
        if($request->has('sourcingAgentId') && $request->sourceAgentId != 0){
            $query->where('agent',$request->sourcingAgentId);
        }
        if($request->has('customerId') && $request->customerId != 0){
            $query->where('customer',$request->customerId);
        }
        if($request->has('insuranceCompanyId') && $request->insuranceCompanyId != 0){
            $query->where('company',$request->insuranceCompanyId);
        }
        if($request->has('subCategoryId') && $request->subCategoryId != 0){
            $query->where('sub_category',$request->subCategoryId);
        }
        if($request->has('categoryId') && $request->categoryId != 0){
            $query->where('category',$request->categoryId);
        }
        if($request->has('businessType') && $request->businessType != 0){
            $query->where('business_type',$request->businessType);
        }
        $this->applyFilters($query, $request);

        if ($type == 'CSV') {
            return $this->generateCSV($query);
        } else {
            return $this->generatePDF($query, $request);
        }
        $data = $query->get();
        // print_r($data);
        if($type == 'CSV'){
            $path = 'public/downloads/';
            $rand = File::allFiles(public_path('/downloads')); 
            Excel::store(new policyCSVExport($data), 'motor-policy-report-'.(count($rand)+1).'.csv','storageDisk');
            $file= url('/').'/public/downloads/motor-policy-report-'.(count($rand)+1).'.csv';
            return response()->json([
                'status'=> 200,
                'file'=> $file,
                'file_name' => 'motor_policy_report.csv',
            ]);
        }else{
            $records = [];
            if(!blank($data)){
                foreach($data as $source){
                    $customer = Customer::where('id',$source->customer)->first();
                    $records = [];
                    if($request->insurance_type == 2){
                        if($source->category == 1){
                            $category = 'Base';
                        }elseif($source->category == 2){
                            $category = 'Personal Accident';  
                        }else{
                            $category = 'Super Popup'; 
                        }
                        $plan = Plan::where('id',$source->health_plan)->first();
                        if(!blank($plan)){
                            $plan_name = $plan->name;
                        }else{
                            $plan_name = "";
                        }
                        $records[] = array(
                            'category'      =>  $category,
                            'plan_name'     =>  $plan_name,
                            'start_date'    =>  date('d-m-Y',strtotime($source->risk_start_date)),
                            'end_date'      =>  date('d-m-Y',strtotime($source->risk_end_date)),
                            'policy_no'     =>  $source->policy_no,
                            'customer'      =>  $source->customers->name,
                            'net_premium'   =>  $source->net_premium_amount
                        );
                    }else{
                        $category_data = Category::where('id',$source->sub_category)->first();
                        if(!blank($category_data)){
                            $category_name = $category_data->name;
                        }else{
                            $category_name = "";
                        }
                        if($source->business_type == 1){
                            $b_type = 'New';
                        }elseif($source->business_type == 2){
                            $b_type = 'Renew';
                        }elseif($source->business_type == 3){
                            $b_type = 'Rollover';
                        }else{
                            $b_type = 'Used';
                        }
                        $records[] = array(
                            'category'          =>  $category_name,
                            'business_type'     =>  $b_type,
                            'start_date'        =>  date('d-m-Y',strtotime($source->risk_start_date)),
                            'end_date'          =>  date('d-m-Y',strtotime($source->risk_end_date)),
                            'policy_no'         =>  $source->policy_no,
                            'customer'          =>  $source->customers->name,
                            'registration_no'   =>  $source->vehicle_registration_no,
                            'net_premium'       =>  $source->net_premium_amount
                        );
                    }
                }
            }
            $requests = [];
            $requests['policy_type']                = 1;
            $requests['policy_created_start_date']  = $request->policy_created_from;
            $requests['policy_created_end_date']    = $request->policy_created_to;
            $requests['policy_expiry_start_date']   = $request->policy_expiry_from;
            $requests['policy_expiry_end_date']     = $request->policy_expiry_to;
            $cmp = Company::where('id',$request->insurance_company)->first();
            if(!blank($cmp)){
                $company_name = $cmp->name;
            }else{
                $company_name = "";
            }
            $requests['insurance_company']  = $company_name;
            $requests['customer_name']      = $request->customer_name;
            if($request->business_type == 1){
                $b_type = 'New';
            }elseif($request->business_type == 2){
                $b_type = 'Renew';
            }elseif($request->business_type == 3){
                $b_type = 'Rollover';
            }else{
                $b_type = 'Used';
            }
            if($request->insurance_type == 2){
                if($source->category == 1){
                    $category = 'Base';
                }elseif($source->category == 2){
                    $category = 'Personal Accident';  
                }else{
                    $category = 'Super Popup'; 
                }
            }else{
                $category_data = Category::where('id',$request->sub_category)->first();
                if(!blank($category_data)){
                    $category = $category_data->name;
                }else{
                    $category = "";
                }
            }
            $requests['business_type']      = $b_type;
            $requests['category']           = $category;
            view()->share(['records'=>$records,'requests'=>$requests]);
            $customPaper = [0, 0, 567.00, 500.80];
            $pdf = PDF::loadView('admin.reports.policy_pdf_report', $records)->setPaper('a4', 'landscape');
            // return view('admin.payout.payout_pdf_report');
            // return $pdf->download('policy-report.pdf');
            $path = 'public/downloads/';
            // $rand = rand(1,100);
            $rand = File::allFiles(public_path('/downloads')); 
            $pdf->save($path  . 'motor-policy-report-'.(count($rand)+1).'.pdf');
            $file= url('/').'/public/downloads/motor-policy-report-'.(count($rand)+1).'.pdf';

            return response()->json([
                'status'=> 200,
                'file'=> $file,
                'file_name' => 'motor_policy_report.pdf',
            ]);
            // return Response::download($file, 'filename.pdf', $headers);
        }
    }
    private function applyFilters($query, $request)
    {
        $filters = [
            'policyNo' => 'policy_no',
            'covernoteNo' => 'covernote_no',
            'vehicleMake' => 'vehicle_make',
            'vehicleModel' => 'vehicle_model',
            'vehicleRegistrationNo' => 'vehicle_registration_no',
            'vehicleChassisNo' => 'vehicle_chassis_no',
            'vehicleEngine' => 'vehicle_engine',
            'sourcingAgentId' => 'agent',
            'customerId' => 'customer',
            'insuranceCompanyId' => 'company',
            'subCategoryId' => 'sub_category',
            'categoryId' => 'category',
            'businessType' => 'business_type',
        ];
    
        foreach ($filters as $requestKey => $dbColumn) {
            if ($request->filled($requestKey)) {
                $query->where($dbColumn, $request->input($requestKey));
            }
        }
    
        if ($request->filled(['policyCreatedStartDate', 'policyCreatedEndDate'])) {
            $start = \DateTime::createFromFormat('d/m/Y', $request->policyCreatedStartDate)->format('Y-m-d');
            $end = \DateTime::createFromFormat('d/m/Y', $request->policyCreatedEndDate)->format('Y-m-d');
            $query->whereBetween('created_at', [$start, $end]);
        }
    
        if ($request->filled(['policyExpiryStartDate', 'policyExpiryEndDate'])) {
            $start = \DateTime::createFromFormat('d/m/Y', $request->policyExpiryStartDate)->format('Y-m-d');
            $end = \DateTime::createFromFormat('d/m/Y', $request->policyExpiryEndDate)->format('Y-m-d');
            $query->whereBetween('risk_end_date', [$start, $end]);
        }
    }
    
    private function generateCSV($query)
    {
        $fileName = 'motor-policy-report-' . now()->format('YmdHis') . '.csv';
        Excel::store(new policyCSVExport($query), $fileName, 'public');
        $file = url('/storage/' . $fileName);
    
        return response()->json([
            'status' => 200,
            'file' => $file,
            'file_name' => 'motor_policy_report.csv',
        ]);
    }
    
    private function generatePDF($query, $request)
    {
        $records = [];
        $plans = Plan::pluck('name', 'id');
        $categories = Category::pluck('name', 'id');
    
        $query->with(['customers', 'company', 'category'])->chunk(100, function ($policies) use (&$records, $plans, $categories) {
            foreach ($policies as $policy) {
                $records[] = [
                    'category' => $categories[$policy->sub_category] ?? '',
                    'business_type' => $this->getBusinessType($policy->business_type),
                    'start_date' => date('d-m-Y', strtotime($policy->risk_start_date)),
                    'end_date' => date('d-m-Y', strtotime($policy->risk_end_date)),
                    'policy_no' => $policy->policy_no,
                    'customer' => $policy->customers->name ?? '',
                    'registration_no' => $policy->vehicle_registration_no,
                    'net_premium' => $policy->net_premium_amount
                ];
            }
        });
    
        $requests = $this->prepareRequestData($request);
    
        $pdf = PDF::loadView('admin.reports.policy_pdf_report', compact('records', 'requests'))->setPaper('a4', 'landscape');
    
        $fileName = 'motor-policy-report-' . now()->format('YmdHis') . '.pdf';
        $pdf->save(public_path('downloads/' . $fileName));
        $file = url('/public/downloads/' . $fileName);
    
        return response()->json([
            'status' => 200,
            'file' => $file,
            'file_name' => 'motor_policy_report.pdf',
        ]);
    }
    
    private function prepareRequestData($request)
    {
        return [
            'policy_type' => 1,
            'policy_created_start_date' => $request->policyCreatedStartDate,
            'policy_created_end_date' => $request->policyCreatedEndDate,
            'policy_expiry_start_date' => $request->policyExpiryStartDate,
            'policy_expiry_end_date' => $request->policyExpiryEndDate,
            'insurance_company' => Company::find($request->insuranceCompanyId)->name ?? '',
            'customer_name' => $request->customerName,
            'business_type' => $this->getBusinessType($request->businessType),
            'category' => Category::find($request->categoryId)->name ?? '',
        ];
    }
    public function getHealthInsurancePolicyReport(Request $request) {
        $type = 'CSV';
        if ($request->has('fileFormat') && $request->fileFormat != '') {
            $type = $request->fileFormat;
        }
        $query = Policy::where('insurance_type', 2)->orderBy('id', 'DESC');
    
        $this->applyHealthPolicyFilters($query, $request);
    
        if ($type == 'CSV') {
            return $this->generateHealthCSV($query);
        } else {
            return $this->generateHealthPDF($query, $request);
        }
    }
    
    private function applyHealthPolicyFilters($query, $request)
    {
        $filters = [
            'policyNo' => 'policy_no',
            'covernoteNo' => 'covernote_no',
            'sourcingAgentId' => 'agent',
            'customerId' => 'customer',
            'insuranceCompanyId' => 'company',
            'subCategoryId' => 'sub_category',
            'categoryId' => 'category',
            'businessType' => 'business_type',
            'planId' => 'health_plan',
        ];
    
        foreach ($filters as $requestKey => $dbColumn) {
            if ($request->filled($requestKey)) {
                $query->where($dbColumn, $request->input($requestKey));
            }
        }
    
        if ($request->filled(['policyCreatedStartDate', 'policyCreatedEndDate'])) {
            $start = \DateTime::createFromFormat('d/m/Y', $request->policyCreatedStartDate)->format('Y-m-d');
            $end = \DateTime::createFromFormat('d/m/Y', $request->policyCreatedEndDate)->format('Y-m-d');
            $query->whereBetween('created_at', [$start, $end]);
        }
    
        if ($request->filled(['policyExpiryStartDate', 'policyExpiryEndDate'])) {
            $start = \DateTime::createFromFormat('d/m/Y', $request->policyExpiryStartDate)->format('Y-m-d');
            $end = \DateTime::createFromFormat('d/m/Y', $request->policyExpiryEndDate)->format('Y-m-d');
            $query->whereBetween('risk_end_date', [$start, $end]);
        }
    }
    
    private function generateHealthCSV($query)
    {
        $fileName = 'health-policy-report-' . now()->format('YmdHis') . '.csv';
        Excel::store(new PolicyHealthCSVExport($query->get()), $fileName, 'public');
        $file = url('/storage/' . $fileName);
    
        return response()->json([
            'status' => 200,
            'file' => $file,
            'file_name' => 'health_policy_report.csv',
        ]);
    }
    
    private function generateHealthPDF($query, $request)
    {
        $records = [];
        $plans = Plan::pluck('name', 'id');
        $categories = Category::pluck('name', 'id');
    
        $query->with(['customers', 'company', 'category'])->chunk(100, function ($policies) use (&$records, $plans, $categories) {
            foreach ($policies as $policy) {
                $records[] = [
                    'category' => $categories[$policy->sub_category] ?? '',
                    'business_type' => $this->getBusinessType($policy->business_type),
                    'start_date' => date('d-m-Y', strtotime($policy->risk_start_date)),
                    'end_date' => date('d-m-Y', strtotime($policy->risk_end_date)),
                    'policy_no' => $policy->policy_no,
                    'customer' => $policy->customers->name ?? '',
                    'net_premium' => $policy->net_premium_amount,
                    'plan_name' => $plans[$policy->health_plan] ?? ''
                ];
            }
        });
    
        $requests = $this->prepareHealthRequestData($request);
    
        $pdf = PDF::loadView('admin.reports.policy_pdf_report', compact('records', 'requests'))->setPaper('a4', 'landscape');
    
        $fileName = 'health-policy-report-' . now()->format('YmdHis') . '.pdf'; 
        $pdf->save(public_path('downloads/' . $fileName));
        $file = url('/public/downloads/' . $fileName);
    
        return response()->json([
            'status' => 200,
            'file' => $file,
            'file_name' => 'health_policy_report.pdf',
        ]);
    }
    
    private function prepareHealthRequestData($request)
    {
        return [
            'policy_type' => 2,
            'policy_created_start_date' => $request->policyCreatedStartDate,
            'policy_created_end_date' => $request->policyCreatedEndDate,
            'policy_expiry_start_date' => $request->policyExpiryStartDate,
            'policy_expiry_end_date' => $request->policyExpiryEndDate,
            'insurance_company' => Company::find($request->insuranceCompanyId)->name ?? '',
            'customer_name' => $request->customerName,
            'business_type' => $this->getBusinessType($request->businessType),
            'category' => Category::find($request->categoryId)->name ?? '',
        ];
    }
    
    private function getBusinessType($type)
    {
        switch ($type) {
            case 1: return 'New';
            case 2: return 'Renew';
            case 3: return 'Rollover';
            default: return 'Used';
        }
    }

    public function getMotorEndorsementReport(Request $request){
        $type = 'CSV';
        if($request->has('fileFormat') && $request->fileFormat != ''){
            $type = $request->fileFormat;
        }
        $endorsement = Endorsement::orderBy('id','DESC');
        $endorsement->whereHas('policy', function($query1) use ($request){
            $query1->where('insurance_type', 1);
        });
        if($request->endorsementStartDate != '' && $request->endorsementEndDate != ''){
            $start_date =  \DateTime::createFromFormat('d/m/Y',$request->endorsementStartDate);
            $end_date =  \DateTime::createFromFormat('d/m/Y',$request->endorsementEndDate);
            $endorsement->whereBetween('created_at',[$start_date,$end_date]);
        }
        if($request->companyId != 0){
            $endorsement->whereHas('policy', function($query1) use ($request){
                    $query1->where('company', $request->companyId);
            });
        }
        if($request->customerId != ''){
            $endorsement->whereHas('policy', function($query1) use ($request){
                $query1->where('customer', $request->customerId);
            });
        }
        if($request->has('planId') && $request->planId != ''){
            $endorsement = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('health_plan', $request->planId);
            });
        }
        if($request->has('endorsement_details') && $request->endorsement_details != ''){
            $endorsement->where('details','LIKE','%'.$request->endorsement_details.'%');
        }
        $data = $endorsement->get();
        if($type == 'CSV'){
            $path = 'public/downloads/';
            $rand = File::allFiles(public_path('/downloads')); 
            Excel::store(new endorsementCSVExport($data), 'motor-endorsement-report-'.(count($rand)+1).'.csv','storageDisk');
            $file= url('/').'/public/downloads/motor-endorsement-report-'.(count($rand)+1).'.csv';
            return response()->json([
                'status'=> 200,
                'file'=> $file,
                'file_name' => 'motor_endorsement_report.csv',
            ]);
        }else{
            $records = [];
            if(!blank($data)){
                foreach($data as $source){
                    $company = Company::where('id',$source->policy->company)->first();
                    if(!blank($company)){
                        $cmp = $company->name;
                    }else{
                        $cmp = '';
                    }
                    $customer = Customer::where('id',$source->policy->customer)->first();
                    $records[] = [
                        'endorsement'       =>  date('d-m-Y',strtotime($source->created_at)),
                        'company_name'      =>  $cmp,
                        'details'           =>  $source->details,
                        'policy_no'         =>  $source->policy->policy_no,
                        'customer_name'     =>  $customer->name,
                    ];
                }
            }
            $requests = [];
            $requests['start_date'] = $request->endorsementStartDate;
            $requests['end_date'] = $request->endorsementEndDate;
            $cmp = Company::where('id',$request->companyId)->first();
            if(!blank($cmp)){
                $company_name = $cmp->name;
            }else{
                $company_name = "";
            }
            $requests['insurance_company'] = $company_name;
            $cust = Customer::where('id',$request->customerId)->first();
            if(!blank($cust)){
                $customer_name = $cust->name;
            }else{
                $customer_name = "";
            }
            $requests['customer_name'] = $customer_name;
            $requests['endorsement_details'] = $request->endorsement_details;

            view()->share(['records'=>$records,'requests'=>$requests]);
            $pdf = PDF::loadView('admin.reports.endorsement_pdf_report', $records)->setPaper('a4', 'landscape');
            // return view('admin.payout.payout_pdf_report');
            // return $pdf->download('endorsement-report.pdf');
            $path = 'public/downloads/';
            // $rand = rand(1,100);
            $rand = File::allFiles(public_path('/downloads')); 
            $pdf->save($path  . 'motor-endorsement-report-'.(count($rand)+1).'.pdf');
            $file= url('/').'/public/downloads/motor-endorsement-report-'.(count($rand)+1).'.pdf';

            return response()->json([
                'status'=> 200,
                'file'=> $file,
                'file_name' => 'motor_endorsement_report.pdf',
            ]);
        }
    }
    public function getHealthEndorsementReport(Request $request){
        $type = 'CSV';
        if($request->has('fileFormat') && $request->fileFormat != ''){
            $type = $request->fileFormat;
        }
        $endorsement = Endorsement::orderBy('id','DESC');
        $endorsement->whereHas('policy', function($query1) use ($request){
            $query1->where('insurance_type', 2);
        });
        if($request->endorsementStartDate != '' && $request->endorsementEndDate != ''){
            $start_date =  \DateTime::createFromFormat('d/m/Y',$request->endorsementStartDate);
            $end_date =  \DateTime::createFromFormat('d/m/Y',$request->endorsementEndDate);
            $endorsement->whereBetween('created_at',[$start_date,$end_date]);
        }
        if($request->companyId != 0){
            $endorsement->whereHas('policy', function($query1) use ($request){
                    $query1->where('company', $request->companyId);
            });
        }
        if($request->customerId != ''){
            $endorsement->whereHas('policy', function($query1) use ($request){
                $query1->where('customer', $request->customerId);
            });
        }
        if($request->has('planId') && $request->planId != ''){
            $endorsement = $endorsement->whereHas('policy', function($query1) use ($request){
                $query1->where('health_plan', $request->planId);
            });
        }
        if($request->has('endorsement_details') && $request->endorsement_details != ''){
            $endorsement->where('details','LIKE','%'.$request->endorsement_details.'%');
        }
        $data = $endorsement->get();
        if($type == 'CSV'){
            $path = 'public/downloads/';
            $rand = File::allFiles(public_path('/downloads')); 
            Excel::store(new endorsementCSVExport($data), 'health-endorsement-report-'.(count($rand)+1).'.csv','storageDisk');
            $file= url('/').'/public/downloads/health-endorsement-report-'.(count($rand)+1).'.csv';
            return response()->json([
                'status'=> 200,
                'file'=> $file,
                'file_name' => 'health_endorsement_report.csv',
            ]);
        }else{
            $records = [];
            if(!blank($data)){
                foreach($data as $source){
                    $company = Company::where('id',$source->policy->company)->first();
                    if(!blank($company)){
                        $cmp = $company->name;
                    }else{
                        $cmp = '';
                    }
                    $customer = Customer::where('id',$source->policy->customer)->first();
                    $records[] = [
                        'endorsement'       =>  date('d-m-Y',strtotime($source->created_at)),
                        'company_name'      =>  $cmp,
                        'details'           =>  $source->details,
                        'policy_no'         =>  $source->policy->policy_no,
                        'customer_name'     =>  $customer->name,
                    ];
                }
            }
            $requests = [];
            $requests['start_date'] = $request->endorsementStartDate;
            $requests['end_date'] = $request->endorsementEndDate;
            $cmp = Company::where('id',$request->companyId)->first();
            if(!blank($cmp)){
                $company_name = $cmp->name;
            }else{
                $company_name = "";
            }
            $requests['insurance_company'] = $company_name;
            $cust = Customer::where('id',$request->customerId)->first();
            if(!blank($cust)){
                $customer_name = $cust->name;
            }else{
                $customer_name = "";
            }
            $requests['customer_name'] = $customer_name;
            $requests['endorsement_details'] = $request->endorsement_details;

            view()->share(['records'=>$records,'requests'=>$requests]);
            $pdf = PDF::loadView('admin.reports.endorsement_pdf_report', $records)->setPaper('a4', 'landscape');
            // return view('admin.payout.payout_pdf_report');
            // return $pdf->download('endorsement-report.pdf');
            $path = 'public/downloads/';
            // $rand = rand(1,100);
            $rand = File::allFiles(public_path('/downloads')); 
            $pdf->save($path  . 'health-endorsement-report-'.(count($rand)+1).'.pdf');
            $file= url('/').'/public/downloads/health-endorsement-report-'.(count($rand)+1).'.pdf';

            return response()->json([
                'status'=> 200,
                'file'=> $file,
                'file_name' => 'health_endorsement_report.pdf',
            ]);
        }
    }
    public function getMotorClaimReport(Request $request){
        $type = 'CSV';
        if($request->has('fileFormat') && $request->fileFormat != ''){
            $type = $request->fileFormat;
        }
        $claim = Claim::orderBy('id','DESC');
        $claim->whereHas('policy', function($query1) use ($request){
            $query1->where('insurance_type', 1);
        });
        if($request->claimStartDate != '' && $request->claimEndDate != ''){
            $start_date =  \DateTime::createFromFormat('d/m/Y',$request->claimStartDate);
            $end_date =  \DateTime::createFromFormat('d/m/Y',$request->claimEndDate);
            $claim->whereBetween('created_at',[$start_date,$end_date]);
        }
        if($request->has($request->claim_status)){
            $claim->where('claim_status',$request->claim_status);
        }
        if($request->has($request->claim_type)){
            $claim->where('claim_type',$request->claim_type);
        }
        if($request->companyId != 0){
            $claim->whereHas('policy', function($query1) use ($request){
                    $query1->where('company', $request->companyId);
            });
        }
        if($request->customer_Id != ''){
            $claim->whereHas('policy', function($query1) use ($request){
                $query1->where('customer',$request->customerId);
            });
        }
        $data = $claim->get();
        if($type == 'CSV'){
            $path = 'public/downloads/';
            $rand = File::allFiles(public_path('/downloads')); 
            Excel::store(new claimCSVExport($data), 'motor-claim-report-'.(count($rand)+1).'.csv','storageDisk');
            $file= url('/').'/public/downloads/motor-claim-report-'.(count($rand)+1).'.csv';
            return response()->json([
                'status'=> 200,
                'file'=> $file,
                'file_name' => 'motor_claim_report.csv',
            ]);
        }else{
            $records = [];
            if(!blank($data)){
                foreach($data as $source){
                    $company = Company::where('id',$source->policy->company)->first();
                    if(!blank($company)){
                        $cmp = $company->name;
                    }else{
                        $cmp = '';
                    }
                    $customer = Customer::where('id',$source->policy->customer)->first();
                    $records[] = [
                        'claim_no'          =>  $source->claim_no,
                        'claim_date'        =>  date('d-m-Y',strtotime($source->created_at)),
                        'company_name'      =>  $cmp,
                        'claim_type'        =>  $source->claim_type,
                        'claim_status'      =>  $source->claim_status,
                        'policy_no'         =>  $source->policy->policy_no,
                        'customer_name'     =>  $customer->name,
                    ];
                }
            }
            $requests = [];
            $requests['start_date'] = $request->claim_start_date;
            $requests['end_date'] = $request->claim_start_date;
            $cmp = Company::where('id',$request->insurance_company)->first();
            if(!blank($cmp)){
                $company_name = $cmp->name;
            }else{
                $company_name = "";
            }
            $requests['insurance_company'] = $company_name;
            $requests['customer_name'] = $request->customer_name;
            $requests['claim_type'] = $request->claim_type;
            $requests['claim_status'] = $request->claim_status;
            view()->share(['records'=>$records,'requests'=>$requests]);
            $customPaper = [0, 0, 567.00, 500.80];
            $pdf = PDF::loadView('admin.reports.claim_pdf_report', $records)->setPaper('a4', 'landscape');
            // return view('admin.payout.payout_pdf_report');
            $path = 'public/downloads/';
            // $rand = rand(1,100);
            $rand = File::allFiles(public_path('/downloads')); 
            $pdf->save($path  . 'motor-claim-report-'.(count($rand)+1).'.pdf');
            $file= url('/').'/public/downloads/motor-claim-report-'.(count($rand)+1).'.pdf';
            return response()->json([
                'status'=> 200,
                'file'=> $file,
                'file_name' => 'motor_claim_report.pdf',
            ]);
        }
    }
    public function getHealthClaimReport(Request $request){
        $type = 'CSV';
        if($request->has('fileFormat') && $request->fileFormat != ''){
            $type = $request->fileFormat;
        }
        $claim = Claim::orderBy('id','DESC');
        $claim->whereHas('policy', function($query1) use ($request){
            $query1->where('insurance_type', 2);
        });
        if($request->claimStartDate != '' && $request->claimEndDate != ''){
            $start_date =  \DateTime::createFromFormat('d/m/Y',$request->claimStartDate);
            $end_date =  \DateTime::createFromFormat('d/m/Y',$request->claimEndDate);
            $claim->whereBetween('created_at',[$start_date,$end_date]);
        }
        if($request->has($request->claim_status)){
            $claim->where('claim_status',$request->claim_status);
        }
        if($request->has($request->claim_type)){
            $claim->where('claim_type',$request->claim_type);
        }
        if($request->has('planId') && $request->planId != ''){
            $claim = $query->whereHas('policy', function($query1) use ($request){
                $query1->where('health_plan', $request->planId);
            });
        }
        if($request->companyId != 0){
            $claim->whereHas('policy', function($query1) use ($request){
                    $query1->where('company', $request->companyId);
            });
        }
        if($request->customer_Id != ''){
            $claim->whereHas('policy', function($query1) use ($request){
                $query1->where('customer',$request->customerId);
            });
        }
        $data = $claim->get();
        if($type == 'CSV'){
            $path = 'public/downloads/';
            $rand = File::allFiles(public_path('/downloads')); 
            Excel::store(new claimCSVExport($data), 'health-claim-report-'.(count($rand)+1).'.csv','storageDisk');
            $file= url('/').'/public/downloads/health-claim-report-'.(count($rand)+1).'.csv';
            return response()->json([
                'status'=> 200,
                'file'=> $file,
                'file_name' => 'health_claim_report.csv',
            ]);
        }else{
            $records = [];
            if(!blank($data)){
                foreach($data as $source){
                    $company = Company::where('id',$source->policy->company)->first();
                    if(!blank($company)){
                        $cmp = $company->name;
                    }else{
                        $cmp = '';
                    }
                    $customer = Customer::where('id',$source->policy->customer)->first();
                    $records[] = [
                        'claim_no'          =>  $source->claim_no,
                        'claim_date'        =>  date('d-m-Y',strtotime($source->created_at)),
                        'company_name'      =>  $cmp,
                        'claim_type'        =>  $source->claim_type,
                        'claim_status'      =>  $source->claim_status,
                        'policy_no'         =>  $source->policy->policy_no,
                        'customer_name'     =>  $customer->name,
                    ];
                }
            }
            $requests = [];
            $requests['start_date'] = $request->claim_start_date;
            $requests['end_date'] = $request->claim_start_date;
            $cmp = Company::where('id',$request->insurance_company)->first();
            if(!blank($cmp)){
                $company_name = $cmp->name;
            }else{
                $company_name = "";
            }
            $requests['insurance_company'] = $company_name;
            $requests['customer_name'] = $request->customer_name;
            $requests['claim_type'] = $request->claim_type;
            $requests['claim_status'] = $request->claim_status;
            view()->share(['records'=>$records,'requests'=>$requests]);
            $pdf = PDF::loadView('admin.reports.claim_pdf_report', $records)->setPaper('a4', 'landscape');
            // return view('admin.payout.payout_pdf_report');
            $path = 'public/downloads/';
            // $rand = rand(1,100);
            $rand = File::allFiles(public_path('/downloads')); 
            $pdf->save($path  . 'health-claim-report-'.(count($rand)+1).'.pdf');
            $file= url('/').'/public/downloads/health-claim-report-'.(count($rand)+1).'.pdf';
            return response()->json([
                'status'=> 200,
                'file'=> $file,
                'file_name' => 'health_claim_report.pdf',
            ]);
        }
    }
}
                                       