<?php

namespace App\Http\Controllers\common;

use App\Models\AdditionalProjectDetail;
use App\Models\Project;
use App\Models\Fitting;
use App\Models\Workshop;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\Measurement;
use App\Models\Purchase;
use App\Models\Measurementfile;
use App\Models\MeasurementSitePhoto;
use App\Models\FittingQuestion;
use App\Models\FittingDoneTask;
use App\Models\Invoicefile;
use App\Models\Log;
use App\Models\User;
use App\Models\Sitephotos;
use App\Models\WorkshopQuestion;
use App\Models\QuotationUpload;
use App\Models\WorkshopDoneTask;
use App\Models\PartialDeliverDetail;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;

class FittingController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        // $user = User::first();
        view()->share('setting', $setting);
    }
    public function viewfitting(Request $request, $id)
    {
        $page = 'View Fittings';
        $projects = Project::findOrFail($id);
        $quotations = Quotation::where('project_id', $id)->orderBy('add_work', 'desc')->first();
        $additionalProject = AdditionalProjectDetail::where('project_id', $id)->where('status', 'add')->first();
        $quotationfiles = Quotationfile::where('project_id', $id)->get();
        $measurements = Measurement::where('project_id', $id)->first();
        $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();
        // dd($fitting_doneTasks);
        $measurementfiles = Measurementfile::where('project_id', $id)->orderBy('add_work', 'desc')->get();
        $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        $workshops = Workshop::where('project_id', $id)->get();
        $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        $fittings = Fitting::where('project_id', $id)->get();
        $purchases = Purchase::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $sitephotos = Sitephotos::where('project_id', $id)->get();
        $workshop_questions = WorkshopQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $issueProject = AdditionalProjectDetail::with('workshopIssueTask','fittingIssueTask')->where('project_id', $id)->where('status', 'issue')->latest()->get();

        $subProjectIDArray = Project::where('sub_project_id', $projects->id)->pluck('id')->toArray();
        $submeasurements = Measurement::whereIn('project_id', $subProjectIDArray)->orderBy('id', 'desc')->get();
        $sumeasurementfiles = Measurementfile::whereIn('project_id', $subProjectIDArray)->orderBy('add_work', 'desc')->get();
        $sumeasurementphotos = MeasurementSitePhoto::whereIn('project_id', $subProjectIDArray)->get();

        $subquotations = Quotation::whereIn('project_id', $subProjectIDArray)->orderBy('add_work', 'desc')->first();
        $subquotationfiles = Quotationfile::whereIn('project_id', $subProjectIDArray)->get();
        $sub_quotation_uploads = QuotationUpload::whereIn('project_id', $subProjectIDArray)->get();

        $subPurchases = Purchase::whereIn('project_id', $subProjectIDArray)->orderBy('id', 'DESC')->get();
        $subProjectData = Project::whereIn('id', $subProjectIDArray)->orderBy('id', 'DESC')->get();
        $subPartialDeliverDatas = PartialDeliverDetail::whereIn('project_id', $subProjectIDArray)->get();
        $subInvoiceFiles = Invoicefile::whereIn('project_id', $subProjectIDArray)->get();
        if ($projects->type == 1) {
            $type = 'Project';
        } else {
            $type = 'Lead';
        }
        if (Auth::user()->role == 1) {
            return view('admin.projects.view_fitting', compact('subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'issueProject', 'additionalProject', 'type', 'page', 'projects', 'fittings', 'purchases', 'workshops', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'fitting_questions', 'fitting_doneTasks', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'partialDeliverDatas'));
            } else {
            return view('quotation.projects.view_fitting', compact('subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'issueProject', 'additionalProject', 'type', 'page', 'projects', 'fittings', 'purchases', 'workshops', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'fitting_questions', 'fitting_doneTasks', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'partialDeliverDatas'));
        }
    }
    public function view_store_fitting(Request $request)
    {
        // dd($request->all());
        $requestData = $request->all();
        $projectData = Project::where('id', $requestData['project_id'])->first();
        $issueData = AdditionalProjectDetail::where('project_id', $requestData['project_id'])->where('status', 'issue')->latest()->first();
        foreach ($requestData as $key => $value) {
            if (strpos($key, 'chk') === 0) {
                $id = substr($key, 3);
                $isChecked = ($value === 'on');
                if ($projectData->add_work == 2) {
                    $workshopTask = FittingDoneTask::updateOrCreate(
                        ['project_id' => $requestData['project_id'], 'question_id' => $id, 'add_type' => 2,'issue_id' => $issueData->id],
                        ['chk' => $isChecked ? 'on' : 'off']
                    );
                } else {
                    $fittingTask = FittingDoneTask::updateOrCreate(
                        ['project_id' => $requestData['project_id'], 'question_id' => $id],
                        ['chk' => $isChecked ? 'on' : 'off', 'add_type' => 0]
                    );
                }
            }
        }

        $project_fitting = Project::where('id', $request->project_id)->first();
        $project_fitting->step = 5;
        $project_fitting->save();

        $projectdata = Project::where('id', $request->project_id)->count();

        if ($request->status == 2) {
            if ($projectdata > 0) {
                $projects = Project::find($request->project_id);
                $projects->transit_date = date("Y-m-d", strtotime($request->deliverydate));
                $projects->transit_desc = $request->deliverydescription;
                // $project->status= 2;
                $projects->save();
            }
        }
        if ($request->status == 3) {
            if ($projectdata > 0) {
                $projects = Project::find($request->project_id);
                $projects->fitting_date = date("Y-m-d", strtotime($request->fittingdate));
                $projects->fitting_desc = $request->fittingdescription;
                // $project->status= 3;
                $projects->save();
            }
        }
        if (Auth::user()->role == 1) {
            return redirect()->route('view.fitting', $request->project_id)->with('success', 'Measurement created successfully.');
        } else {
            return redirect()->route('quotation_view.fitting', $request->project_id)->with('success', 'Measurement created successfully.');
        }
    }

    public function store_fitting_question(Request $request)
    {
        $newQuestion = new FittingQuestion();
        $newQuestion->project_id = $request->project_id;
        $newQuestion->fitting_question = $request->fitting_question;
        $newQuestion->created_by = auth()->user()->id;
        $newQuestion->save();

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Fitting';
        $log->log       = 'Fitting Added';
        $log->save();

        $fittingDoneTasks = FittingDoneTask::where('project_id', $request->project_id)->get();

        if (!blank($fittingDoneTasks)) {
            $fittingQuestion = new FittingDoneTask();
            $fittingQuestion->project_id = $request->project_id;
            $fittingQuestion->question_id = $newQuestion->id;
            $fittingQuestion->save();
        }
        if (Auth::user()->role == 1) {
            return redirect()->route('view.fitting', $request->project_id)->with('success', 'Fitting question submitted successfully.');
        } else {
            return redirect()->route('quotation_view.fitting', $request->project_id)->with('success', 'Fitting question submitted successfully.');
        }
    }

    public function model_store_fitting(Request $request)
    {
        $projectdata = Project::where('id', $request->project_id)->count();
        if ($projectdata > 0) {
            $projects = Project::find($request->project_id);
            $projects->fitting_complete_date = date("Y-m-d", strtotime($request->completedate));
            $projects->fitting_complete_desc = $request->completedescription;
            $projects->save();
            if ($request->hasFile('fittingfile')) {
                $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx'];
                $files = $request->file('fittingfile');
                foreach ($files as $file) {
                    $filename = $file->getClientOriginalName();
                    $destinationPath = 'public/fittingfile/';
                    $extension = $file->getClientOriginalExtension();
                    $file_name = $filename;
                    $file->move($destinationPath, $file_name);
                    $m_file = new Fitting();
                    $m_file->project_id = $request->project_id;
                    $m_file->Fitting_image = $file_name;
                    $m_file->save();
                }
            }
        }
        if (Auth::user()->role == 1) {
            return redirect()->route('view.fitting', $request->project_id)->with('success', 'Measurement created successfully.');
        } else {
            return redirect()->route('quotation_view.fitting', $request->project_id)->with('success', 'Measurement created successfully.');
        }
    }
    public function deletefitting_image($id)
    {
        $fittings = Fitting::find($id);
        $fittings->delete();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Fitting';
        $log->log       = 'Fitting Deleted';
        $log->save();
        return response()->json("success", 200);
    }
}
