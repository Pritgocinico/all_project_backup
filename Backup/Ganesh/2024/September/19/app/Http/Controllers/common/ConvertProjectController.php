<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City; 
use App\Models\Setting;
use Nnjeim\World\World;
use App\Models\Project;
use App\Models\Lead;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;

class ConvertProjectController extends Controller
{
    protected $world;
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function create($id = NULL)
    {
        $cities = City::where('state_id', 1650)->get();
        // $users = User::where('role', '=', 2)->get();
        $customers = Customer::all();
        // $business = User::where('role', '=', 7)->get();
        $leads = Lead::where('id',$id)->first();
        return view('admin.leads.convert_project', compact('cities', 'customers','leads'));
    } 
    public function store(Request $request)
    {
        $request->validate([
            // 'businessType' => 'required|in:b2c,b2b',
            // 'customer_name' => 'required_if:businessType,b2c',
            // 'business_name' => 'required_if:businessType,b2b',
            // 'gst_number' => 'required_if:businessType,b2b',
            // 'phone_number' => 'required|string',
            // 'email' => 'required|email',
            // 'address' => 'required|string',
            // 'projectconfirmdate' => 'required|date',
            // 'startdaterangepicker' => 'required|date',
            // 'enddaterangepicker' => 'required|date',
            // 'measurementdate' => 'required|date',
            // 'reference_name' => 'nullable|string',
            // 'reference_phone' => 'nullable|string',
            // 'description' => 'required|string',
        ]);

        $project = new Project();
        if ($request->input('businessType') == 'b2b') {
            $project->customer_id = $request->input('business_name') !== 'Select Business'
                ? $request->input('business_name')
                : null;
        } else {
            $project->customer_id = $request->input('customer_name') !== 'Select Customer'
                ? $request->input('customer_name')
                : null;
        }
        $project->business_type = $request->input('businessType');
        $project->gst_number = $request->input('gst_number');
        $project->phone_number = $request->input('phone_number');
        $project->email = $request->input('email');
        $project->address = $request->input('address');
        // $project->project_confirm_date = Carbon::createFromFormat('d-m-Y', $request->input('projectconfirmdate'))->toDateString();
        $project->start_date = Carbon::createFromFormat('d-m-Y', $request->input('startdaterangepicker'))->toDateString();
        // $project->end_date = Carbon::createFromFormat('d-m-Y', $request->input('enddaterangepicker'))->toDateString();
        $project->measurement_date = Carbon::createFromFormat('d-m-Y', $request->input('measurementdate'))->toDateString();
        $project->reference_name = $request->input('reference_name');
        $project->reference_phone = $request->input('reference_phone');
        $project->description = $request->input('description');
        $project->architecture_name = $request->input('architecture_name');
        $project->architecture_number = $request->input('architecture_number');
        $project->supervisor_name = $request->input('supervisor_name');
        $project->supervisor_number = $request->input('supervisor_number');

        $project->save();

        $currentMonthYear = date('Ym');
        $projectCount = Project::whereRaw('DATE_FORMAT(created_at, "%Y%m") = ?', [$currentMonthYear])->count();
        $projectCount++;
        $projectIdPadding = str_pad($projectCount, 5, '0', STR_PAD_LEFT);
        $project->project_generated_id = 'SGA_' . strtolower(date('M', strtotime($project->created_at))) . '_' . $projectIdPadding;
        $project->update();

        $lead = Lead::where('id',$request->lead_id)->delete();
        $request->session()->flash('success', 'Lead converted to project successfully!');
        return redirect()->route('leads')->with('success', 'Project created successfully');
    }
    // Helper method to get cities (replace with your logic)
    private function getCities()
    {
        // Replace this with your logic to retrieve cities
        return [
            ['name' => 'City1'],
            ['name' => 'City2'],
            // Add more cities as needed
        ];
    }
   
}
