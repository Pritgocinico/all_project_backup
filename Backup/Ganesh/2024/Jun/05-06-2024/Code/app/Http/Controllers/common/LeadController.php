<?php

namespace App\Http\Controllers\common;
use App\Models\AdditionalProjectDetail;
use Nnjeim\World\WorldHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use App\Models\City;
use App\Models\Customer;
use App\Models\Log;
use App\Models\MeasurementTask;
use Nnjeim\World\World;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;
use App\Http\Helpers\SmsHelper;

class LeadController extends Controller
{
    protected $world;
    public function __construct() {
        $setting=Setting::first();
        // $user = User::first();
        view()->share('setting', $setting);
    }

    public function leads(){
        $page= 'Leads';
        // $users = User::where('role','=',2)->get();
        $customers = Customer::all();
        $leads = Project::with('customer')->orderBy('id','DESC')->where('type',0)->get();
        $type = 'Lead';
        if(Auth::user()->role == 1){
            return view('admin/leads/leads',compact('page','leads','customers','type'));
        }else{
            return view('quotation/leads/leads',compact('page','leads','customers','type'));
        }
    }

    public function addleads(WorldHelper $world , Request $req){
        $this->world = $world;
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);
        
        if ($state_action->success) {
            $states = $state_action->data;
        }

        // $cities = City::all();
        $customers = Customer::all();
        $measurement_users = User::where('role',3)->whereNot('id',1)->get();
        $page= 'Add Leads';
        $leads = Lead::all();
        $type = 'Lead';
        if(Auth::user()->role == 1){
            return view('admin/leads/add_leads',compact('page' ,'leads','customers','type','states', 'measurement_users'));
        }else{
            return view('quotation/leads/add_leads',compact('page' ,'leads','customers','type','states', 'measurement_users'));
        }
    }

    public function store_leads_data(Request $request){
        // echo '<pre>';
        // print_r($request->All());
        // exit;
        $request->validate([
            'customer_name'     => 'required|not_in:0',
            // 'reference_name'    => 'required',
            // 'reference_number'  => 'required',
            'measurementdate'   => 'required',
            'phone_number'      => 'required',
        ]);
        // dd($request->all());
        $currentMonthYear = date('Ym');
        $leadCount = Project::whereRaw('DATE_FORMAT(created_at, "%Y%m") = ?', [$currentMonthYear])->count();
        $leadCount++;
        $leadIdPadding = str_pad($leadCount, 5, '0', STR_PAD_LEFT);
        $leadNo = 'SGA_LD' . '_' . $leadIdPadding;
            // Check if the lead number already exists
            while (Project::where('lead_no', $leadNo)->exists()) {
                // If it exists, increment the count and regenerate the lead number
                $leadCount++;
                $leadIdPadding = str_pad($leadCount, 5, '0', STR_PAD_LEFT);
                $leadNo = 'SGA_LD' . '_' . $leadIdPadding;
            }
        $date = Carbon::createFromFormat('d/m/Y', $request->measurementdate)->format('Y-m-d');
        $leads = $request->all();
        $leads = new Project();
        $leads->lead_no             = $leadNo;
        $leads->reference_name      = $request->reference_name;
        $leads->reference_phone     = $request->reference_number;
        $leads->customer_id         = $request->customer_name;
        $leads->phone_number        = $request->phone_number;
        $leads->email               = $request->email;
        $leads->address             = $request->addressone;
        $leads->statename           = $request->state;
        $leads->cityname            = $request->city;
        $leads->zipcode             = $request->zipcode;
        $leads->description         = $request->description;
        $leads->measurement_date    = $date;
        $leads->lead_status         = 1;
        $leads->created_by          = Auth()->user()->id;
        $leads->save();

        if(!blank($request->measurement_user)){
            $measurementUsers = $request->measurement_user;
            foreach($measurementUsers as $key=>$user){
                $measurementTask = new MeasurementTask();
                $measurementTask->project_id = $leads->id;
                $measurementTask->user_id = $user;
                $measurementTask->save();
                $userDetail = User::where('id',$user)->first();
                try {
                    $mobileNumber = $userDetail->phone;
                    $message = "Dear " . $userDetail->name . ", your lead " . $leads->lead_no . " has been added to our system. - Shree Ganesh Aluminum";
                    $templateid = '1407171593766914336';
                    $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, false);
                    
                } catch (Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                }
            } 
        }

        $customer = Customer::where('id', $request->customer_name)->first();
        $customer_name = $customer->name;
        if(Auth::user()->role == 1){
            $user = User::where('id',Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Lead Added - ',
                'text' => 'Name: '.$leads->lead_no.'-'.$customer_name,
                'url' => route('view.lead',$leads->id),
            ];
    
            Notification::send($user, new OffersNotification($notificationData));
        }else{
            $user = User::where('id',Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Lead Added - ',
                'text' => 'Name: '.$leads->lead_no.'-'.$customer_name,
                'url' => route('quotation_view.lead',$leads->id),
            ];
    
            Notification::send($user, new OffersNotification($notificationData));
        }

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Lead';
        $log->log       = 'Lead Added';
        $log->save();
        
        try {
                $mobileNumber = $leads->phone_number;
                $message = "Dear " . $customer->name . ", your lead " . $leads->lead_no . " has been added to our system. - Shree Ganesh Aluminum";
                $templateid = '1407171593766914336';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                
            } catch (Exception $e) {
                echo 'Message: ' . $e->getMessage();
            }
        
        if(Auth::user()->role == 1){
            return redirect()->route('leads')->with('success','Lead created successfully.');
        }else{
            return redirect()->route('quotation_leads')->with('success','Lead created successfully.');
        }
    }

    public function deleteleads($id){
        $leads = Project::find($id);
        $lead->status = 0;
        $leads->save();

        $customer = Customer::where('id', $leads->customer_id)->first();
        $customer_name = $customer->name; 
        if(Auth::user()->role == 1){
            $user = User::where('id',Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Lead Deleted - ',
                'text' => 'Name: '.$leads->lead_no.'-'.$customer_name,
                'url' => route('leads'),
            ];
    
            Notification::send($user, new OffersNotification($notificationData));
        }else{
            $user = User::where('id',Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Lead Deleted - ',
                'text' => 'Name: '.$leads->lead_no.'-'.$customer_name,
                'url' => route('quotation_leads'),
            ];
    
            Notification::send($user, new OffersNotification($notificationData));
        }
         

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Lead';
        $log->log       = 'Lead Deleted';
        $log->save();
        return response()->json("success", 200);
    }

    public function editleads(WorldHelper $world, $id = NULL){
        $leads = Project::where('id',$id)->first();
        $this->world = $world;
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);
        
        if ($state_action->success) {
            $states = $state_action->data;
        }

        $cities = City::where('state_id', $leads->statename)->get();
        // dd($cities);
        $customers = Customer::all();
        $page= 'Edit Leads';
        $type = 'Lead';
        if(Auth::user()->role == 1){
            return view('admin.leads.edit_lead',compact('leads','page','customers','cities','type','states'));
        }else{
            return view('quotation.leads.edit_lead',compact('leads','page','customers','cities','type','states'));
        }
    }

    public function updateleads(Request $request , $id){
        $request->validate([
            'customer_name'     => 'required',
            'reference_name'    => 'required',
            'phone_number'      => 'required',
        ]);
        $date = Carbon::createFromFormat('d/m/Y', $request->measurementdate)->format('Y-m-d');
        $leads = Project::find($id);
        $leads->reference_name      = $request->reference_name;
        $leads->reference_phone     = $request->reference_number;
        $leads->customer_id         = $request->customer_name;
        $leads->phone_number        = $request->phone_number;
        $leads->email               = $request->email;
        $leads->address             = $request->addressone;
        $leads->description         = $request->description;
        $leads->statename           = $request->state;
        $leads->cityname            = $request->city;
        $leads->zipcode             = $request->zipcode;
        $leads->measurement_date    = $date;
        $leads->save();

        $customer = Customer::where('id', $request->customer_name)->first();
        $customer_name = $customer->name; 
        if(Auth::user()->role == 1){
            $user = User::where('id',Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Lead Updated - ',
                'text' => 'Name: '.$leads->lead_no.'-'.$customer_name,
                'url' => route('view.lead',$leads->id),
            ];
    
            Notification::send($user, new OffersNotification($notificationData));
        }else{
            $user = User::where('id',Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Lead Updated - ',
                'text' => 'Name: '.$leads->lead_no.'-'.$customer_name,
                'url' => route('quotation_view.lead',$leads->id),
            ];
    
            Notification::send($user, new OffersNotification($notificationData));
        }
        

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Lead';
        $log->log       = 'Lead Updated';
        $log->save();

        if(Auth::user()->role == 1){
            return redirect()->route('leads')->with('success','Lead created successfully.');
        }else{
            return redirect()->route('quotation_leads')->with('success','Lead created successfully.');
        }
    }
    public function viewLead(WorldHelper $world, Request $request, $id)
    {
        $this->world = $world;
        $action = $this->world->cities([
            'filters' => [
                'state_id' => 1650,
            ],
        ]);
        if ($action->success) {
            $cities = $action->data;
        }
        $users = Customer::all();
        $business = User::where('role', '=', 7)->get();
        $page = 'View Lead';
        $projects = Lead::findOrFail($id);
        $customer = Customer::where('id',$projects->customer_id)->first();
        $issueProject = AdditionalProjectDetail::where('project_id',$projects->id)->where('status','issue')->first();
        if($projects->type == 1){
            $type = 'Project';
        }else{
            $type = 'Lead';
        }
        if(Auth::user()->role == 1){
            return view('admin.leads.view_project', compact('issueProject','page', 'projects','customer', 'users', 'cities'));
        }else{
            return view('quotation.leads.view_project', compact('issueProject','page', 'projects','customer', 'users', 'cities'));
        }
    }
    public function convertToProject($leadId)
    {
        $lead = Lead::findOrFail($leadId);
        $project = new Project();
        $project->project_name = $lead->project_name;
        $project->customer_id = $lead->customer_name;
        $project->description = $lead->description;
        $project->save();
        if(Auth::user()->role == 1){
            return redirect()->route('leads')->with('success', 'Lead converted to project successfully');
        }else{
            return redirect()->route('quotation_leads')->with('success', 'Lead converted to project successfully');
        }
    }
}
