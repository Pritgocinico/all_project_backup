<?php

namespace App\Http\Controllers\common;

use App\Models\AdditionalProjectDetail;
use Nnjeim\World\WorldHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\Measurement;
use App\Models\GlassMeasurementFile;
use App\Models\Measurementfile;
use App\Models\MeasurementSitePhoto;
use App\Models\Invoicefile;
use App\Models\FittingQuestion;
use App\Models\FittingDoneTask;
use App\Models\MeasurementTask;
use App\Models\PartialDeliverDetail;
use App\Models\QuotationUpload;
use App\Models\Fitting;
use App\Models\City;
use App\Models\Workshop;
use App\Models\Purchase;
use App\Models\Setting;
use App\Models\TaskManagement;
use Nnjeim\World\World;
use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Log;
use App\Models\Sitephotos;
use App\Models\WorkshopQuestion;
use App\Models\WorkshopDoneTask;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;
use App\Http\Helpers\SmsHelper;
use App\Models\ProjectCompleteImage;
use App\Models\ProjectQuestion;
use App\Models\QaDoneTask;
use PDO;
use Mpdf\Mpdf;

class ProjectController extends Controller
{
    protected $world;
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }
    public function projects(Request $request)
    {
        $status = request('status');
        $page = 'Projects';
        $users = Customer::all();
        $step = $request->project_step;
        $projects = Project::with('user', 'additionalProjectWork')->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })->when($step,function($query)use($step){
            $query->where('step',$step);
        })->orderBy('id', 'DESC')->whereIn('status', [1, 2])->get();

        $type = 'Project';
        if (Auth::user()->role == 1) {
            return view('admin/projects/projects', compact('page', 'projects', 'users', 'type'));
        } else if (Auth::user()->role == 8) {
            return view('purchase/projects/projects', compact('page', 'projects', 'users', 'type'));
        }else if (Auth::user()->role == 10) {
            return view('quality_analytic/projects/projects', compact('page', 'projects', 'users', 'type'));
        }
        return view('quotation/projects/projects', compact('page', 'projects', 'users', 'type'));
    }

    public function addproject(WorldHelper $world, Request $req)
    {
        $this->world = $world;
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);

        if ($state_action->success) {
            $states = $state_action->data;
        }
        $users = Customer::all();
        $measurement_users = User::where('role', 3)->whereNot('id', 1)->get();
        $business = User::where('role', '=', 7)->get();
        $page = 'Add Projects';
        $projects = Project::all();
        $type = 'Project';
        if (Auth::user()->role == 1) {
            return view('admin/projects/add_project', compact('page', 'projects', 'states', 'users', 'business', 'type', 'measurement_users'));
        } else if (Auth::user()->role == 8) {
            return view('purchase/projects/add_project', compact('page', 'projects', 'users', 'type'));
        } else if(Auth::user()->role == 10){
            return view('qa/projects/add_project', compact('page', 'projects', 'users', 'type'));
        }
        return view('quotation/projects/add_project', compact('page', 'projects', 'states', 'users', 'business', 'type', 'measurement_users'));
    }

    public function store_project_data(Request $request)
    {
        // $projects = $request->all();
        // echo '<pre>'; print_r($request->all()); exit;
        $request->validate([
            'customer_name' => 'required|not_in:0|exists:customers,id',
            'phone_number' => 'required',
            'address' => 'required',
            'state' => 'required',
            'cityname' => 'required',
        ]);

        $currentMonthYear = date('Ym');
        $projectCount = Project::whereRaw('DATE_FORMAT(created_at, "%Y%m") = ?', [$currentMonthYear])->count();
        $projectCount++;
        $projectIdPadding = str_pad($projectCount, 5, '0', STR_PAD_LEFT);
        $projectNo = 'SGA_PR' . '_' . $projectIdPadding;
        while (Project::where('project_generated_id', $projectNo)->exists()) {
            // If it exists, increment the count and regenerate the lead number
            $projectCount++;
            $projectIdPadding = str_pad($projectCount, 5, '0', STR_PAD_LEFT);
            $projectNo = 'SGA_PR' . '_' . $projectIdPadding;
        }

        // $projects->project_name = $request->project_name;

        // $project->business_type = $request->businessType;
        $projects = new Project();
        $projects->description = $request->description;
        $projects->customer_id = $request->customer_name;
        $projects->email = $request->email;
        $projects->phone_number = $request->phone_number;
        $projects->architecture_name = $request->architecture_name;
        $projects->architecture_number = $request->architecture_number;
        $projects->supervisor_name = $request->supervisor_name;
        $projects->supervisor_number = $request->supervisor_number;
        $projects->address = $request->address;
        $projects->statename = $request->state;
        $projects->cityname = $request->cityname;

        // $projects->project_confirm_date = date("Y-m-d", strtotime($request->projectconfirmdate));
        $projects->measurement_date = Carbon::createFromFormat('d/m/Y', $request->measurementdate)->format('Y-m-d');
        // $projects->end_date = date("Y-m-d", strtotime($request->enddaterangepicker));
        $projects->start_date = Carbon::createFromFormat('d/m/Y', $request->startdaterangepicker)->format('Y-m-d');
        $projects->reference_name = $request->reference_name;
        $projects->reference_phone = $request->reference_phone;
        $projects->lead_status = 1;
        $projects->status = 1;
        $projects->type = 1;
        // $projects->lead_no = 'SGA_' . strtolower(date('M', strtotime($projects->created_at))) . '_' . $projectIdPadding;
        $projects->project_generated_id = $projectNo;
        $projects->save();

        if (!blank($request->measurement_user)) {
            $measurementUsers = $request->measurement_user;
            foreach ($measurementUsers as $user) {
                $userDetail = User::where('id', $user)->first();
                $measurementTask = new MeasurementTask();
                $measurementTask->project_id = $projects->id;
                $measurementTask->user_id = $user;
                $measurementTask->save();
                $mobileNumber = $userDetail->phone;
                $message = "Dear " . $request->customer_name . ", your lead " . $projectNo . " has been added to our system. - Shree Ganesh Aluminum";
                $templateid = '1407171593766914336';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, false);
            }
        }

        $customer = Customer::where('id', $request->customer_name)->first();
        $customer_name = $customer->name;
        $user = User::where('id', Auth::user()->id)->first();
        if (Auth::user()->role == 1) {
            $notificationData = [
                'type' => 'message',
                'title' => 'Project Added - ',
                'text' => 'Name: ' . $projects->project_generated_id . '-' . $customer_name,
                'url' => route('view.project', $projects->id),
            ];

            Notification::send($user, new OffersNotification($notificationData));
        } else {
            $notificationData = [
                'type' => 'message',
                'title' => 'Project Added - ',
                'text' => 'Name: ' . $projects->project_generated_id . '-' . $customer_name,
                'url' => route('quotation_view.project', $projects->id),
            ];

            Notification::send($user, new OffersNotification($notificationData));
        }


        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Project';
        $log->log = 'Project Created';
        $log->save();

        if ($request->hasFile('quotation_file')) {
            $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx'];
            $files = $request->file('quotation_file');
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $destinationPath = 'public/quotation_file/';
                $extension = $file->getClientOriginalExtension();
                $file_name = $filename;
                $file->move($destinationPath, $file_name);
                $check = in_array($extension, $allowedfileExtension, $destinationPath);
                if ($check) {
                    Quotationfile::create([
                        'project_id' => $projects->id,
                        'quotation' => $file_name
                    ]);
                }
            }
        }

        try {
            $mobileNumber = $request->phone_number;
            $message = "Dear " . $request->customer_name . ", your lead " . $projectNo . " has been added to our system. - Shree Ganesh Aluminum";
            $templateid = '1407171593766914336';
            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
        } catch (Exception $e) {
            echo 'Message:' . $e->getMessage();
        }

        if (Auth::user()->role == 1) {
            return redirect()->route('projects')->with('success', 'Project created successfully.');
        } else if(Auth::user()->role == 8) {
            return redirect()->route('purchase_projects')->with('success', 'Project created successfully.');
        } else if(Auth::user()->role == 10){
            return redirect()->route('qa_projects')->with('success', 'Project created successfully.');
        }
        return redirect()->route('quotation_projects')->with('success', 'Project created successfully.');
    }

    public function deleteproject($id)
    {
        $projects = Project::find($id);
        $project_task = TaskManagement::where('project_id', $id)->where('deleted_at', null)->count();
        if ($project_task > 0) {
            return response()->json("error", 200);
        } else {
            $projects->delete();

            $customer = Customer::where('id', $projects->customer_id)->first();
            $customer_name = $customer->name;
            $user = User::where('id', Auth::user()->id)->first();
            if (Auth::user()->role == 1) {
                $notificationData = [
                    'type' => 'message',
                    'title' => 'Project Deleted - ',
                    'text' => 'Name: ' . $projects->project_generated_id . '-' . $customer_name,
                    'url' => route('projects'),
                ];

                Notification::send($user, new OffersNotification($notificationData));
            } else {
                $notificationData = [
                    'type' => 'message',
                    'title' => 'Project Deleted - ',
                    'text' => 'Name: ' . $projects->project_generated_id . '-' . $customer_name,
                    'url' => route('quotation_projects'),
                ];

                Notification::send($user, new OffersNotification($notificationData));
            }


            $log = new Log();
            $log->user_id = Auth::user()->name;
            $log->module = 'Project';
            $log->log = 'Project Deleted';
            $log->save();

            return response()->json("success", 200);
        }
    }

    public function editproject(WorldHelper $world, $id)
    {
        $projects = Project::findOrFail($id);
        $this->world = $world;
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);

        if ($state_action->success) {
            $states = $state_action->data;
        }

        $cities = City::where('state_id', $projects->statename)->get();
        $users = Customer::all();
        $business = User::where('role', '=', 7)->get();
        $page = 'Edit Projects';
        $quotationfiles = Quotationfile::where('project_id', $id)->get();
        $type = 'Project';
        if (Auth::user()->role == 1) {
            return view('admin.leads.view_lead', compact('projects', 'page', 'users', 'cities', 'quotationfiles', 'business', 'type', 'states'));
        } else if(Auth::user()->role == 8){
            return view('purchase.leads.view_lead', compact('projects', 'page', 'users', 'cities', 'quotationfiles', 'business', 'type', 'states'));
        } else if(Auth::user()->role == 10){
            return view('quality_analytic.leads.view_lead', compact('projects', 'page', 'users', 'cities', 'quotationfiles', 'business', 'type', 'states'));
        }
        return view('quotation.leads.view_lead', compact('projects', 'page', 'users', 'cities', 'quotationfiles', 'business', 'type', 'states'));
    }

    public function updateproject(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            // 'project_name' => 'required',
            // 'description' => 'required',
        ]);
        $projects = Project::find($id);
        $date = Carbon::createFromFormat('d/m/Y', $request->measurementdate)
            ->format('Y-m-d');
        $projects->architecture_name = $request->architecture_name;
        $projects->architecture_number = $request->architecture_number;
        $projects->supervisor_name = $request->supervisor_name;
        $projects->supervisor_number = $request->supervisor_number;
        $projects->customer_id = $request->customer_name;
        $projects->phone_number = $request->phone_number;
        $projects->email = $request->email;
        $projects->address = $request->addressone;
        $projects->statename = $request->state;
        $projects->cityname = $request->cityname;
        $projects->zipcode = $request->zipcode;
        $projects->description = $request->description;
        $projects->measurement_date = $date;
        $projects->reference_name = $request->reference_name;
        $projects->reference_phone = $request->reference_phone;
        $projects->save();

        if (!blank($request->measurement_user)) {
            $measurementUsers = $request->measurement_user;

            // Retrieve existing users for the project
            $existingUsers = MeasurementTask::where('project_id', $id)->pluck('user_id')->toArray();

            // Users to delete
            $usersToDelete = array_diff($existingUsers, $measurementUsers);
            if (!empty($usersToDelete)) {
                MeasurementTask::where('project_id', $id)
                    ->whereIn('user_id', $usersToDelete)
                    ->delete();
            }

            // Users to add
            $usersToAdd = array_diff($measurementUsers, $existingUsers);
            foreach ($usersToAdd as $user) {
                $measurementTask = new MeasurementTask();
                $measurementTask->project_id = $id;
                $measurementTask->user_id = $user;
                $measurementTask->save();
            }
        } else {
            // If no users are selected, delete all existing entries for the project
            MeasurementTask::where('project_id', $id)->delete();
        }

        $customer = Customer::where('id', $request->customer_name)->first();
        $customer_name = $customer->name;
        $user = User::where('id', Auth::user()->id)->first();
        if (Auth::user()->role == 1) {
            $notificationData = [
                'type' => 'message',
                'title' => 'Project Updated - ',
                'text' => 'Name: ' . $projects->project_generated_id . '-' . $customer_name,
                'url' => route('view.lead', $projects->id),
            ];

            Notification::send($user, new OffersNotification($notificationData));
        } else {
            $notificationData = [
                'type' => 'message',
                'title' => 'Project Updated - ',
                'text' => 'Name: ' . $projects->project_generated_id . '-' . $customer_name,
                'url' => route('quotation_view.lead', $projects->id),
            ];

            Notification::send($user, new OffersNotification($notificationData));
        }


        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Project';
        $log->log = 'Project Updated';
        $log->save();

        if (Auth::user()->role == 1) {
            if ($request->has('project_info')) {
                return redirect()->route('view.project', $id);
            } else {
                return redirect()->route('leads')->with('success', 'Project created successfully.');
            }
        } else {
            if ($request->has('project_info')) {
                return redirect()->route('quotation_view.project', $id);
            } else {
                return redirect()->route('quotation_leads')->with('success', 'Project created successfully.');
            }
        }
    }

    public function viewproject(WorldHelper $world, Request $request, $id)
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
        // $cities = [];
        $users = Customer::all();
        $business = User::where('role', '=', 7)->get();
        $projects = Project::with('customer')->findOrFail($id);
        if ($projects->type == 1) {
            $page = 'View Project';
            $type = 'Project';
        } else {
            $page = 'View Lead';
            $type = 'Lead';
        }
        $customer = Customer::where('id', $projects->customer_id)->first();
        $quotationfiles = Quotationfile::where('project_id', $id)->get();
        $measurements = Measurement::where('project_id', $id)->orderBy('id', 'desc')->get();
        $measurementfiles = Measurementfile::where('project_id', $id)->get();
        $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        $quotations = Quotation::where('project_id', $id)->first();
        $quotationfiles = Quotationfile::where('project_id', $id)->get();
        $workshops = Workshop::where('project_id', $id)->get();
        $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        $fittings = Fitting::where('project_id', $id)->get();
        $purchases = Purchase::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $sitephotos = Sitephotos::where('project_id', $id)->get();
        $workshop_questions = ProjectQuestion::whereIn('project_id', [$id, 0])->where('question_type', 1)->orderBy('id', 'DESC')->get();
        $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->where('add_type', 0)->orderBy('id', 'DESC')->get();
        $fitting_questions = ProjectQuestion::whereIn('project_id', [$id, 0])->where('question_type', 2)->orderBy('id', 'DESC')->get();
        $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->where('add_type', 0)->orderBy('id', 'DESC')->get();

        $qa_questions = ProjectQuestion::whereIn('project_id', [$id, 0])->where('question_type', 3)->latest()->get();
        $qa_doneTasks = QaDoneTask::where('project_id', $id)->where('add_type', 0)->latest()->get();
        $measurement_users = User::where('role', 3)->whereNot('id', 1)->get();
        $measurementtaskUsers = MeasurementTask::where('project_id', $id)->get();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();
        $issueProject = AdditionalProjectDetail::where('project_id', $projects->id)->where('status', 'issue')->latest()->get();

        $subProjectIDArray = Project::where('sub_project_id', $projects->id)->pluck('id')->toArray();
        $subPurchases = Purchase::whereIn('project_id', $subProjectIDArray)->orderBy('id', 'DESC')->get();
        $subProjectData = Project::whereIn('id', $subProjectIDArray)->orderBy('id', 'DESC')->get();
        $subPartialDeliverDatas = PartialDeliverDetail::whereIn('project_id', $subProjectIDArray)->get();
        $subInvoiceFiles = Invoicefile::whereIn('project_id', $subProjectIDArray)->get();
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);

        if ($state_action->success) {
            $states = $state_action->data;
        }
        $cities = City::where('state_id', $projects->statename)->get();
        if (Auth::user()->role == 1) {
            if ($projects->step == 0) {
                return view('admin.projects.view_project', compact('qa_questions', 'qa_doneTasks', 'subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'issueProject', 'page', 'projects', 'users', 'cities', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'business', 'customer', 'purchases', 'type', 'states', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'measurementtaskUsers', 'measurement_users', 'partialDeliverDatas'));
            } elseif ($projects->step == 1) {
                return redirect()->route('view.measurement', $projects->id);
            } elseif ($projects->step == 2) {
                return redirect()->route('view.quotation', $projects->id);
            } elseif ($projects->step == 3) {
                return redirect()->route('view.material', $projects->id);
            } elseif ($projects->step == 4) {
                return redirect()->route('view.workshop', $projects->id);
            } elseif ($projects->step == 5) {
                return redirect()->route('view.fitting', $projects->id);
            } else {
                return redirect()->route('view.fitting', $projects->id);
            }
        } else if (Auth::user()->role == 8) {
            if ($projects->step == 0) {
                return view('purchase.projects.view_project', compact('qa_questions', 'qa_doneTasks', 'subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'issueProject', 'page', 'projects', 'users', 'cities', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'business', 'customer', 'purchases', 'type', 'states', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'measurementtaskUsers', 'measurement_users', 'partialDeliverDatas'));
            } elseif ($projects->step == 1) {
                return redirect()->route('purchase_view.measurement', $projects->id);
            } elseif ($projects->step == 2) {
                return redirect()->route('purchase_view.quotation', $projects->id);
            } elseif ($projects->step == 3) {
                return redirect()->route('purchase_view.material', $projects->id);
            } elseif ($projects->step == 4) {
                return redirect()->route('purchase_view.workshop', $projects->id);
            } elseif ($projects->step == 5) {
                return redirect()->route('purchase_view.fitting', $projects->id);
            } else {
                return redirect()->route('purchase_view.fitting', $projects->id);
            }
        } else if(Auth::user()->role == 10) {
            if ($projects->step == 0) {
                return view('qa.projects.view_project', compact('qa_questions', 'qa_doneTasks', 'subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'issueProject', 'page', 'projects', 'users', 'cities', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'business', 'customer', 'purchases', 'type', 'states', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'measurementtaskUsers', 'measurement_users', 'partialDeliverDatas'));
            } elseif ($projects->step == 1) {
                return redirect()->route('qa_view.measurement', $projects->id);
            } elseif ($projects->step == 2) {
                return redirect()->route('qa_view.quotation', $projects->id);
            } elseif ($projects->step == 3) {
                return redirect()->route('qa_view.material', $projects->id);
            } elseif ($projects->step == 4) {
                return redirect()->route('qa_view.workshop', $projects->id);
            } elseif ($projects->step == 5) {
                return redirect()->route('qa_view.fitting', $projects->id);
            } else {
                return redirect()->route('qa_view.fitting', $projects->id);
            }
        } else{
            if ($projects->step == 0) {
                return view('quotation.projects.view_project', compact('qa_questions', 'qa_doneTasks', 'subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'issueProject', 'page', 'projects', 'users', 'cities', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'business', 'customer', 'purchases', 'type', 'states', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'measurementtaskUsers', 'measurement_users', 'partialDeliverDatas'));
            } elseif ($projects->step == 1) {
                return redirect()->route('quotation_view.measurement', $projects->id);
            } elseif ($projects->step == 2) {
                return redirect()->route('quotation_view.quotation', $projects->id);
            } elseif ($projects->step == 3) {
                return redirect()->route('quotation_view.material', $projects->id);
            } elseif ($projects->step == 4) {
                return redirect()->route('quotation_view.workshop', $projects->id);
            } elseif ($projects->step == 5) {
                return redirect()->route('quotation_view.fitting', $projects->id);
            } else {
                return redirect()->route('quotation_view.fitting', $projects->id);
            }
        } 
    }

    public function viewProgressBarproject(WorldHelper $world, Request $request, $id)
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
        // $cities = [];
        $users = Customer::all();
        $business = User::where('role', '=', 7)->get();
        $projects = Project::with('customer')->findOrFail($id);
        if ($projects->type == 1) {
            $page = 'View Project';
            $type = 'Project';
        } else {
            $page = 'View Lead';
            $type = 'Lead';
        }
        $customer = Customer::where('id', $projects->customer_id)->first();
        $quotationfiles = Quotationfile::where('project_id', $id)->get();
        $addProject = AdditionalProjectDetail::where('project_id', $id)->where('status', 'add')->first();
        $measurements = Measurement::where('project_id', $id)->orderBy('add_work', 'desc')->get();
        $measurementfiles = Measurementfile::where('project_id', $id)->orderBy('id', 'desc')->get();
        $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        $quotations = Quotation::where('project_id', $id)->orderBy('id', 'desc')->first();
        $quotationfiles = Quotationfile::where('project_id', $id)->get();
        $workshops = Workshop::where('project_id', $id)->get();
        $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        $fittings = Fitting::where('project_id', $id)->get();
        $purchases = Purchase::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $sitephotos = Sitephotos::where('project_id', $id)->get();
        $workshop_questions = WorkshopQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->whereNull('issue_id')->orderBy('id', 'DESC')->get();
        $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->whereNull('issue_id')->orderBy('id', 'DESC')->get();
        $measurement_users = User::where('role', 3)->whereNot('id', 1)->get();
        $measurementtaskUsers = MeasurementTask::where('project_id', $id)->get();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();
        $issueProject = AdditionalProjectDetail::with('workshopIssueTask', 'fittingIssueTask')->where('project_id', $id)->where('status', 'issue')->latest()->get();

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
        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);

        if ($state_action->success) {
            $states = $state_action->data;
        }

        $cities = City::where('state_id', $projects->statename)->get();

        if (Auth::user()->role == 1) {
            return view('admin.projects.view_project', compact('subInvoiceFiles', 'subPartialDeliverDatas', 'subProjectData', 'subPurchases', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'issueProject', 'addProject', 'page', 'projects', 'users', 'cities', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'business', 'customer', 'purchases', 'type', 'states', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'measurement_users', 'measurementtaskUsers', 'partialDeliverDatas'));
        } else if (Auth::user()->role == 8) {
            return view('purchase.projects.view_project', compact('page', 'projects', 'users', 'type'));
        }else if (Auth::user()->role == 10) {
            return view('quality_analytic.projects.view_project', compact('page', 'projects', 'users', 'type'));
        }
        return view('quotation.projects.view_project', compact('page', 'projects', 'users', 'cities', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'business', 'customer', 'purchases', 'type', 'states', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'measurement_users', 'measurementtaskUsers', 'partialDeliverDatas'));
    }

    public function convertToLead($projectId)
    {
        // Find the project by ID
        $project = Project::findOrFail($projectId);

        // Create a new lead instance
        $lead = new Lead();

        // Map specific fields from the project to the lead
        $lead->customer_id = $project->customer_id;
        $lead->reference_name = $project->reference_name; // You may adjust this field accordingly
        $lead->phone_number = $project->phone_number; // Adjust according to your Project and Lead structure
        $lead->email = $project->email; // Adjust according to your Project and Lead structure

        // Set the lead status to 3 (Hold)
        $lead->lead_status = 3; // Assuming 3 is the status code for "Hold"

        // Assuming lead_no is a required field, assign a value to it
        $lead->lead_no = 'generate_a_unique_lead_number_here'; // You may use your own logic to generate a unique lead number

        // Save the lead
        $lead->save();

        // Delete the project
        $project->delete();

        // Redirect to the leads page with a success message
        if (Auth::user()->role == 1) {
            return redirect()->route('leads')->with('success', 'Project converted to lead and set to Hold status successfully');
        } else if(Auth::user()->role == 8) {
            return redirect()->route('purchase_leads')->with('success', 'Project converted to lead and set to Hold status successfully');
        } else if(Auth::user()->role == 10){
            return redirect()->route('qa_leads')->with('success', 'Project converted to lead and set to Hold status successfully');    
        }
        return redirect()->route('quotation_leads')->with('success', 'Project converted to lead and set to Hold status successfully');
    }

    public function updateMaterialStatus(Request $request)
    {
        $project = Project::where('id', $request->project_id)->first();
        $project->material_selection = $request->material_option;
        if ($request->material_option == 0) {
            $project->selection_date = NULL;
        } else {
            $project->selection_date = $request->selection_date;
        }
        $customer = Customer::where('id', $project->customer_id)->first();
        $project->save();
        $mobileNumber = $customer->phone;
        $route = route('feedbackForm', $request->project_id);
        // $message = "Dear " . $customer_name . ", your " . $project->project_generated_id . " has been completed. Kindly review us in this " . $route . ". Shree Ganesh Aluminum.";
        // $templateid = '1407171593897710905';
        // $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);

        $measurementList = MeasurementTask::where('project_id', $request->project_id)->get();
        foreach ($measurementList as $measurement) {
            $userDetail = User::where('id', $measurement->user_id)->first();
            $mobile = $userDetail->phone;
            // $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
        }
        $filltingUser = User::where('role', '4')->get();
        foreach ($filltingUser as $fitting) {
            $mobile = $fitting->phone;
            // $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
        }
        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Project';
        $log->log = 'Material Selection Status Updated';
        $log->save();
        return redirect()->back();
    }

    public function storeProjectCost(Request $request)
    {
        $project = Project::where('id', $request->project_id)->first();
        $project->quotation_cost = $request->quotation_cost;
        $project->project_cost = $request->project_cost;
        $project->transport_cost = $request->transport_cost;
        $project->laber_cost = $request->laber_cost;
        $totalCost = $request->quotation_cost + $request->transport_cost + $request->laber_cost;
        $project->margin_cost = $request->project_cost - $totalCost;
        $project->save();

        $addProject = AdditionalProjectDetail::where('project_id', $project->id)->where('status', 'issue')->first();
        if (isset($addProject) && $project->add_work == 2) {
            $addProject->quotation_cost = $request->quotation_cost;
            $addProject->project_cost = $request->add_quotation_cost;
            $addProject->transport_cost = $request->transport_cost;
            $addProject->laber_cost = $request->laber_cost;
            $addProject->margin_cost = $request->project_cost - $totalCost;
            $addProject->quotation_cost = $request->quotation_cost;
            $addProject->save();
        }
        return redirect()->back();
    }

    public function getCustomer(Request $request)
    {
        $customer = Customer::where('id', $request->id)->first();
        return response()->json($customer, 200);
    }

    public function getCities(Request $request, $id = NULL)
    {
        $cities = City::where('state_id', $id)->get();
        $html = '';
        $html .= '<option value="">Select City</option>';
        if (!blank($cities)) {
            foreach ($cities as $city) {
                $html .= '<option value="' . $city['id'] . '">' . $city['name'] . '</option>';
            }
        }
        return response()->json($html);
    }

    public function projectDone(Request $request)
    {
        $selection = $request->project_done;
        $project = Project::where('id', $request->project_id)->first();
        $customer = Customer::where('id', $project->customer_id)->first();
        $where['project_id'] = $project->id;
        if ($selection == 1) {
            $project->status = 2;
            $project->step = 6;
            if ($project->add_work == 1) {
                $project->add_work = 0;
                $update['end_date'] = $request->end_date;
                AdditionalProjectDetail::where($where)->update($update);
            } else {
                $project->end_date = $request->end_date;
            }
            $project->save();
            if (isset($request->project_done_photos)) {
                foreach ($request->project_done_photos as $key => $image) {
                    $projectImage = new ProjectCompleteImage();
                    $projectImage->project_id = $project->id;
                    $filename = $image->getClientOriginalName();
                    $destinationPath = 'public/project/';
                    $extension = $image->getClientOriginalExtension();
                    $file_name = $filename;
                    $image->move($destinationPath, $file_name);
                    $projectImage->file = $file_name;
                    $projectImage->save();
                }
            }
            $msg = 'Project Completed.';

            $customer = Customer::where('id', $project->customer_id)->first();
            $customer_name = $customer->name;
            $user = User::where('id', Auth::user()->id)->first();
            if (Auth::user()->role == 1) {
                $notificationData = [
                    'type' => 'message',
                    'title' => 'Project Completed - ',
                    'text' => 'Name: ' . $project->project_generated_id . '-' . $customer_name,
                    'url' => route('view.lead', $project->id),
                ];

                Notification::send($user, new OffersNotification($notificationData));
            } else {
                $notificationData = [
                    'type' => 'message',
                    'title' => 'Project Completed - ',
                    'text' => 'Name: ' . $project->project_generated_id . '-' . $customer_name,
                    'url' => route('quotation_view.lead', $project->id),
                ];

                Notification::send($user, new OffersNotification($notificationData));
            }


            $log = new Log();
            $log->user_id = Auth::user()->name;
            $log->module = 'Project';
            $log->log = 'Project Completed';
            $log->save();

            try {
            } catch (Exception $e) {
                echo 'Message:' . $e->getMessage();
            }

            try {
                $mobileNumber = $customer->phone;
                $route = route('feedbackForm', $request->project_id);
                $message = "Dear " . $customer_name . ", your " . $project->project_generated_id . " has been completed. Kindly review us in this " . $route . ". Shree Ganesh Aluminum.";
                $templateid = '1407171593897710905';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);

                $measurementList = MeasurementTask::where('project_id', $request->project_id)->get();
                foreach ($measurementList as $measurement) {
                    $userDetail = User::where('id', $measurement->user_id)->first();
                    $mobile = $userDetail->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                }
                $filltingUser = User::where('role', '5')->get();
                foreach ($filltingUser as $fitting) {
                    $mobile = $fitting->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                }

                $quatationUser = User::where('role', '6')->get();
                foreach ($quatationUser as $quatation) {
                    $mobile = $quatation->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                }
            } catch (Exception $e) {
                echo 'Message:' . $e->getMessage();
            }

            if (Auth::user()->role == 1) {
                return redirect()->route('projects')->with('success', $msg);
            } else if (Auth::user()->role == 8) {
                return redirect()->route('purchase_projects')->with('success', $msg);
            } else if (Auth::user()->role == 10) {
                return redirect()->route('qa_projects')->with('success', $msg);
            }
            return redirect()->route('quotation_projects')->with('success', $msg);
        } else {
            $project->status = 1;
            $msg = '';
            $project->end_date = NULL;
            $project->save();
            return redirect()->back()->with('message', $msg);
        }
    }

    public function viewcomplete(Request $request, $id)
    {
        $page = 'View Complete';
        $projects = Project::findOrFail($id);
        $quotations = Quotation::where('project_id', $id)->first();
        $addProject = AdditionalProjectDetail::where('project_id', $id)->where('status', 'add')->orderBy('id', 'desc')->get();
        $quotationfiles = Quotationfile::where('project_id', $id)->orderBy('add_work', 'desc')->get();
        $measurements = Measurement::where('project_id', $id)->first();
        // $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id','DESC')->get();
        // $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id','DESC')->get();
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
        $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
        $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();
        $issueProject = AdditionalProjectDetail::where('project_id', $id)->where('status', 'issue')->latest()->get();

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
            return view('admin.projects.view_complete', compact('subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'issueProject', 'addProject', 'type', 'page', 'projects', 'fittings', 'purchases', 'workshops', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        } else if (Auth::user()->role == 8) {
            return view('purchase.projects.view_complete', compact('page', 'projects', 'users', 'type'));
        } else if(Auth::user()->role == 10){
            return view('quality_analytic.projects.view_complete', compact('addProject', 'type', 'page', 'projects', 'fittings', 'purchases', 'workshops', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        }
        return view('quotation.projects.view_complete', compact('addProject', 'type', 'page', 'projects', 'fittings', 'purchases', 'workshops', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
    }

    public function updateCutting(Request $request)
    {
        $project = Project::where('id', $request->project_id)->first();
        $userDetail = Customer::where('id', $project->customer_id)->first();
        $where['project_id'] = $project->id;
        $addProject = AdditionalProjectDetail::where($where)->where('status', 'issue')->latest()->first();
        if (isset($addProject) && $project->add_type == 2) {
            if ($request->cutting_option == 0) {
                $message = "Workshop cutting for " . $userDetail->name . " - " . $project->project_generated_id . " is not done, kindly complete it withing next 24 hours. - Shri Ganesh Aluminum.";
                $templateid = '1407171593914575779';
                $quatationUser = User::where('role', 5)->get();
                foreach ($quatationUser as $key => $quatation) {
                    $mobile = $quatation->phone;
                    $status = false;
                    if ($key == 0) {
                        $status = true;
                    }
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, $status);
                }

                $addProject->cutting_date = NULL;
                $addProject->cutting_selection = $request->cutting_option;
                $addProject->sub_step = "Cutting (5.1)";
                $addProject->save();
            } else {
                $addProject->cutting_date = $request->cutting_date;
                $addProject->cutting_selection = $request->cutting_option;
                $addProject->sub_step = "Cutting (5.1)";
                $addProject->save();
            }
        } else {
            if ($request->cutting_option == 0) {
                $message = "Workshop cutting for " . $userDetail->name . " - " . $project->project_generated_id . " is not done, kindly complete it withing next 24 hours. - Shri Ganesh Aluminum.";
                $templateid = '1407171593914575779';
                $quatationUser = User::where('role', 5)->get();
                foreach ($quatationUser as $key => $quatation) {
                    $mobile = $quatation->phone;
                    $status = false;
                    if ($key == 0) {
                        $status = true;
                    }
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, $status);
                }
                $project->cutting_date = NULL;
                $project->cutting_selection = $request->cutting_option;
                $project->sub_step = "Cutting (5.1)";
            } else {
                $project->cutting_selection = $request->cutting_option;
                $project->cutting_date = $request->cutting_date;
                $project->sub_step = "Cutting (5.1)";
            }
            if ($project->cutting_selection == 1 && $project->shutter_selection == 1 && $project->glass_measurement == 1 && $project->glass_receive == 1 && $project->shutter_ready == 1 && $project->invoice_status == 1 && ($project->material_delivered == 1 || $project->material_delivered == 2)) {
                $project->step = 5;
            }
            $project->save();
        }
        $mobileNumber = $userDetail->phone;
        $message = "Dear " . $userDetail->name . ", your workshop cutting has been done and shifted to shutter joinery phase. - Shree Ganesh Aluminum.";
        $templateid = '1407171593906874402';
        $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
        $quatationUser = User::where('role', '5')->get();
        foreach ($quatationUser as $quatation) {
            $mobile = $quatation->phone;
            $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
        }


        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Project';
        $log->log = 'Workshop - Cutting Status Updated';
        $log->save();
        return redirect()->back();
    }

    public function updateShutter(Request $request)
    {
        $project = Project::where('id', $request->project_id)->first();

        $userDetail = Customer::where('id', $project->customer_id)->first();
        $where['project_id'] = $project->id;
        $addProject = AdditionalProjectDetail::where($where)->where('status', 'issue')->latest()->first();
        if (isset($addProject) && $project->add_type == 2) {
            if ($request->shutter_joinery == 0) {
                $message = "Workshop shutter joinery for " . $userDetail->name . " - " . $project->project_generated_id . " is not done. Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                $templateid = '1407171593956996465';
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $key => $quatation) {
                    $status = false;
                    if ($key == 0) {
                        $status = true;
                    }
                    $mobile = $quatation->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, $status);
                }
                $addProject->shutter_date = Null;
                $addProject->shutter_selection = $request->shutter_joinery;
                $addProject->sub_step = "Shutter Joinery (5.2)";
                $addProject->save();
            } else {
                $mobileNumber = $userDetail->phone;
                $message = "Dear " . $userDetail->name . ", your workshop shutter joinery has been done and shifted to glass measurement. - Shree Ganesh Aluminum.";
                $templateid = '1407171593931045555';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $quatation) {
                    $mobile = $quatation->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                }
                $addProject->shutter_date = $request->shutter_date;
                $addProject->shutter_selection = $request->shutter_joinery;
                $addProject->sub_step = "Shutter Joinery (5.2)";
                $addProject->save();
            }
        } else {
            if ($request->shutter_joinery == 0) {
                $message = "Workshop shutter joinery for " . $userDetail->name . " - " . $project->project_generated_id . " is not done. Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                $templateid = '1407171593956996465';
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $key => $quatation) {
                    $status = false;
                    if ($key == 0) {
                        $status = true;
                    }
                    $mobile = $quatation->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, $status);
                }
                $project->shutter_selection = $request->shutter_joinery;
                $project->shutter_date = NULL;
                $project->sub_step = "Shutter Joinery (5.2)";
                $project->save();
            } else {
                $mobileNumber = $userDetail->phone;
                $message = "Dear " . $userDetail->name . ", your workshop shutter joinery has been done and shifted to glass measurement. - Shree Ganesh Aluminum.";
                $templateid = '1407171593931045555';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $quatation) {
                    $mobile = $quatation->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                }
                $project->shutter_date = $request->shutter_date;
                $project->shutter_selection = $request->shutter_joinery;
                $project->sub_step = "Shutter Joinery (5.2)";
            }
            if ($project->cutting_selection == 1 && $project->shutter_selection == 1 && $project->glass_measurement == 1 && $project->glass_receive == 1 && $project->shutter_ready == 1 && $project->invoice_status == 1 && ($project->material_delivered == 1 || $project->material_delivered == 2)) {
                $project->step = 5;
            }
            $project->save();
        }


        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Project';
        $log->log = 'Workshop - Shutter Joinery Status Updated';
        $log->save();
        return redirect()->back();
    }

    public function updateGlassmeasure(Request $request)
    {
        $project = Project::where('id', $request->project_id)->first();

        $userDetail = Customer::where('id', $project->customer_id)->first();
        $where['project_id'] = $project->id;
        $addProject = AdditionalProjectDetail::where($where)->where('status', 'issue')->latest()->first();
        if (isset($addProject) && $project->add_type == 2) {
            if ($request->glass_measurement == 0) {
                $message = "Workshop glass measurement for " . $userDetail->name . " - " . $project->project_generated_id . " is not done. Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                $templateid = '1407171594268172670';
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $key => $quatation) {
                    $mobile = $quatation->phone;
                    $status = false;
                    if ($key == 0) {
                        $status = true;
                    }
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, $status);
                }
                $addProject->glass_date = null;
                $addProject->glass_measurement = $request->glass_measurement;
                $addProject->sub_step = "Glass Measurement (5.3)";
                $addProject->save();
            } else {

                $mobileNumber = $userDetail->phone;
                $message = "Dear " . $userDetail->name . ", your workshop glass measurement has been done for your project " . $project->project_generated_id . ". Shree Ganesh Aluminum";
                $templateid = '1407171594259015718';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $quatation) {
                    $mobile = $quatation->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                }
                $addProject->glass_date = $request->glass_date;
                $addProject->glass_measurement = $request->glass_measurement;
                $addProject->sub_step = "Glass Measurement (5.3)";
                $addProject->save();
            }
        } else {
            if ($request->glass_measurement == 0) {
                $message = "Workshop glass measurement for " . $userDetail->name . " - " . $project->project_generated_id . " is not done. Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                $templateid = '1407171594268172670';
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $key => $quatation) {
                    $mobile = $quatation->phone;
                    $status = false;
                    if ($key == 0) {
                        $status = true;
                    }
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, $status);
                }
                $project->glass_measurement = $request->glass_measurement;
                $project->glass_date = NULL;
                $project->sub_step = "Glass Measurement (5.3)";
            } else {

                $mobileNumber = $userDetail->phone;
                $message = "Dear " . $userDetail->name . ", your workshop glass measurement has been done for your project " . $project->project_generated_id . ". Shree Ganesh Aluminum";
                $templateid = '1407171594259015718';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $quatation) {
                    $mobile = $quatation->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                }
                $project->glass_date = $request->glass_date;
                $project->glass_measurement = $request->glass_measurement;
                $project->sub_step = "Glass Measurement (5.3)";
                if ($request->hasFile('glass_file')) {
                    $files = $request->file('glass_file');
                    $filename = $files->getClientOriginalName();
                    $destinationPath = 'public/glassmeasurementfiles/';
                    $file_name = $filename;
                    $files->move($destinationPath, $file_name);
                    $invoiceFile = new GlassMeasurementFile();
                    if ($project->add_work == 1) {
                        $invoiceFile->add_work = 1;
                    }

                    $invoiceFile->project_id = $request->project_id;
                    $invoiceFile->file = $file_name;
                    $invoiceFile->save();
                }
                if ($project->cutting_selection == 1 && $project->shutter_selection == 1 && $project->glass_measurement == 1 && $project->glass_receive == 1 && $project->shutter_ready == 1 && $project->invoice_status == 1 && ($project->material_delivered == 1 || $project->material_delivered == 2)) {
                    $project->step = 5;
                }
                $project->save();
            }
        }

        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Project';
        $log->log = 'Workshop - Glass Measurement Status Updated';
        $log->save();
        return redirect()->back();
    }

    public function updateGlassReceive(Request $request)
    {
        $project = Project::where('id', $request->project_id)->first();
        $userDetail = Customer::where('id', $project->customer_id)->first();
        $where['project_id'] = $project->id;
        $addProject = AdditionalProjectDetail::where($where)->where('status', 'issue')->latest()->first();
        if (isset($addProject) && $project->add_type == 2) {
            if ($request->glass_receive == 0) {
                $message = "Workshop glass is not received for " . $userDetail->name . " - " . $project->project_generated_id . ". Shree Ganesh Aluminum";
                $templateid = '1407171594282255716';
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $key => $quatation) {
                    $mobile = $quatation->phone;
                    $status = false;
                    if ($key == 0) {
                        $status = true;
                    }
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, $status);
                }
                $addProject->glass_date = null;
                $addProject->glass_measurement = $request->glass_measurement;
                $addProject->sub_step = "Glass Received (5.4)";
                $addProject->save();
            } else {
                $mobileNumber = $userDetail->phone;
                $message = "Dear " . $userDetail->name . ", your workshop glass has been received and it is being ready for the workshop fitting for project " . $project->project_generated_id . ". Shree Ganesh Aluminum";
                $templateid = '1407171594274932255';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $quatation) {
                    $mobile = $quatation->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                }
                $addProject->glass_receive_date = $request->glass_receive_date;
                $addProject->glass_receive = $request->glass_receive;
                $addProject->sub_step = "Glass Received (5.4)";
                $addProject->save();
            }
        } else {
            if ($request->glass_receive == 0) {
                $message = "Workshop glass is not received for " . $userDetail->name . " - " . $project->project_generated_id . ". Shree Ganesh Aluminum";
                $templateid = '1407171594282255716';
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $key => $quatation) {
                    $mobile = $quatation->phone;
                    $status = false;
                    if ($key == 0) {
                        $status = true;
                    }
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, $status);
                }
                $project->glass_receive = $request->glass_receive;
                $project->glass_receive_date = NULL;
                $project->sub_step = "Glass Received (5.4)";
            } else {
                $mobileNumber = $userDetail->phone;
                $message = "Dear " . $userDetail->name . ", your workshop glass has been received and it is being ready for the workshop fitting for project " . $project->project_generated_id . ". Shree Ganesh Aluminum";
                $templateid = '1407171594274932255';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $quatation) {
                    $mobile = $quatation->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                }
                $project->glass_receive_date = $request->glass_receive_date;
                $project->glass_receive = $request->glass_receive;
                $project->sub_step = "Glass Received (5.4)";
            }
            if ($project->cutting_selection == 1 && $project->shutter_selection == 1 && $project->glass_measurement == 1 && $project->glass_receive == 1 && $project->shutter_ready == 1 && $project->invoice_status == 1 && ($project->material_delivered == 1 || $project->material_delivered == 2)) {
                $project->step = 5;
            }
            $project->save();
        }
        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Project';
        $log->log = 'Workshop - Glass Received Status Updated';
        $log->save();
        return redirect()->back();
    }

    public function updateShutterReady(Request $request)
    {
        $project = Project::where('id', $request->project_id)->first();
        $userDetail = Customer::where('id', $project->customer_id)->first();
        $where['project_id'] = $project->id;
        $addProject = AdditionalProjectDetail::where($where)->where('status', 'issue')->latest()->first();
        if (isset($addProject) && $project->add_type == 2) {
            if ($request->shutter_ready == 0) {
                $message = "Workshop glass fitting is not done for " . $userDetail->name . " - " . $project->project_generated_id . ". Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                $templateid = '1407171594295256186';
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $key => $quatation) {
                    $mobile = $quatation->phone;
                    $status = false;
                    if ($key == 0) {
                        $status = true;
                    }
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, $status);
                }
                $addProject->shutter_ready_date = null;
                $addProject->shutter_ready = $request->shutter_ready;
                $addProject->sub_step = "Glass fitting (5.5)";
                $addProject->save();
            } else {
                $mobileNumber = $userDetail->phone;
                $message = "Dear " . $userDetail->name . ", your workshop glass fitting has been done and invoice is being generated for the project " . $project->project_generated_id . ". Shree Ganesh Aluminum";
                $templateid = '1407171594290366119';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $quatation) {
                    $mobile = $quatation->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                }
                $addProject->shutter_ready_date = $request->shutter_ready_date;
                $addProject->shutter_ready = $request->shutter_ready;
                $addProject->sub_step = "Glass fitting (5.5)";
                $addProject->save();
            }
        } else {
            if ($request->shutter_ready == 0) {
                $message = "Workshop glass fitting is not done for " . $userDetail->name . " - " . $project->project_generated_id . ". Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                $templateid = '1407171594295256186';
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $key => $quatation) {
                    $mobile = $quatation->phone;
                    $status = false;
                    if ($key == 0) {
                        $status = true;
                    }
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, $status);
                }
                $project->shutter_ready_date = NULL;
                $project->shutter_ready = $request->shutter_ready;
                $project->sub_step = "Glass fitting (5.5)";
            } else {
                $mobileNumber = $userDetail->phone;
                $message = "Dear " . $userDetail->name . ", your workshop glass fitting has been done and invoice is being generated for the project " . $project->project_generated_id . ". Shree Ganesh Aluminum";
                $templateid = '1407171594290366119';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $quatationUser = User::where('role', '5')->get();
                foreach ($quatationUser as $quatation) {
                    $mobile = $quatation->phone;
                    $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                }
                $project->shutter_ready_date = $request->shutter_ready_date;
                $project->shutter_ready = $request->shutter_ready;
                $project->sub_step = "Glass fitting (5.5)";
            }
            if ($project->cutting_selection == 1 && $project->shutter_selection == 1 && $project->glass_measurement == 1 && $project->glass_receive == 1 && $project->shutter_ready == 1 && $project->invoice_status == 1 && ($project->material_delivered == 1 || $project->material_delivered == 2)) {
                $project->step = 5;
            }
            $project->save();
        }

        $log = new Log();
        $log->user_id = Auth::user()->name;
        $log->module = 'Project';
        $log->log = 'Workshop - Shutter Fitting Status Updated';
        $log->save();
        return redirect()->back();
    }

    public function viewCompletedproject(WorldHelper $world, Request $request, $id = NULL)
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
        $projects = Project::with('customer')->findOrFail($id);
        if ($projects->type == 1) {
            $page = 'View Project';
            $type = 'Project';
        } else {
            $page = 'View Lead';
            $type = 'Lead';
        }

        // $customer = Customer::where('id',$projects->customer_id)->first();
        // $measurements = Measurement::where('project_id', $id)->orderBy('id', 'desc')->get();
        // $measurementfiles = Measurementfile::where('project_id', $id)->get();
        // $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        // $quotations = Quotation::where('project_id', $id)->first();
        // $quotationfiles = Quotationfile::where('project_id', $id)->get();
        // $workshops = Workshop::where('project_id', $id)->get();
        //     $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        //     $fittings = Fitting::where('project_id', $id)->get();
        // $purchases = Purchase::where('project_id',$id)->orderBy('id','DESC')->get();
        //     $sitephotos = Sitephotos::where('project_id', $id)->get();
        // $workshop_questions = WorkshopQuestion::whereIn('project_id', [$id, 0])->orderBy('id','DESC')->get();
        // $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->orderBy('id','DESC')->get();
        // $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id','DESC')->get();
        // $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id','DESC')->get();
        // $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();

        $customer = Customer::where('id', $projects->customer_id)->first();
        $measurements = collect();
        $measurementfiles = collect();
        $measurementphotos = collect();
        $quotations = collect();
        $quotationfiles = collect();
        $workshops = collect();
        $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        $fittings = Fitting::where('project_id', $id)->get();
        $purchases = collect();
        $sitephotos = Sitephotos::where('project_id', $id)->get();
        $workshop_questions = collect();
        $workshop_doneTasks = collect();
        $fitting_questions = collect();
        $fitting_doneTasks = collect();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();

        if ($projects->step >= 1) {
            $measurements = Measurement::where('project_id', $id)->orderBy('id', 'desc')->get();
            $measurementfiles = Measurementfile::where('project_id', $id)->get();
            $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        }
        if ($projects->step >= 2) {
            $quotations = Quotation::where('project_id', $id)->first();
            $quotationfiles = Quotationfile::where('project_id', $id)->get();
        }
        if ($projects->step >= 3) {
            $purchases = Purchase::where('project_id', $id)->orderBy('id', 'DESC')->get();
        }
        if ($projects->step >= 4) {
            $workshop_questions = WorkshopQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
            $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        }
        if ($projects->step >= 5) {
            $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
            $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        }

        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);

        if ($state_action->success) {
            $states = $state_action->data;
        }

        $cities = City::where('state_id', $projects->statename)->get();
        if (Auth::user()->role == 1) {
            return view('admin.projects.view_completedproject', compact('page', 'projects', 'users', 'cities', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'business', 'customer', 'purchases', 'type', 'states', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        } else if (Auth::user()->role == 8) {
            return view('purchase.projects.view_completedproject', compact('page', 'projects', 'users', 'cities', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'business', 'customer', 'purchases', 'type', 'states', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
        }
        return view('quotation.projects.view_completedproject', compact('page', 'projects', 'users', 'cities', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'business', 'customer', 'purchases', 'type', 'states', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
    }

    public function generateReport(WorldHelper $world, Request $request, $id)
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
        $projects = Project::with('customer')->findOrFail($id);

        $customer = Customer::where('id', $projects->customer_id)->first();
        $measurements = collect();
        $measurementfiles = collect();
        $measurementphotos = collect();
        $quotations = collect();
        $quotationfiles = collect();
        $workshops = collect();
        $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        $fittings = Fitting::where('project_id', $id)->get();
        $purchases = collect();
        $sitephotos = Sitephotos::where('project_id', $id)->get();
        $workshop_questions = collect();
        $workshop_doneTasks = collect();
        $fitting_questions = collect();
        $fitting_doneTasks = collect();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();

        if ($projects->step >= 1) {
            $measurements = Measurement::where('project_id', $id)->orderBy('id', 'desc')->get();
            $measurementfiles = Measurementfile::where('project_id', $id)->get();
            $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        }
        if ($projects->step >= 2) {
            $quotations = Quotation::where('project_id', $id)->first();
            $quotationfiles = Quotationfile::where('project_id', $id)->get();
        }
        if ($projects->step >= 3) {
            $purchases = Purchase::where('project_id', $id)->orderBy('id', 'DESC')->get();
        }
        if ($projects->step >= 4) {
            $workshop_questions = WorkshopQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
            $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        }
        if ($projects->step >= 5) {
            $fitting_questions = FittingQuestion::whereIn('project_id', [$id, 0])->orderBy('id', 'DESC')->get();
            $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        }

        $state_action = $this->world->states([
            'filters' => [
                'country_id' => 102,
            ],
        ]);

        if ($state_action->success) {
            $states = $state_action->data;
        }
        $page = "pdf";
        $type = "pdf";
        $cities = City::where('state_id', $projects->statename)->get();
        return view('admin.pdf.view_complete', compact('page', 'projects', 'users', 'cities', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'quotations', 'quotationfiles', 'workshops', 'fittings', 'business', 'customer', 'purchases', 'type', 'states', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'fitting_questions', 'fitting_doneTasks', 'partialDeliverDatas'));
    }

    public function resumeWork(Request $request)
    {
        $request->validate([
            'resume_work_option' => 'required',
        ]);
        $projectDetail = Project::where('id', $request->project_id)->first();
        if ($request->resume_work_option == "2") {
            $update['step'] = 4;
            $update['status'] = 2;
            $update['add_work'] = 2;
            $where['id'] = $request->project_id;
            $update['total_issue_work'] = isset($projectDetail) ? $projectDetail->total_issue_work + 1 : 1;
            Project::where($where)->update($update);
            $additionalProject['status'] = "issue";
            $additionalProject['sub_step'] = "workshop";
            $additionalProject['project_id'] = $request->project_id;
            AdditionalProjectDetail::create($additionalProject);
            if (Auth()->user()->role == 1) {
                return redirect()->route('view.workshop', $projectDetail->id);
            }
            if (Auth::user()->role == 8) {
                return redirect()->route('purchase_view.workshop', $projectDetail->id);
            }
            return redirect()->route('quotation_view.workshop', $projectDetail->id);
        }
    }
    public function resumeWorkComplete(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'resume_work_option_complete' => 'required',
        ]);

        $projectDetail = Project::where('id', $request->project_id)->first();
        if ($request->resume_work_option_complete == "0") {
            $update['step'] = 5;
            $update['status'] = 2;
            $update['add_work'] = 0;
            $where['id'] = $request->project_id;
            Project::where($where)->update($update);
            $additionalProject['end_date'] = date('Y-m-d');
            AdditionalProjectDetail::where('project_id', $request->project_id)->update($additionalProject);
            if (Auth()->user()->role == 1) {
                return redirect()->route('projects', $projectDetail->id);
            } else {
                return redirect()->route('purchase_projects', $projectDetail->id);
            }
            return redirect()->route('quotation_projects', $projectDetail->id);
        }
    }
    public function cancelLead(Request $request)
    {
        $projectID = $request->project_id;
        $project = Project::where('id', $projectID)->first();
        if (isset($project)) {
            $project->lead_cancel_status = 1;
            $project->lead_cancel_date_time = Carbon::now();
            $project->save();
            return response()->json(['status' => 1, 'message' => 'Lead cancel successfully.'], 200);
        }
        return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 500);
    }

    public function addAdditionalProject(Request $request, $id = NULL)
    {
        $project = Project::where('id', $id)->first();
        if ($project) {
            $currentMonthYear = date('Ym');
            $leadCount = Project::whereRaw('DATE_FORMAT(created_at, "%Y%m") = ?', [$currentMonthYear])->count();
            $leadCount++;
            $leadIdPadding = str_pad($leadCount, 5, '0', STR_PAD_LEFT);
            $leadNo = 'SGA_SUB_PR' . '_' . $leadIdPadding;
            while (Project::where('lead_no', $leadNo)->exists()) {
                $leadCount++;
                $leadIdPadding = str_pad($leadCount, 5, '0', STR_PAD_LEFT);
                $leadNo = 'SGA_SUB_PR' . '_' . $leadIdPadding;
            }
            $create = [
                'project_generated_id' => $leadNo,
                'customer_id' => $project->customer_id,
                'phone_number' => $project->phone_number,
                'email' => $project->email,
                'address' => $project->address,
                'cityname' => $project->cityname,
                'statename' => $project->statename,
                'start_date' => $project->start_date,
                'measurement_date' => $project->measurement_date,
                'sub_project_id' => $project->id,
                'project_name' => $project->project_name,
                'business_type' => $project->business_type,
                'business_name' => $project->business_name,
                'gst_number' => $project->gst_number,
                'customer_name' => $project->customer_name,
                'reference_name' => $project->reference_name,
                'reference_phone' => $project->reference_phone,
                'zipcode' => $project->zipcode,
                'status' => 1,
                'step' => 1,
                'type' => 1,
            ];
            $createProject = Project::create($create);
            if ($createProject) {
                return response()->json(['status' => 1, 'id' => $createProject->id, 'message' => 'project Add successfully.'], 200);
            }
            return response()->json(['status' => 0, 'error' => 'Something wen to wrong.'], 500);
        }
        return response()->json(['status' => 0, 'error' => 'Project Not Found.'], 500);
    }
    public function convertLead($id)
    {
        $project = Project::where('id', $id)->first();
        if ($project) {
            $project->lead_status = 1;
            $project->type = 0;
            $project->status = 0;
            $update = $project->save();
            if ($update) {
                return response()->json(['status' => 1, 'id' => $project->id, 'message' => 'project Converted into lead successfully.'], 200);
            }
            return response()->json(['status' => 0, 'error' => 'Something wen to wrong.'], 500);
        }
        return response()->json(['status' => 0, 'error' => 'Project Not Found.'], 500);
    }
    public function qaQuestionList($id){
        $page = 'View Fittings';
        $projects = Project::findOrFail($id);
        $quotations = Quotation::where('project_id', $id)->orderBy('add_work', 'desc')->first();
        $additionalProject = AdditionalProjectDetail::where('project_id', $id)->where('status', 'add')->first();
        $quotationfiles = Quotationfile::where('project_id', $id)->get();
        $measurements = Measurement::where('project_id', $id)->first();
        $fitting_questions = ProjectQuestion::whereIn('project_id', [$id, 0])->where('question_type',1)->orderBy('id', 'DESC')->get();
        $fitting_doneTasks = FittingDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $partialDeliverDatas = PartialDeliverDetail::where('project_id', $id)->get();
        $measurementfiles = Measurementfile::where('project_id', $id)->orderBy('add_work', 'desc')->get();
        $measurementphotos = MeasurementSitePhoto::where('project_id', $id)->get();
        $workshops = Workshop::where('project_id', $id)->get();
        $invoiceFiles = Invoicefile::where('project_id', $id)->get();
        $fittings = Fitting::where('project_id', $id)->get();
        $purchases = Purchase::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $sitephotos = Sitephotos::where('project_id', $id)->get();
        $workshop_questions = ProjectQuestion::whereIn('project_id', [$id, 0])->where('question_type',1)->orderBy('id', 'DESC')->get();
        $workshop_doneTasks = WorkshopDoneTask::where('project_id', $id)->orderBy('id', 'DESC')->get();
        $issueProject = AdditionalProjectDetail::with('workshopIssueTask','fittingIssueTask')->where('project_id', $id)->where('status', 'issue')->latest()->get();

        $subProjectIDArray = Project::where('sub_project_id', $projects->id)->pluck('id')->toArray();
        $submeasurements = Measurement::whereIn('project_id', $subProjectIDArray)->orderBy('id', 'desc')->get();
        $sumeasurementfiles = Measurementfile::whereIn('project_id', $subProjectIDArray)->orderBy('add_work', 'desc')->get();
        $sumeasurementphotos = MeasurementSitePhoto::whereIn('project_id', $subProjectIDArray)->get();

        $subquotations = Quotation::whereIn('project_id', $subProjectIDArray)->orderBy('add_work', 'desc')->first();
        $subquotationfiles = Quotationfile::whereIn('project_id', $subProjectIDArray)->get();
        $sub_quotation_uploads = QuotationUpload::whereIn('project_id', $subProjectIDArray)->get();

        $qa_questions = ProjectQuestion::whereIn('project_id',[$id,0])->where('question_type',3)->latest()->get();
        $qa_doneTasks = QaDoneTask::where('project_id', $id)->where('add_type', 0)->latest()->get();

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
            return view('admin.projects.view_qa', compact('qa_questions','qa_doneTasks','subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'issueProject', 'additionalProject', 'type', 'page', 'projects', 'fittings', 'purchases', 'workshops', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'fitting_questions', 'fitting_doneTasks', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'partialDeliverDatas'));
        } else if(Auth::user()->role == 8) {
            return view('purchase.projects.view_qa', compact('qa_questions','qa_doneTasks','subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'issueProject', 'additionalProject', 'type', 'page', 'projects', 'fittings', 'purchases', 'workshops', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'fitting_questions', 'fitting_doneTasks', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'partialDeliverDatas'));
        }else if(Auth::user()->role == 10){
            return view('qa.projects.view_qa', compact('qa_questions','qa_doneTasks','subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'issueProject', 'additionalProject', 'type', 'page', 'projects', 'fittings', 'purchases', 'workshops', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'fitting_questions', 'fitting_doneTasks', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'partialDeliverDatas'));
        }
        return view('quotation.projects.view_qa', compact('qa_questions','qa_doneTasks','subPurchases', 'subProjectData', 'subPartialDeliverDatas', 'subInvoiceFiles', 'subquotations', 'subquotationfiles', 'sub_quotation_uploads', 'submeasurements', 'sumeasurementfiles', 'sumeasurementphotos', 'issueProject', 'additionalProject', 'type', 'page', 'projects', 'fittings', 'purchases', 'workshops', 'quotations', 'quotationfiles', 'measurements', 'measurementfiles', 'measurementphotos', 'fitting_questions', 'fitting_doneTasks', 'sitephotos', 'workshop_questions', 'workshop_doneTasks', 'invoiceFiles', 'partialDeliverDatas'));
    }
}
