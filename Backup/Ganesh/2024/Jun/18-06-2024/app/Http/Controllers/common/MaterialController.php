<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\MeasurementSitePhoto;
use App\Models\Project;
use App\Models\Customer;
use App\Models\User;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\Fitting;
use App\Models\Workshop;
use App\Models\AdditionalProjectDetail;
use App\Models\Setting;
use App\Models\Material;
use App\Models\Log;
use App\Models\Sitephotos;
use App\Models\WorkshopQuestion;
use App\Models\WorkshopDoneTask;
use App\Models\QuotationUpload;
use App\Models\FittingQuestion;
use App\Models\FittingDoneTask;
use App\Models\PartialDeliverDetail;

use App\Models\Invoicefile;
use Carbon\Carbon;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;

class MaterialController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        // $user = User::first();
        view()->share('setting', $setting);
    }
    public function storeMaterial(Request $request)
    {
        // echo '<pre>';
        // print_r($request->All());
        // exit;
        $type = 0;
        if ($request->type == "Additional") {
            $type = 1;
        }
        $materials = $request->purchase;
        if ($request->has('purchase') && !blank($materials)) {
            foreach ($materials as $material) {
                // if(array_key_exists('material_id',$material)){
                //     $data = Material::where('id',$material['material_id'])->first();
                //     $data->material_selection = $material['material_selection'];
                //     $data->material_description = $material['material_description'];
                //     $data->save();
                // }else{
                //     $data = new Material();
                //     $data->project_id = $request->project_id;
                //     $data->material_selection = $material['material_selection'];
                //     $data->material_description = $material['material_description'];
                //     $data->save();
                // }
                $filename = $material->getClientOriginalName();
                $destinationPath = 'public/purchases/';
                $extension = $material->getClientOriginalExtension();
                $file_name = $filename;
                $material->move($destinationPath, $file_name);

                $purchase = new Purchase();
                $purchase->project_id = $request->project_id;
                $purchase->add_work = $type;
                $purchase->purchase = $file_name;
                $purchase->save();
            }
            $project_purchase = Project::where('id', $request->project_id)->first();
            $project_purchase->step = 3;
            $project_purchase->save();

            $customer = Customer::where('id', $project_purchase->customer_id)->first();
            $customer_name = $customer->name;
            $user = User::where('id', Auth::user()->id)->first();
            if (Auth::user()->role == 1) {
                $notificationData = [
                    'type' => 'message',
                    'title' => 'Purchase Added - ',
                    'text' => 'Name: ' . $project_purchase->project_generated_id . '-' . $customer_name,
                    'url' => route('view.material', $project_purchase->id),
                ];

                Notification::send($user, new OffersNotification($notificationData));
            } else {
                $notificationData = [
                    'type' => 'message',
                    'title' => 'Purchase Added - ',
                    'text' => 'Name: ' . $project_purchase->project_generated_id . '-' . $customer_name,
                    'url' => route('quotation_view.material', $project_purchase->id),
                ];

                Notification::send($user, new OffersNotification($notificationData));
            }


            $log = new Log();
            $log->user_id = Auth::user()->name;
            $log->module = 'Purchase';
            $log->log = 'Purchase Added';
            $log->save();
        }
        if (Auth::user()->role == 1) {
            return redirect()->route('view.material', $request->project_id);
        } else {
            return redirect()->route('quotation_view.material', $request->project_id);
        }
    }
    public function viewMaterial(Request $request, $id = NULL)
    {
        $page = 'Material';
        $icon = '';
        $projects = Project::findOrFail($id);
        $additionalProjectCost = AdditionalProjectDetail::where('project_id', $id)->where('status', 'add')->orderBy('id', 'desc')->first();
        $measurements = Measurement::where('project_id', $id)->orderBy('id', 'desc')->get();
        $measurementfiles = Measurementfile::where('project_id', $id)->orderBy('add_work', 'desc')->get();
        $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        $quotations = Quotation::where('project_id', $id)->orderBy('add_work', 'desc')->first();
        $quotationfiles = Quotationfile::where('project_id', $id)->orderBy('id', 'desc')->get();
        $workshops = Workshop::where('project_id', $id)->get();
        $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        $fittings = Fitting::where('project_id', $id)->get();
        $materials = Material::where('project_id', $id)->get();
        $purchases = Purchase::where('project_id', $id)->get();
        $sitephotos = Sitephotos::where('project_id', $id)->get();
        $workshop_questions = WorkshopQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();
        $issueProject = AdditionalProjectDetail::where('project_id', $id)->where('status', 'issue')->orderBy('id', 'desc')->get();
        $purchaseUser = User::where('role', 8)->get();

        $subProjectIDArray = Project::where('sub_project_id', $projects->id)->pluck('id')->toArray();
        $subpurchases = Purchase::whereIn('project_id', $subProjectIDArray)->get();
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
            return view('admin.projects.view_material', compact('subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'subpurchases', 'purchaseUser', 'issueProject', 'additionalProjectCost', 'type', 'page', 'icon', 'projects', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'materials', 'purchases', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
            } else {
            return view('quotation.projects.view_material', compact('subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'subpurchases', 'purchaseUser', 'issueProject', 'additionalProjectCost', 'type', 'page', 'icon', 'projects', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'materials', 'purchases', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        }
    }
    public function deleteMaterial($id)
    {
        $material = Material::find($id);
        $material->delete();
        return response()->json("success", 200);
    }

    public function deletePurchase($id)
    {
        $purchase = Purchase::find($id);
        $purchase->delete();

        $project = Project::where('id', $purchase->project_id)->first();
        $customer = Customer::where('id', $project->customer_id)->first();
        $customer_name = $customer->name;
        if (Auth::user()->role == 1) {
            $user = User::where('id', Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Purchase file Deleted - ',
                'text' => 'Name: ' . $project->project_generated_id . '-' . $customer_name,
                'url' => route('view.material', $project->id),
            ];

            Notification::send($user, new OffersNotification($notificationData));
        } else {
            $user = User::where('id', Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Purchase file Deleted - ',
                'text' => 'Name: ' . $project->project_generated_id . '-' . $customer_name,
                'url' => route('quotation_view.material', $project->id),
            ];

            Notification::send($user, new OffersNotification($notificationData));
        }


        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Purchase';
        $log->log = 'Purchase File Deleted';
        $log->save();
        return response()->json("success", 200);
    }

    public function materialReceivedUpdate(Request $request)
    {
        $project = Project::where('id', $request->project_id)->first();
        $where['project_id'] = $project->id;
        $addProject = AdditionalProjectDetail::where($where)->where('status', 'issue')->orderBy('id', 'desc')->first();
        if (isset($addProject) && $project->add_work == 2) {
            if ($request->material_receive == "0") {
                $addProject->material_received_date = NULL;
                $addProject->material_received_selection = $request->material_receive;
                $addProject->material_received_number = $request->material_received_number;
                $addProject->sub_step = "Material Purchase (5.1)";
                $addProject->save();
            } else {
                $addProject->material_received_selection = $request->material_receive;
                $addProject->material_received_date = $request->material_received_date;
                $addProject->material_received_number = $request->material_received_number;
                $addProject->material_received_by = $request->material_received_by;
                $addProject->sub_step = "Material Purchase (4.2)";
                $addProject->save();
                $log = new Log();
                $log->user_id = Auth::user()->name;
                $log->module = 'Project';
                $log->log = 'Material - issue Material Received Status Updated';
                $log->save();
            }
        } else {
            if ($request->material_receive == "0") {
                $project->material_received_date = NULL;
                $project->material_received_selection = $request->material_receive;
                $project->material_received_number = $request->material_received_number;
                $project->sub_step = "Material Purchase (4.2)";
                $project->save();
            } else {
                $project->material_received_selection = $request->material_receive;
                $project->material_received_date = $request->material_received_date;
                $project->material_received_number = $request->material_received_number;
                $project->material_received_by = $request->material_received_by;
                $project->sub_step = "Material Purchase (4.2)";
                $project->save();
                $log = new Log();
                $log->user_id = Auth::user()->name;
                $log->module = 'Project';
                $log->log = 'Material - issue Material Received Status Updated';
                $log->save();
            }
        }
        return redirect()->back();
    }
}
