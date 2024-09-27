<?php

namespace App\Http\Controllers\common;

use App\Models\AdditionalProjectDetail;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\QuotationUpload;
use App\Models\Project;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\MeasurementSitePhoto;
use App\Models\Fitting;
use App\Models\Workshop;
use App\Models\Purchase;
use App\Models\Setting;
use App\Models\Log;
use App\Models\User;
use App\Models\Sitephotos;
use App\Models\WorkshopQuestion;
use App\Models\WorkshopDoneTask;
use App\Models\FittingQuestion;
use App\Models\FittingDoneTask;
use App\Models\PartialDeliverDetail;
use App\Models\Invoicefile;
use App\Models\Customer;
use Notification;
use App\Notifications\OffersNotification;
use App\Models\MeasurementTask;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Auth;
use App\Http\Helpers\SmsHelper;

class QuotationController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        // $user = User::first();
        view()->share('setting', $setting);
    }
    public function viewquotation(Request $request, $id)
    {
        $page = 'View Quotation';
        $projects = Project::findOrFail($id);
        $quotations = Quotation::where('project_id', $id)->orderBy('id','desc')->first();
        $addProject = AdditionalProjectDetail::where('project_id', $id)->where('status','add')->first();
        $quotationfiles = Quotationfile::where('project_id', $id)->get();
        $quotation_uploads = QuotationUpload::where('project_id', $id)->get();
        $measurements = Measurement::where('project_id', $id)->first();
        $measurementfiles = Measurementfile::where('project_id', $id)->orderBy('add_work','desc')->get();
        $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        $workshops = Workshop::where('project_id', $id)->get();
        $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        $fittings = Fitting::where('project_id', $id)->get();
        $purchases = Purchase::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $sitephotos = Sitephotos::where('project_id', $id)->get();
        $workshop_questions = WorkshopQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();
        $issueProject = AdditionalProjectDetail::where('project_id', $id)->where('status','issue')->first();

        $subProjectIDArray = Project::where('sub_project_id', $projects->id)->pluck('id')->toArray();
        $subquotations = Quotation::whereIn('project_id', $subProjectIDArray)->orderBy('add_work','desc')->first();
        $subquotationfiles = Quotationfile::whereIn('project_id', $subProjectIDArray)->get();
        $sub_quotation_uploads = QuotationUpload::whereIn('project_id', $subProjectIDArray)->get();

        $submeasurements = Measurement::whereIn('project_id', $subProjectIDArray)->orderBy('id', 'desc')->get();
        $sumeasurementfiles = Measurementfile::whereIn('project_id', $subProjectIDArray)->orderBy('add_work','desc')->get();
        $sumeasurementphotos = MeasurementSitePhoto::whereIn('project_id', $subProjectIDArray)->get();

        $subquotations = Quotation::whereIn('project_id', $subProjectIDArray)->orderBy('add_work','desc')->first();
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
            return view('admin.projects.view_quotation', compact('subPurchases','subInvoiceFiles','subPartialDeliverDatas','subProjectData','subquotations','subquotationfiles','sub_quotation_uploads','submeasurements','sumeasurementfiles','sumeasurementphotos','sub_quotation_uploads','subquotations','subquotationfiles','issueProject','addProject','type', 'page', 'projects', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'workshops', 'fittings', 'quotation_uploads', 'purchases', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        } else {
            return view('quotation.projects.view_quotation', compact('type', 'page', 'projects', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'workshops', 'fittings', 'quotation_uploads', 'purchases', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        }
    }
    public function viewSelection(Request $request, $id)
    {
        $page = 'View Quotation';
        $projects = Project::findOrFail($id);
        $quotations = Quotation::where('project_id', $id)->first();
        $quotationfiles = Quotationfile::where('project_id', $id)->orderBy('id', 'desc')->get();
        $quotation_uploads = QuotationUpload::where('project_id', $id)->get();
        $measurements = Measurement::where('project_id', $id)->first();
        $measurementfiles = Measurementfile::where('project_id', $id)->get();
        $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        $workshops = Workshop::where('project_id', $id)->get();
        $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        $fittings = Fitting::where('project_id', $id)->get();
        $purchases = Purchase::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $sitephotos = Sitephotos::where('project_id', $id)->get();
        $workshop_questions = WorkshopQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();

        $subProjectIDArray = Project::where('sub_project_id', $projects->id)->pluck('id')->toArray();
        $submeasurements = Measurement::whereIn('project_id', $subProjectIDArray)->orderBy('id', 'desc')->get();
        $sumeasurementfiles = Measurementfile::whereIn('project_id', $subProjectIDArray)->orderBy('add_work','desc')->get();
        $sumeasurementphotos = MeasurementSitePhoto::whereIn('project_id', $subProjectIDArray)->get();

        $subquotations = Quotation::whereIn('project_id', $subProjectIDArray)->orderBy('add_work','desc')->first();
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
            return view('admin.projects.view_selection', compact('subPurchases','subProjectData','subPartialDeliverDatas','subInvoiceFiles','subquotations','subquotationfiles','sub_quotation_uploads','submeasurements','sumeasurementfiles','sumeasurementphotos','type', 'page', 'projects', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'workshops', 'fittings', 'quotation_uploads', 'purchases', 'measurementphotos', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        } else {
            return view('quotation.projects.view_selection', compact('type', 'page', 'projects', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'workshops', 'fittings', 'quotation_uploads', 'purchases', 'measurementphotos', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        }
    }
    public function view_store_quotation(Request $request)
    {
        $quo_data = $request->quotation;
        $quotationvalues = Quotation::where('project_id', $request->project_id)->count();
        $quotationData = Quotation::where('project_id', $request->project_id)->first();
        $quotationfiles = Quotationfile::where('project_id', $request->project_id)->get();
        $project = Project::find($request->project_id);
        $revertFinalquotation = Quotationfile::where('project_id', $request->project_id)->where('final', 1)->first();
        if ($quotationvalues > 0) {
            $quotations = Quotation::find($quotationData->id);
            $quotations->quotation_date = Carbon::now()->format('Y-m-d');
            $quotations->add_work = $project->add_work;
            $quotations->save();
        } else {
            $project = Project::where('id', $request->project_id)->first();
            $project->step = 2;
            $project->save();
            $quotations = new Quotation();
            $quotations->project_id = $request->project_id;
            $quotations->add_work = $project->add_work;
            $quotations->quotation_date = Carbon::now()->format('Y-m-d');
            $quotations->created_by = Auth::user()->id;
            $quotations->save();
        }
        if (!blank($quo_data)) {
            foreach ($quo_data as $quotation_data) {
                // echo '<pre>';
                // print_r($quo_data);
                // exit;
                if (array_key_exists('id', $quotation_data)) {
                    $final = 0;
                    if (array_key_exists('final', $quotation_data)) {
                        if ($quotation_data['final'] == 'on') {
                            $final = 1;
                        } else {
                            $final = 0;
                        }
                    }
                    if (!array_key_exists('hidden_div', $quotation_data)) {
                        $existingQuotation = Quotationfile::find($quotation_data['id']);
                        // if ($request->hasFile($fileInputName)) {
                        //     $image = $request->file($fileInputName);
                        //     $destinationPath = 'public/quotationfile/';
                        //     $quotationimg = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        //     $image->move($destinationPath, $quotationimg);
                        // }
                        // else{
                        //     $quotationimg = $oldImageName;
                        // }
                        // $existingQuotation->type = $quotation_data['type_quotation'];
                        // $existingQuotation->cost        = $quotation_data['quotation_cost'];
                        // $existingQuotation->project_cost        = $quotation_data['project_cost'];
                        $existingQuotation->description = $quotation_data['description'];
                        $existingQuotation->add_work = $project->add_work;
                        $existingQuotation->final = $final;
                        $existingQuotation->save();
                        if (array_key_exists('quotations_file', $quotation_data)) {
                            foreach ($quotation_data['quotations_file'] as $files) {
                                if ($files) {
                                    $image = $files;
                                    $destinationPath = 'public/quotationfile/';
                                    $quotationimg = date('YmdHis') . "." . $image->getClientOriginalExtension();
                                    $image->move($destinationPath, $quotationimg);
                                    $img_name = $image->getClientOriginalName();
                                } else {
                                    $quotationimg = '';
                                    $img_name = '';
                                }
                                $quotation_upload = new QuotationUpload();
                                $quotation_upload->quotation_id = $quotations->id;
                                $quotation_upload->project_id = $request->project_id;
                                $quotation_upload->quotation_file_id = $existingQuotation->id;
                                $quotation_upload->file = $quotationimg;
                                $quotation_upload->add_work = $project->add_work;
                                $quotation_upload->file_name = $img_name;
                                $quotation_upload->save();
                            }
                        }
                    }
                } else {
                    $final = 0;
                    if (array_key_exists('final', $quotation_data)) {
                        if ($quotation_data['final'] == 'on') {
                            $final = 1;
                        } else {
                            $final = 0;
                        }
                    }
                    $quotationFile = new Quotationfile();
                    $quotationFile->project_id = $request->project_id;
                    $quotationFile->quotation_id = $quotations->id;
                    // $quotationFile->cost            = $quotation_data['quotation_cost'];
                    // $quotationFile->project_cost    = $quotation_data['project_cost'];
                    $quotationFile->description = $quotation_data['description'];
                    $quotationFile->final = $final;
                    $quotations->add_work = $project->add_work;
                    $quotationFile->save();
                    // print_r($quotation_data);
                    // exit;
                    if ($request->hasFile('quotations_file')) {
                        $files = $request->file('quotations_file');
                        foreach ($files as $file) {
                            $image = $file;
                            $img_name = $image->getClientOriginalName();
                            $destinationPath = 'public/quotationfile/';
                            $quotationimg = date('YmdHis') . "." . $image->getClientOriginalExtension();
                            $image->move($destinationPath, $quotationimg);
                            $quotation_upload = new QuotationUpload();
                            $quotation_upload->quotation_id = $quotations->id;
                            $type = 0;
                            if ($request->type == "additional") {
                                $type = 1;
                            }
                            $quotation_upload->add_work = $type;
                            $quotation_upload->quotation_id = $quotations->id;
                            $quotation_upload->project_id = $request->project_id;
                            $quotation_upload->quotation_file_id = $quotationFile->id;
                            $quotation_upload->file = $quotationimg;
                            $quotation_upload->add_work = $project->add_work;
                            $quotation_upload->file_name = $img_name;
                            $quotation_upload->save();
                        }

                        $user = User::where('id', Auth::user()->id)->first();
                        if (Auth::user()->role == 1) {
                            $notificationData = [
                                'type' => 'message',
                                'title' => 'Quotation - ',
                                'text' => 'Quotation has been added.',
                                'url' => route('view.quotation', $project->id),
                            ];
                            Notification::send($user, new OffersNotification($notificationData));
                        } else {
                            $notificationData = [
                                'type' => 'message',
                                'title' => 'Quotation - ',
                                'text' => 'Quotation has been added.',
                                'url' => route('quotation_view.quotation', $project->id),
                            ];
                            Notification::send($user, new OffersNotification($notificationData));
                        }


                        $log = new Log();
                        $log->user_id = Auth::user()->name;
                        $log->module = 'Quotation';
                        $log->log = 'Quotation Added';
                        $log->save();

                        try {
                            //http Url to send sms.
                            $userDetail = Customer::where('id', $project->customer_id)->first();
                            $url = route('projectView', $request->project_id);
                            $mobileNumber = $userDetail->phone;
                            $message = "Dear " . $userDetail->name . ", your quotation has been uploaded in our system for the " . $project->lead_no . " To check, use the following " . $url . ". Shree Ganesh Aluminum";
                            $templateid = '1407171593880088737';
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);

                        } catch (Exception $e) {
                            echo 'Message:' . $e->getMessage();
                        }
                    }
                }
            }
        }

        if ($request->hasFile('quotations_file')) {
            if ($quotationFile->final == 1) {
                if (!blank($revertFinalquotation)) {
                    $revertFinalquotation->final = 0;
                    $revertFinalquotation->save();
                }
            }
        }
        if (Auth::user()->role == 1) {
            return redirect()->route('view.quotation', $request->project_id)->with('created', 'Quotation created successfully.');
        } else {
            return redirect()->route('quotation_view.quotation', $request->project_id)->with('created', 'Quotation created successfully.');
        }
    }
    public function storeSelection(Request $request)
    {
        $selection = $request->project_final;
        $project = Project::where('id', $request->project_id)->first();
        if ($selection == 1) {
            // $project->project_generated_id = $project->lead_no;
            $project->project_generated_id = str_replace('SGA_LD_', 'SGA_PR_', $project->lead_no);
            $project->status = 1;
            $project->type = 1;
            $msg = "Lead has been converted to project.";
            $project->save();

            $customer = Customer::where('id', $project->customer_id)->first();
            $customer_name = $customer->name;
            $user = User::where('id', Auth::user()->id)->first();
            if (Auth::user()->role == 1) {
                $notificationData = [
                    'type' => 'message',
                    'title' => 'Lead converted to Project - ',
                    'text' => $project->project_generated_id . '-' . $customer_name,
                    'url' => route('view.project', $project->id),
                ];
                Notification::send($user, new OffersNotification($notificationData));
            } else {
                $notificationData = [
                    'type' => 'message',
                    'title' => 'Lead converted to Project - ',
                    'text' => $project->project_generated_id . '-' . $customer_name,
                    'url' => route('quotation_view.project', $project->id),
                ];
                Notification::send($user, new OffersNotification($notificationData));
            }


            $log = new Log();
            $log->user_id = Auth::user()->name;
            $log->module = 'Lead';
            $log->log = 'Lead Converted to Project';
            $log->save();
        } else {
            $project->status = 0;
            $project->type = 0;
            $msg = '';
            $project->save();
        }

        return redirect()->back()->with('message', $msg);
    }
    public function finalizeQuotation(Request $request, $id = NULL)
    {
        $quotation = Quotationfile::where('id', $id)->first();
        $quotation->status = 1;
        $quotation->save();
        return 1;
    }
    public function rejectQuotation(Request $request)
    {
        $quotation_file = Quotationfile::where('id', $request->quotation_file_id)->first();
        $quotation_file->status = 2;
        $quotation_file->final = 0;
        $quotation_file->reject_reason = $request->reject_reason;
        $quotation_file->reject_note = $request->reason;
        $quotation_file->save();

        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Quotation';
        $log->log = 'Quotation Rejected';
        $log->save();

        Session::flash('success', 'Rejected Successfully!');
        return redirect()->back();
    }
    public function deletequotation($id)
    {
        $quotationupload = QuotationUpload::find($id);
        $quotationupload->delete();

        $quotation_fileid = $quotationupload->quotation_file_id;
        $quotation_id = $quotationupload->quotation_id;

        $quotationuploads = QuotationUpload::where('quotation_file_id', $quotation_fileid)->get();

        if (count($quotationuploads) === 0) {
            // $quotation = Quotation::where('id', $quotation_id)->delete();
            $quotationfile = Quotationfile::where('id', $quotation_fileid)->delete();
        }

        $user = User::where('id', Auth::user()->id)->first();
        if (Auth::user()->role == 1) {
            $notificationData = [
                'type' => 'message',
                'title' => 'Quotation - ',
                'text' => 'Quotation Deleted',
                'url' => route('view.quotation', $quotationupload->project_id),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        } else {
            $notificationData = [
                'type' => 'message',
                'title' => 'Quotation - ',
                'text' => 'Quotation Deleted',
                'url' => route('quotation_view.quotation', $quotationupload->project_id),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        }


        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Quotation';
        $log->log = 'Quotation Deleted';
        $log->save();

        return response()->json("success", 200);
    }
}
