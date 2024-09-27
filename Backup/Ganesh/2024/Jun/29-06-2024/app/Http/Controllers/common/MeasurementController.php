<?php

namespace App\Http\Controllers\common;

use App\Models\AdditionalProjectDetail;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\Fitting;
use App\Models\Workshop;
use App\Models\Setting;
use App\Models\Material;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\User;
use App\Models\Log;
use App\Models\Sitephotos;
use App\Models\WorkshopQuestion;
use App\Models\WorkshopDoneTask;
use App\Models\FittingQuestion;
use App\Models\QuotationUpload;
use App\Models\MeasurementTask;
use App\Models\FittingDoneTask;
use App\Models\PartialDeliverDetail;
use App\Models\Invoicefile;
use App\Models\MeasurementSitePhoto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;
use App\Http\Helpers\SmsHelper;

class MeasurementController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        // $user = User::first();
        view()->share('setting', $setting);
    }
    public function viewmeasurement(Request $request, $id)
    {
        $page = 'View Measurements';
        $projects = Project::findOrFail($id);
        $measurements = Measurement::where('project_id', $id)->orderBy('id', 'desc')->get();
        $addProject = AdditionalProjectDetail::where('project_id', $id)->where('status','add')->first();
        $measurementfiles = Measurementfile::where('project_id', $id)->orderBy('add_work','desc')->get();
        $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        $quotations = Quotation::where('project_id', $id)->orderBy('add_work','desc')->first();
        $quotationfiles = Quotationfile::where('project_id', $id)->get();
        $workshops = Workshop::where('project_id', $id)->get();
        $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        $fittings = Fitting::where('project_id', $id)->get();
        $materials = Material::where('project_id', $id)->get();
        $purchases = Purchase::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $sitephotos = Sitephotos::where('project_id', $id)->get();
        $workshop_questions = WorkshopQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();
        $issueProject = AdditionalProjectDetail::where('project_id', $id)->where('status','issue')->latest()->get();

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
            return view('admin.projects.view_measurement', compact('subPurchases','subProjectData','subPartialDeliverDatas','subInvoiceFiles','subquotations','subquotationfiles','sub_quotation_uploads','submeasurements','sumeasurementfiles','sumeasurementphotos','issueProject','addProject','type', 'page', 'projects', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'materials', 'purchases', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        } else {
            return view('quotation.projects.view_measurement', compact('subPurchases','subProjectData','subPartialDeliverDatas','subInvoiceFiles','subquotations','subquotationfiles','sub_quotation_uploads','submeasurements','sumeasurementfiles','sumeasurementphotos','issueProject','addProject','type', 'page', 'projects', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'materials', 'purchases', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        }
    }

    public function view_store_measurement(Request $request)
    {
        $request->validate([
            'measurementfile' => 'required',
        ]);
        $project = Project::find($request->project_id);
        $materialdata = Material::where('project_id', $request->project_id)->get();
        $materialinput = $request->input('measurement');

        $measData = Measurement::where('project_id', $request->project_id)->count();
        if ($measData == 0) {
            $project = Project::where('id', $request->project_id)->first();
            $project->step = 1;
            $project->save();
        }
        $measurements = new Measurement();
        $measurements->project_id = $request->project_id;
        $measurements->description = $request->description;
        $measurements->measurement_date = Carbon::today()->format('Y-m-d');
        $measurements->material_option = $request->material_option;
        $measurements->add_work = $project->add_work;
        $measurements->save();


        if ($request->hasFile('measurementfile')) {
            $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx'];
            $files = $request->file('measurementfile');

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $destinationPath = 'public/measurementfile/';
                $extension = $file->getClientOriginalExtension();
                $file_name = $filename;
                $file->move($destinationPath, $file_name);
                $m_file = new Measurementfile();
                if ($measurements->id) {
                    $m_file->measurement_id = $measurements->id;
                } else {
                    $newMeasurement = new Measurement();
                    $newMeasurement->project_id = $request->project_id;
                    $newMeasurement->measurement_date = Carbon::today()->format('Y-m-d');
                    $newMeasurement->save();
                    $m_file->measurement_id = $newMeasurement->id;
                }
                $m_file->project_id = $request->project_id;
                $m_file->add_work = $project->add_work;
                $m_file->measurement = $file_name;
                $m_file->save();
            }
        }

        if ($request->hasFile('sitephotos')) {
            $allowedfileExtension = ['jpg', 'png', 'jpeg'];
            $files = $request->file('sitephotos');

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $destinationPath = 'public/sitephoto/';
                $extension = $file->getClientOriginalExtension();
                $file_name = $filename;
                $file->move($destinationPath, $file_name);
                $m_sitephoto = new MeasurementSitePhoto();
                if ($measurements->id) {
                    $m_sitephoto->measurement_id = $measurements->id;
                } else {
                    $siteMeasurement = new Measurement();
                    $siteMeasurement->project_id = $request->project_id;
                    $siteMeasurement->measurement_date = Carbon::today()->format('Y-m-d');
                    $siteMeasurement->save();
                    $m_sitephoto->measurement_id = $siteMeasurement->id;
                }
                $m_sitephoto->project_id = $request->project_id;
                $m_sitephoto->site_photo = $file_name;
                $m_sitephoto->add_work = $project->add_work;
                $m_sitephoto->save();
            }
        }

        if ($measurements->material_option == 1) {
            if (!blank($request->input('measurement'))) {
                foreach ($request->input('measurement') as $key => $measurement) {
                    if (array_key_exists('id', $measurement)) {
                        $existingmeasurement = Material::find($measurement['id']);
                        $existingmeasurement->material_selection = $measurement['material_selection'];
                        $existingmeasurement->material_description = $measurement['material_description'];
                        $existingmeasurement->save();
                    } else {
                        $materialFile = new Material();
                        $materialFile->project_id = $request->project_id;
                        $materialFile->measurement_id = $measurements->id;
                        $materialFile->material_selection = $measurement['material_selection'];
                        $materialFile->material_description = $measurement['material_description'];
                        $materialFile->save();
                    }
                }
            }
        }

        $customer = Customer::with('cityDetail','stateDetail')->where('id', $project->customer_id)->first();
        
        $user = User::where('id', Auth::user()->id)->first();
        if (Auth::user()->role == 1) {
            $notificationData = [
                'type' => 'message',
                'title' => 'Measurement - ',
                'text' => 'Measurement has been taken.',
                'url' => route('view.measurement', $project->id),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        } else {
            $notificationData = [
                'type' => 'message',
                'title' => 'Measurement - ',
                'text' => 'Measurement has been taken.',
                'url' => route('quotation_view.measurement', $project->id),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        }


        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Measurement';
        $log->log       = 'Measurement Added';
        $log->save();

        try {
            $mobileNumber = $customer->phone;
            $customerDetail = "Name:- ".$customer->name . " and Phone Number:-". $customer->phone . " and Address:-" . $customer->address . " ".$customer->cityDetail->name." ".$customer->stateDetail->name." ".$customer->zipcode;
            // dd($customer);
            $customer_name = $customer->name;
            $message = "Dear " . $customer_name . ", your measurement for the " . $project->lead_no . " has been taken on " . date('d-m-Y h:i:s A') . " Shree Ganesh Aluminum";
            $templateid = '1407171593796762936';
            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
            $measurementList = MeasurementTask::where('project_id', $request->project_id)->get();
            foreach ($measurementList as $measurement) {
                $userDetail  = User::where('id', $measurement->user_id)->first();
                $mobile = $userDetail->phone;
                $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
            }
        } catch (Exception $e) {
            echo 'Message:' . $e->getMessage();
        }

        if (Auth::user()->role == 1) {
            return redirect()->route('view.measurement', $request->project_id)->with('success', 'Measurement created successfully.');
        } else {
            return redirect()->route('quotation_view.measurement', $request->project_id)->with('success', 'Measurement created successfully.');
        }
    }
    public function editMeasurement(Request $request, $id = NULL)
    {
        // echo '<pre>';
        // print_r($request->All());
        // exit;
        $request->validate([
            // 'description' => 'required',
        ]);
        $materialdata = Material::where('project_id', $request->project_id)->get();
        $materialinput = $request->input('measurement');

        $measurements = Measurement::where('id', $id)->first();
        $measurements->project_id = $request->project_id;
        $measurements->description = $request->description;
        $measurements->measurement_date = Carbon::today()->format('Y-m-d');
        $measurements->save();

        if ($request->hasFile('measurementfileupdated')) {
            $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx'];
            $files = $request->file('measurementfileupdated');
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $destinationPath = 'public/measurementfile/';
                $extension = $file->getClientOriginalExtension();
                $file_name = $filename;
                $file->move($destinationPath, $file_name);
                $m_file = new Measurementfile();
                if ($measurements->id) {
                    $m_file->measurement_id = $measurements->id;
                } else {
                    $newMeasurement = new Measurement();
                    $newMeasurement->project_id = $request->project_id;
                    $newMeasurement->measurement_date = Carbon::today()->format('Y-m-d');
                    $newMeasurement->save();
                    $m_file->measurement_id = $newMeasurement->id;
                }
                $m_file->project_id = $request->project_id;
                $m_file->measurement = $file_name;
                $m_file->save();
            }
        }

        if ($request->hasFile('sitephotos')) {
            $allowedfileExtension = ['jpg', 'png', 'jpeg'];
            $files = $request->file('sitephotos');
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $destinationPath = 'public/sitephoto/';
                $extension = $file->getClientOriginalExtension();
                $file_name = $filename;
                $file->move($destinationPath, $file_name);
                $m_sitephoto = new MeasurementSitePhoto();
                if ($measurements->id) {
                    $m_sitephoto->measurement_id = $measurements->id;
                } else {
                    $newMeasurement = new Measurement();
                    $newMeasurement->project_id = $request->project_id;
                    $newMeasurement->measurement_date = Carbon::today()->format('Y-m-d');
                    $newMeasurement->save();
                    $m_sitephoto->measurement_id = $newMeasurement->id;
                }
                $m_sitephoto->project_id = $request->project_id;
                $m_sitephoto->site_photo = $file_name;
                $m_sitephoto->save();
            }
        }

        $user = User::where('id', Auth::user()->id)->first();
        if (Auth::user()->role == 1) {
            $notificationData = [
                'type' => 'message',
                'title' => 'Measurement - ',
                'text' => 'Measurement has been updated.',
                'url' => route('view.measurement', $request->project_id),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        } else {
            $notificationData = [
                'type' => 'message',
                'title' => 'Measurement - ',
                'text' => 'Measurement has been updated.',
                'url' => route('quotation_view.measurement', $request->project_id),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        }


        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Measurement';
        $log->log       = 'Measurement Updated';
        $log->save();
        // if($measurements->material_option == 1){
        //     foreach ($request->input('measurement') as $key => $measurement) {
        //         if (array_key_exists('id', $measurement) ) {
        //             $existingmeasurement = Material::find($measurement['id']);
        //                 $existingmeasurement->material_selection = $measurement['material_selection'];
        //                 $existingmeasurement->material_description = $measurement['material_description'];
        //                 $existingmeasurement->save();

        //         } else {
        //                 $materialFile = new Material();
        //                 $materialFile->project_id = $request->project_id;
        //                 $materialFile->measurement_id = $measurements->id;
        //                 $materialFile->material_selection = $measurement['material_selection'];
        //                 $materialFile->material_description = $measurement['material_description'];
        //                 $materialFile->save();
        //         }
        //     }
        // }
        if (Auth::user()->role == 1) {
            return redirect()->route('view.measurement', $request->project_id)->with('success', 'Measurement created successfully.');
        } else {
            return redirect()->route('quotation_view.measurement', $request->project_id)->with('success', 'Measurement created successfully.');
        }
    }
    public function deletemeasurement($id)
    {
        $measurementfile = Measurementfile::find($id);
        $measurementfile->delete();
        $measurement_id = $measurementfile->measurement_id;

        $measurementFiles = Measurementfile::where('measurement_id', $measurement_id)->get();

        $measurementpics = MeasurementSitePhoto::where('measurement_id', $measurement_id)->get();

        if (count($measurementFiles) === 0 && count($measurementpics) === 0) {
            $measurement = Measurement::where('id', $measurement_id)->delete();
        }

        $user = User::where('id', Auth::user()->id)->first();
        if (Auth::user()->role == 1) {
            $notificationData = [
                'type' => 'message',
                'title' => 'Measurement - ',
                'text' => 'Measurement has been Deleted.',
                'url' => route('view.measurement', $measurementfile->project_id),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        } else {
            $notificationData = [
                'type' => 'message',
                'title' => 'Measurement - ',
                'text' => 'Measurement has been Deleted.',
                'url' => route('quotation_view.measurement', $measurementfile->project_id),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        }


        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Measurement';
        $log->log       = 'Measurement File Deleted';
        $log->save();
        return response()->json("success", 200);
    }

    public function deletemeasurementPic($id)
    {
        $measurementpic = MeasurementSitePhoto::find($id);
        $measurementpic->delete();
        $measurement_id = $measurementpic->measurement_id;

        $measurementFiles = Measurementfile::where('measurement_id', $measurement_id)->get();

        $measurementpics = MeasurementSitePhoto::where('measurement_id', $measurement_id)->get();

        if (count($measurementFiles) === 0 && count($measurementpics) === 0) {
            $measurement = Measurement::where('id', $measurement_id)->delete();
        }

        $user = User::where('id', Auth::user()->id)->first();
        if (Auth::user()->role == 1) {
            $notificationData = [
                'type' => 'message',
                'title' => 'Measurement - ',
                'text' => 'Measurement has been Deleted.',
                'url' => route('view.measurement', $measurementpic->project_id),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        } else {
            $notificationData = [
                'type' => 'message',
                'title' => 'Measurement - ',
                'text' => 'Measurement has been Deleted.',
                'url' => route('quotation_view.measurement', $measurementpic->project_id),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        }


        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Measurement';
        $log->log       = 'Measurement Site Photo Deleted';
        $log->save();
        return response()->json("success", 200);
    }
}
