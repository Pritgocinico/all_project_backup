<?php

namespace App\Http\Controllers\common;

use Nnjeim\World\WorldHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\Fitting;
use App\Models\TaskManagement;
use App\Models\Workshop;
use App\Models\WorkshopQuestion;
use App\Models\WorkshopDoneTask;
use App\Models\FittingQuestion;
use App\Models\FittingDoneTask;
use App\Models\Purchase;
use App\Models\Setting;
use App\Models\Log;
use Nnjeim\World\World;
use Carbon\Carbon;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;

class ReportsController extends Controller
{
    protected $world;
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }

    public function getReport(Request $request){
        $projects = Project::where('status', 2)->get();
        // dd($projects);

        if(Auth::user()->role == 1){
            return view('admin.reports.report', compact('projects'));
        }else{
            return view('quotation.reports.report', compact('projects'));
        }
    }
    public function pendingReport (Request $request){
        $projects = Project::whereIn('status', [0,1])->get();
        // dd($projects);

        if(Auth::user()->role == 1){
            return view('admin.reports.pending_report', compact('projects'));
        }else{
            return view('quotation.reports.pending_report', compact('projects'));
        }
    }

    public function exportFile(Request $request){
        $projects = Project::where('status', 2)->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=employee Export.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Project', 'Project Profit', 'Created At', 'Cutting Date', 'Glass Date', 'Glass Receive Date', 'Shutter Ready Date', 'Shutter Date', 'Deliver Date', 'Selection Date', 'Invoice Date');
        $callback = function () use ($projects, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $totalCost = 0;
            foreach ($projects as $project) {
                $totalCost = $totalCost + $project->margin_cost;
                $customer = Customer::where('id',$project->customer_id)->first();
                $name = $project->project_generated_id ." - " . $customer->name;
                $createdDate = "";
                if($project->created_at !== null){
                    $date = Carbon::parse($project->created_at);
                    $createdDate = $date->format('d/m/Y h:i:s A');
                }
                $glassDate = "";
                if($project->glass_date !== null){
                    $date = Carbon::parse($project->glass_date);
                    $glassDate = $date->format('d/m/Y h:i:s A');
                }
                $glassReceiveDate = "";
                if($project->glass_receive_date){
                    $date = Carbon::parse($project->glass_receive_date);
                    $glassReceiveDate = $date->format('d/m/Y h:i:s A');
                }
                $cuttingDate = "";
                if($project->cutting_date){
                    $date = Carbon::parse($project->cutting_date);
                    $cuttingDate = $date->format('d/m/Y h:i:s A');
                }
                $shutterReadyDate = "";
                if($project->shutter_ready_date){
                    $date = Carbon::parse($project->shutter_ready_date);
                    $shutterReadyDate = $date->format('d/m/Y h:i:s A');
                }
                $shutterDate = "";
                if($project->shutter_date){
                    $date = Carbon::parse($project->shutter_date);
                    $createdDate = $date->format('d/m/Y h:i:s A');
                }
                $deliverDate = "";
                if($project->deliver_date){
                    $date = Carbon::parse($project->deliver_date);
                    $shutterDate = $date->format('d/m/Y h:i:s A');
                }
                $selectionDate = "";
                if($project->selection_date){
                    $date = Carbon::parse($project->selection_date);
                    $selectionDate = $date->format('d/m/Y h:i:s A');
                }
                $invoiceDate = "";
                if($project->invoice_date){
                    $date = Carbon::parse($project->invoice_date);
                    $invoiceDate = $date->format('d/m/Y h:i:s A');
                }
                fputcsv($file, array($name, $project->margin_cost, $createdDate, $cuttingDate, $glassDate, $glassReceiveDate, $shutterReadyDate, $shutterDate, $deliverDate, $selectionDate, $invoiceDate));
            }
            fputcsv($file, array('', "", "", ""));
            fputcsv($file, array('Total Amount', $totalCost));
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function pendingReportExport(Request $request){
        $projects = Project::whereIn('status', ['0','1'])->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=employee Export.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Project', 'Phone Number', 'Estimate Measurement Date', 'Status',"Step");
        $callback = function () use ($projects, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($projects as $project) {
                $customer = Customer::where('id',$project->customer_id)->first();
                $name = $project->project_generated_id ." - " . $customer->name;
                $createdDate = "";
                if($project->measurement_date){
                    $date = Carbon::parse($project->measurement_date);
                    $createdDate = $date->format('d/m/Y');
                }
                $status = "Not Started";
                if($project->status){
                    $status = "In Progress";
                }
                $step = "Project Created";
                if ($project->step == 1){
                    $step = "Measurement";
                } else if ($project->step == 2) {
                    $step = "Quotation";
                } else if ($project->step == 3) {
                    $step = "Purchase";
                }  else if ($project->step == 4) {
                    $step = "Workshop";
                    if(!blank($project->sub_step)){
                        $step .= "-". $project->sub_step ;
                    }
                } else if ($project->step == 5) {
                    $step = "Site Installation";
                }
                fputcsv($file, array($name, $project->phone_number, $createdDate, $status, $step));
            }
        };
        return response()->stream($callback, 200, $headers);
    }
    
}
