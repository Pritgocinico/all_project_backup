<?php

namespace App\Http\Controllers\common;

use App\Models\AdditionalProjectDetail;
use App\Models\Project;
use App\Models\Workshop;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\MeasurementSitePhoto;
use App\Models\Fitting;
use App\Models\Setting;
use App\Models\Purchase;
use App\Models\WorkshopQuestion;
use App\Models\WorkshopDoneTask;
use App\Models\FittingQuestion;
use App\Models\FittingDoneTask;
use App\Models\PartialDeliverDetail;
use App\Models\Customer;
use App\Models\Log;
use App\Models\User;
use App\Models\QuotationUpload;
use App\Models\Sitephotos;
use App\Models\TaskManagement;
use App\Models\Invoicefile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;
use App\Http\Helpers\SmsHelper;

class WorkshopController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        // $user = User::first();
        view()->share('setting', $setting);
    }
    public function viewworkshop(Request $request, $id)
    {
        $page = 'View Workshop';
        $projects= Project::with('glassMeasurementFile')->findOrFail($id);
        $additionalProject = AdditionalProjectDetail::where('project_id', $id)->where('status', 'add')->orderBy('id', 'desc')->first();
        $workshops = Workshop::where('project_id', $id)->get();
        $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        $workshop_questions = WorkshopQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        // dd($workshop_doneTasks);
        $quotations = Quotation::where('project_id', $id)->orderBy('add_work', 'desc')->first();
        $quotationfiles = Quotationfile::where('project_id', $id)->get();
        $measurements = Measurement::where('project_id', $id)->orderBy('add_work', 'desc')->first();
        $measurementfiles = Measurementfile::where('project_id', $id)->get();
        $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        $fittings = Fitting::where('project_id', $id)->get();
        $purchases = Purchase::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $sitephotos = Sitephotos::where('project_id', $id)->get();
        $taskLists = TaskManagement::where('task_type', 'workshop')->where('project_id', $id)->get();
        $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();
        $issueProject = AdditionalProjectDetail::where('project_id', $id)->where('status', 'issue')->orderBy('id', 'desc')->get();

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
            return view('admin.projects.view_workshop', compact('subPurchases', 'subInvoiceFiles', 'subPartialDeliverDatas', 'subProjectData', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'issueProject', 'additionalProject', 'type', 'page', 'projects', 'purchases', 'workshops', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'fittings', 'workshop_questions', 'workshop_doneTasks', 'sitephotos', 'taskLists', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        } else {
            return view('quotation.projects.view_workshop', compact('subPurchases', 'subInvoiceFiles', 'subPartialDeliverDatas', 'subProjectData', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'issueProject', 'additionalProject', 'type', 'page', 'projects', 'purchases', 'workshops', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'fittings', 'workshop_questions', 'workshop_doneTasks', 'sitephotos', 'taskLists', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        }
    }
    public function view_store_workshop(Request $request)
    {
        $requestData = $request->all();
        $projectData = Project::where('id', $requestData['project_id'])->first();
        $issueData = AdditionalProjectDetail::where('project_id', $requestData['project_id'])->where('status', 'issue')->latest()->first();
        foreach ($requestData as $key => $value) {
            if (strpos($key, 'chk') === 0) {
                $id = substr($key, 3);
                $isChecked = ($value === 'on');
                if ($projectData->add_work == 2) {
                    $workshopTask = WorkshopDoneTask::updateOrCreate(
                        ['project_id' => $requestData['project_id'], 'question_id' => $id, 'add_type' => 2, 'issue_id' => $issueData->id],
                        ['chk' => $isChecked ? 'on' : 'off']
                    );
                } else {
                    $workshopTask = WorkshopDoneTask::updateOrCreate(
                        ['project_id' => $requestData['project_id'], 'question_id' => $id],
                        ['chk' => $isChecked ? 'on' : 'off']
                    );
                }
            }
        }
        $workshopvalues = Workshop::where('project_id', $request->project_id)->count();
        $workshopData = Workshop::where('project_id', $request->project_id)->first();
        // $workshopsdata = $request->input('workshop');
        // foreach ($workshopsdata as $workshop) {
        //     if (array_key_exists('id', $workshop)) {
        //         $existingWorkshop = Workshop::find($workshop['id']);
        //         $existingWorkshop->measurement = $workshop['measurement'];
        //         $existingWorkshop->description = $workshop['description'];
        //         $existingWorkshop->save();
        //     }
        //     else{
        //         $newWorkshop = new Workshop();
        //         $newWorkshop->project_id = $request->project_id;
        //         $newWorkshop->measurement = $workshop['measurement'];
        //         $newWorkshop->description = $workshop['description'];
        //         $newWorkshop->save();
        //     }
        // }
        $project_workshop = Project::where('id', $request->project_id)->first();
        if($project_workshop->cutting_selection == 1 && $project_workshop->shutter_selection == 1 && $project_workshop->glass_measurement == 1 && $project_workshop->glass_receive == 1 && $project_workshop->shutter_ready == 1 && $project_workshop->invoice_status == 1 && ($project_workshop->material_delivered == 1 || $project_workshop->material_delivered == 2)){
            $project_workshop->step = 5;
        }
        $project_workshop->save();
        $lastAdditionalProjectDetail = AdditionalProjectDetail::where('project_id', $request->project_id)->latest()->first();
        if (isset($lastAdditionalProjectDetail)) {
            $lastAdditionalProjectDetail->sub_step = 'Site Installation';
            $lastAdditionalProjectDetail->save();
        }
        if (Auth::user()->role == 1) {
            return redirect()->route('view.workshop', $request->project_id)->with('success', 'Workshop created successfully.');
        } else {
            return redirect()->route('quotation_view.workshop', $request->project_id)->with('success', 'Workshop created successfully.');
        }
    }

    public function store_workshop_question(Request $request)
    {
        $newQuestion = new WorkshopQuestion();
        $newQuestion->project_id = $request->project_id;
        $newQuestion->workshop_question = $request->workshop_question;
        $newQuestion->created_by = auth()->user()->id;
        $newQuestion->save();
        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Workshop';
        $log->log = 'Question Added';
        $log->save();

        $workshopDoneTasks = WorkshopDoneTask::where('project_id', $request->project_id)->get();
        if (!blank($workshopDoneTasks)) {
            $workshopQuestion = new WorkshopDoneTask();
            $workshopQuestion->project_id = $request->project_id;
            $workshopQuestion->question_id = $newQuestion->id;
            $workshopQuestion->save();
        }
        if (Auth::user()->role == 1) {
            return redirect()->route('view.workshop', $request->project_id)->with('success', 'Workshop question submitted successfully.');
        } else {
            return redirect()->route('quotation_view.workshop', $request->project_id)->with('success', 'Workshop question submitted successfully.');
        }
    }

    public function deleteworkshop($id)
    {
        $workshops = Workshop::find($id);
        $workshops->delete();
        return response()->json("success", 200);
    }

    public function storeMaterialStatus(Request $request)
    {
        $project = Project::where('id', $request->project_id)->first();
        if ($request->material_delivered == 2) {
            $project->material_delivered = $request->material_delivered;
            $project->sub_step = "Material (5.7)";
            $project->save();
            $partialDetails = new PartialDeliverDetail();
            $partialDetails->project_id = $request->project_id;
            $partialDetails->partial_deliver_by = $request->partial_deliver_by;
            $partialDetails->driver_number = $request->driver_number;
            $partialDetails->partial_deliver_date = $request->partial_deliver_date;
            $partialDetails->save();
            try {
                $userDetail = Customer::where('id', $project->customer_id)->first();
                $mobileNumber = $userDetail->phone;
                $message = "Dear " . $userDetail->name . ", your material has been dispatched from the workshop and will be delivered by " . $partialDetails->partial_deliver_by . " - " . $partialDetails->driver_number . ". You can call him for the status of the delivery on " . $project->deliver_date . ". Shree Ganesh Aluminum";
                $templateid = '1407171594318987872';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                if ($partialDetails->driver_number) {
                    $mobileNumber = $partialDetails->driver_number;
                    $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, false);
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        } elseif ($request->material_delivered == 1) {
            $project->material_delivered = $request->material_delivered;
            $project->delivered_by = $request->delivered_by;
            $project->driver_number = $request->driver_numbers;
            $project->deliver_date = $request->deliver_date;
            $project->sub_step = "Material (5.7)";
            $project->save();
            $addProject = AdditionalProjectDetail::where('id', $request->project_id)->where('status', 'issue')->first();
            if (isset($addProject) && $project->add_work == 2) {
                $addProject->material_delivered = $request->material_delivered;
                $addProject->delivered_by = $request->delivered_by;
                $addProject->driver_number = $request->driver_number;
                $addProject->deliver_date = $request->deliver_date;
                $addProject->sub_step = "Material (5.7)";
                $addProject->save();
            }
            try {
                $userDetail = Customer::where('id', $project->customer_id)->first();
                $mobileNumber = $userDetail->phone;
                $message = "Dear " . $userDetail->name . ", your material has been dispatched from the workshop and will be delivered by " . $project->delivered_by . " - " . $project->driver_number . ". You can call him for the status of the delivery on " . $project->deliver_date . ". Shree Ganesh Aluminum";
                $templateid = '1407171594318987872';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                if ($project->driver_number) {
                    $mobileNumber = $project->driver_number;
                    $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, false);
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Project';
        $log->log = 'Workshop - Material Delivery Status Updated';
        $log->save();
        return redirect()->back();
    }

    public function deletePartial($id)
    {
        $invoiceFile = PartialDeliverDetail::where('id', $id)->first();
        $invoiceFile->delete();

        $project = Project::where('id', $invoiceFile->project_id)->first();
        $customer = Customer::where('id', $project->customer_id)->first();
        $customer_name = $customer->name;
        if (Auth::user()->role == 1) {
            $user = User::where('id', Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Partial Delivery detail Deleted - ',
                'text' => 'Project: ' . $project->project_generated_id . '-' . $customer_name,
                'url' => route('view.workshop', $project->id),
            ];

            Notification::send($user, new OffersNotification($notificationData));
        } else {
            $user = User::where('id', Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Partial Delivery detail Deleted - ',
                'text' => 'Project: ' . $project->project_generated_id . '-' . $customer_name,
                'url' => route('quotation_view.workshop', $project->id),
            ];

            Notification::send($user, new OffersNotification($notificationData));
        }


        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Workshop';
        $log->log = 'Partial Delivery detail Deleted';
        $log->save();
        return response()->json("success", 200);
    }

    public function storeInvoiceStatus(Request $request)
    {
        $project = Project::where('id', $request->project_id)->first();
        if ($request->invoice_status == 0) {
            $project->invoice_status = $request->invoice_status;
            $project->invoice_date = null;
            $project->save();

            $addProject = AdditionalProjectDetail::where('id', $request->project_id)->where('status', 'issue')->first();
            if (isset($addProject) && $project->add_work == 2) {
                $addProject->invoice_status = $request->invoice_status;
                $addProject->invoice_date = null;
                $addProject->save();
            }
        } else {
            $project->invoice_status = $request->invoice_status;
            $project->invoice_date = $request->invoice_date;
            $project->save();
            $addProject = AdditionalProjectDetail::where('id', $request->project_id)->where('status', 'issue')->first();
            if (isset($addProject) && $project->add_work == 2) {
                $addProject->invoice_status = $request->invoice_status;
                $addProject->invoice_date = $request->invoice_date;
                $addProject->save();
            }
        }

        if ($request->hasFile('invoicefiles')) {
            $allowedfileExtension = ['jpg', 'png', 'jpeg', 'pdf'];
            $files = $request->file('invoicefiles');

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $destinationPath = 'public/invoicefiles/';
                $extension = $file->getClientOriginalExtension();
                $file_name = $filename;
                $file->move($destinationPath, $file_name);
                $invoiceFile = new Invoicefile();
                if ($project->add_work == 1) {
                    $invoiceFile->add_work = 1;
                }
                $invoiceFile->project_id = $request->project_id;
                $invoiceFile->invoice = $file_name;
                // $invoiceFile->created_by = $user->id;
                $invoiceFile->save();
            }
            $route = route('projectView', $project->id);
            $userDetail = Customer::where('id', $project->customer_id)->first();
            $mobileNumber = $userDetail->phone;
            $message = "Dear " . $userDetail->name . ", your invoice has been generated for your " . $project->project_generated_id . ", kindly check your project details to this link " . $route . ". Shree Ganesh Aluminum";
            $templateid = '1407171594311415919';
            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
        }

        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Project';
        $log->log = 'Workshop - Invoice Status Updated';
        $log->save();
        return redirect()->back();
    }

    public function deleteInvoice($id)
    {
        $invoiceFile = Invoicefile::where('id', $id)->first();
        $invoiceFile->delete();

        $project = Project::where('id', $invoiceFile->project_id)->first();
        $customer = Customer::where('id', $project->customer_id)->first();
        $customer_name = $customer->name;
        if (Auth::user()->role == 1) {
            $user = User::where('id', Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Invoice file Deleted - ',
                'text' => 'Project: ' . $project->project_generated_id . '-' . $customer_name,
                'url' => route('view.workshop', $project->id),
            ];

            Notification::send($user, new OffersNotification($notificationData));
        } else {
            $user = User::where('id', Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Invoice file Deleted - ',
                'text' => 'Project: ' . $project->project_generated_id . '-' . $customer_name,
                'url' => route('quotation_view.workshop', $project->id),
            ];

            Notification::send($user, new OffersNotification($notificationData));
        }


        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Workshop';
        $log->log = 'Workshop File Deleted';
        $log->save();
        return response()->json("success", 200);
    }
}
