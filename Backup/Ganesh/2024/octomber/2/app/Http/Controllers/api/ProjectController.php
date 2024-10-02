<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AdditionalProjectDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Log;
use App\Models\MeasurementTask;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use App\Models\Purchase;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\MeasurementSitePhoto;
use App\Models\QuotationUpload;
use App\Models\Quotationfile;
use App\Models\Quotation;
use App\Models\TaskManagement;
use App\Models\Sitephotos;
use App\Models\Invoicefile;
use App\Models\PartialDeliverDetail;
use App\Http\Helpers\SmsHelper;
use App\Models\GlassMeasurementFile;
use App\Models\ProjectCompleteImage;

class ProjectController extends Controller
{

    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }

    public function listProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            if ($accessToken->type == "customer") {
                $user = Customer::where('id', $accessToken->user_id)->first();
                $status = $request->status;
                $projects = Project::where('type', 1)->where('customer_id', $accessToken->user_id)->when($status == 1 || $status == 2, function ($query) use ($status) {
                    $query->where('status', $status);
                })->orderBy('id', 'desc')->get();
            } else {
                $user = User::where('id', $accessToken->user_id)->first();
                $role = $user->role;
                $step = $request->step;
                $status = $request->status;
                $projects = Project::where('type', 1)->when($status == 1 || $status == 2, function ($query) use ($status) {
                    $query->where('status', $status);
                })->when($step, function ($query) use ($step, $status) {
                    $query->where('step', $step);
                })->orderBy('id', 'desc')->get();
            }
            if (!blank($projects)) {
                $array_push = array();
                foreach ($projects as $project) {
                    $customer = Customer::where('id', $project->customer_id)->first();
                    $array = array();
                    $array['id'] = $project->id;
                    $array['project_generated_id'] = $project->project_generated_id;
                    $array['customer_id'] = $project->customer_id;
                    $customer = Customer::where('id', $project->customer_id)->first();
                    $array_push0 = array();
                    if (!blank($customer)) {
                        $array0 = array();
                        $array0['id'] = $customer->id;
                        $array0['name'] = $customer->name;
                        $array0['email'] = $customer->email;
                        $array0['phone'] = $customer->phone;
                        $array0['address'] = $customer->address;
                        $array0['city'] = $customer->city;
                        $array0['state'] = $customer->state;
                        $array0['zipcode'] = $customer->zipcode;
                        $array0['created_at'] = date('d/m/Y', strtotime($customer->created_at));
                        array_push($array_push0, $array0);
                    }
                    $array['customer_details'] = $array_push0;
                    $stepName = "Not Started";
                    if ($project->status == 1) {
                        $stepName = "In Progress - ";
                        if ($project->step == 0) {
                            $stepName .= "Project Created";
                        }
                        if ($project->step == 1) {
                            $stepName .= "Measurement -";
                            if ($project->material_selection == 1) {
                                $stepName .= " Selection Done";
                            } else {
                                $stepName .= " Selection Not Done";
                            }
                        }
                        if ($project->step == 2) {
                            $stepName .= "Quotation";
                        }
                        if ($project->step == 3) {
                            $stepName .= "Purchase";
                        }
                        if ($project->step == 4) {
                            $stepName .= "Workshop";
                            if (!blank($project->sub_step)) {
                                $stepName .=  " - " . $project->sub_step;
                            }
                        }
                        if ($project->step == 5) {
                            $stepName .= "Site Installation";
                        }
                    } elseif ($project->status == 2) {
                        $stepName = "Completed";
                    }
                    $array['step'] = ($project->step != NULL) ? (int) $project->step : 0;
                    $array['step_name'] = $stepName;
                    $array['phone_number'] = $project->phone_number;
                    $array['architecture_name'] = $project->architecture_name;
                    $array['architecture_number'] = $project->architecture_number;
                    $array['supervisor_name'] = $project->supervisor_name;
                    $array['supervisor_number'] = $project->supervisor_number;
                    $array['email'] = $project->email;
                    $array['address'] = $project->address;
                    $array['city'] = $project->cityname;
                    $array['statename'] = $project->statename;
                    $array['zipcode'] = $project->zipcode;
                    $array['description'] = $project->description;
                    $array['start_date'] = ($project->start_date != NULL) ? date('d/m/Y', strtotime($project->start_date)) : "";
                    $array['measurement_date'] = ($project->measurement_date != NULL) ? date('d/m/Y', strtotime($project->measurement_date)) : "";
                    $array['reference_name'] = $project->reference_name;
                    $array['reference_phone'] = $project->reference_phone;
                    $array['status'] = $project->status;
                    $array['lead_no'] = $project->lead_no;
                    $array['lead_status'] = $project->lead_status;
                    $array['quotation_cost'] = $project->quotation_cost;
                    $array['project_cost'] = $project->project_cost;
                    $array['material_selection'] = $project->material_selection;
                    $array['reject_reason'] = $project->reject_reason;
                    $array['add_work'] = (string) $project->add_work;
                    $array['total_issue_work'] = $project->total_issue_work;
                    $array['total_additional_work'] = $project->total_additional_work;
                    $array['created_at'] = ($project->created_at != NULL) ? date('d/m/Y', strtotime($project->created_at)) : "";

                    $measurements = Measurement::where('project_id', $project->id)->get();
                    $array_push1 = array();
                    if (!blank($measurements)) {
                        foreach ($measurements as $measurement) {
                            $array1 = array();
                            $array1['id'] = $measurement->id;
                            $array1['add_work'] = $measurement->add_work;
                            $array1['project_id'] = ($measurement->project_id != NULL) ? $measurement->project_id : "";
                            $array1['description'] = ($measurement->description != NULL) ? $measurement->description : "";
                            $array1['measurement_date'] = ($measurement->measurement_date != NULL) ? date('d/m/Y', strtotime($measurement->measurement_date)) : "";
                            $array1['created_at'] = ($measurement->created_at != NULL) ? date('d/m/Y', strtotime($measurement->created_at)) : "";
                            $array1['measurement_type'] = $measurement->measurement_type;
                            $measurementfiles = Measurementfile::where('project_id', $project->id)->where('measurement_id', $measurement->id)->get();
                            if (!blank($measurementfiles)) {
                                $m_files = array();
                                foreach ($measurementfiles as $measurementfile) {
                                    $files = array();
                                    $files['id'] = $measurementfile->id;
                                    $files['add_work'] = $measurementfile->add_work;
                                    $files['measurement_id'] = $measurementfile->measurement_id;
                                    $files['measurement'] = url('/') . '/public/measurementfile/' . $measurementfile->measurement;
                                    $files['measurement_type'] = $measurementfile->file_type;
                                    $files['created_at'] = date('d/m/Y', strtotime($measurementfile->created_at));
                                    array_push($m_files, $files);
                                }
                                $array1['measurement_files'] = $m_files;
                            } else {
                                $array1['measurement_files'] = [];
                            }

                            $measurementSitephotos = MeasurementSitePhoto::where('project_id', $project->id)->where('measurement_id', $measurement->id)->get();
                            if (!blank($measurementSitephotos)) {
                                $m_photos = array();
                                foreach ($measurementSitephotos as $measurementPhoto) {
                                    $files2 = array();
                                    $files2['id'] = $measurementPhoto->id;
                                    $files2['project_id'] = $measurementPhoto->project_id;
                                    $files2['add_work'] = $measurementPhoto->add_work;
                                    $files2['measurement_id'] = $measurementPhoto->measurement_id;
                                    $files2['sitePhoto'] = url('/') . '/public/sitephoto/' . $measurementPhoto->site_photo;
                                    $files2['created_at'] = date('d/m/Y', strtotime($measurementPhoto->created_at));
                                    array_push($m_photos, $files2);
                                }
                                $array1['measurement_sitephotos'] = $m_photos;
                            } else {
                                $array1['measurement_sitephotos'] = [];
                            }
                            array_push($array_push1, $array1);
                        }
                    }
                    $array['measurements'] = $array_push1;

                    $quotations = Quotation::where('project_id', $project->id)->where('deleted_at', null)->get();
                    $array_push2 = array();
                    if (!blank($quotations)) {
                        foreach ($quotations as $quotation) {
                            $array2 = array();
                            $array2['id'] = $quotation->id;
                            $array2['project_id'] = $quotation->project_id;
                            $array2['quotation_date'] = ($quotation->quotation_date != NULL) ? date('d/m/Y', strtotime($quotation->quotation_date)) : "";
                            $array2['quotation_done'] = $quotation->quotation_done;
                            $array2['created_by'] = $quotation->created_by;
                            $array2['add_work'] = $quotation->add_work;
                            $array2['created_at'] = ($quotation->created_at != NULL) ? date('d/m/Y', strtotime($quotation->created_at)) : "";
                            $quotationfiles = Quotationfile::where('quotation_id', $quotation->id)->orderBy('id', 'desc')->get();
                            $q_files = array();
                            if (!blank($quotationfiles)) {
                                foreach ($quotationfiles as $files) {
                                    $qfiles = array();
                                    $qfiles['id'] = $files->id;
                                    $qfiles['project_id'] = $files->project_id;
                                    $qfiles['quotation_id'] = $files->quotation_id;
                                    $qfiles['is_final'] = $files->final;
                                    $qfiles['add_work'] = $files->add_work;
                                    $qfiles['description'] = ($files->description != '') ? $files->description : "";
                                    $qfiles['cost'] = $files->cost;
                                    $qfiles['project_cost'] = $files->project_cost;
                                    $qfiles['status'] = $files->status;
                                    if ($files->status == 2) {
                                        if ($files->reject_reason == 1) {
                                            $reason = 'Delayed/Cool Of';
                                        } elseif ($files->reject_reason == 2) {
                                            $reason = 'Cancel';
                                        } elseif ($files->reject_reason == 3) {
                                            $reason = 'Addon';
                                        } else {
                                            $reason = '';
                                        }
                                        $qfiles['reject_reason'] = $reason;
                                        $qfiles['reject_note'] = ($files->reject_note != '') ? $files->reject_note : "";
                                    }
                                    $qfiles['created_at'] = date('d/m/Y', strtotime($files['created_at']));
                                    array_push($q_files, $qfiles);
                                }
                            }
                            $array2['quotation_file'] = $q_files;
                            $quotation_uploads = QuotationUpload::where('quotation_id', $quotation->id)->get();
                            $q_uploads = array();
                            if (!blank($quotation_uploads)) {
                                foreach ($quotation_uploads as $uploads) {
                                    $quploads = array();
                                    $quploads['id'] = $uploads->id;
                                    $quploads['quotation_id'] = $uploads->quotation_id;
                                    $quploads['quotation_file_id'] = $uploads->quotation_file_id;
                                    $quploads['project_id'] = $uploads->project_id;
                                    $quploads['file'] = $uploads->file;
                                    $quploads['file_name'] = $uploads->file_name;
                                    $quploads['add_work'] = $uploads->add_work;
                                    $quploads['created_at'] = ($uploads->created_at != NULL) ? date('d/m/Y', strtotime($uploads->created_at)) : "";
                                    array_push($q_uploads, $quploads);
                                }
                            }
                            $array2['quotation_uploads'] = $q_uploads;
                            array_push($array_push2, $array2);
                        }
                    }
                    $array['quotations'] = $array_push2;

                    $purchases = Purchase::where('project_id', $project->id)->get();
                    $purchase_data = array();
                    if (!blank($purchases)) {
                        foreach ($purchases as $purchaseItem) {
                            $purchaseDetails = array();
                            $purchaseDetails['id'] = $purchaseItem->id;
                            $purchaseDetails['project_id'] = $purchaseItem->project_id;
                            $purchaseDetails['file'] = url('/') . 'public/purchases/' . $purchaseItem->purchase;
                            $purchaseDetails['file_name'] = $purchaseItem->purchase;
                            $purchaseDetails['created_at'] = date('d/m/Y', strtotime($purchaseItem->created_at));
                            array_push($purchase_data, $purchaseDetails);
                        }
                    }
                    $array['purchases'] = $purchase_data;

                    array_push($array_push, $array);
                }
                return response()->json([
                    'status' => 1,
                    'project' => $array_push
                ], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'No Project found.'], 404);
            }
        }
    }

    public function addProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'customer_id' => 'required|not_in:0|exists:customers,id',
            'phone_number' => 'required',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $customer = Customer::where('id', $request->customer_id)->first();
            if (!blank($customer)) {
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
                $project = new Project();
                $project->customer_id = $request->customer_id;
                $project->phone_number = $request->phone_number;
                $project->architecture_name = $request->architecture_name;
                $project->architecture_number = $request->architecture_number;
                $project->supervisor_name = $request->supervisor_name;
                $project->supervisor_number = $request->supervisor_number;
                $project->email = $request->email;
                $project->address = $request->address;
                $project->zipcode = $request->zipcode;
                $project->description = $request->description;
                $project->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y/m/d');
                $project->measurement_date = Carbon::createFromFormat('d/m/Y', $request->measurement_date)->format('Y/m/d');
                $project->reference_name = $request->reference_name;
                $project->reference_phone = $request->reference_phone;
                $project->project_generated_id = $projectNo;
                $project->lead_status = 1;
                $project->status = 1;
                $project->type = 1;
                $project->statename = $request->state;
                $project->cityname = $request->city;
                $project->save();

                $log = new Log();
                $log->user_id = $user->name;
                $log->module = 'Project';
                $log->log = 'Project Added.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Project has been added successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Customer does not exist.'], 404);
            }
            return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
        }
    }

    public function updateProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'customer_id' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $project = Project::where('id', $request->project_id)->first();
            if (!blank($project)) {
                $customer = Customer::where('id', $request->customer_id)->first();
                if (!blank($customer)) {
                    $project->customer_id = $request->customer_id;
                    $project->phone_number = $request->phone_number;
                    $project->architecture_name = $request->architecture_name;
                    $project->architecture_number = $request->architecture_number;
                    $project->supervisor_name = $request->supervisor_name;
                    $project->supervisor_number = $request->supervisor_number;
                    $project->email = $request->email;
                    $project->address = $request->address;
                    $project->zipcode = $request->zipcode;
                    $project->description = $request->description;
                    $project->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y/m/d');
                    $project->measurement_date = Carbon::createFromFormat('d/m/Y', $request->measurement_date)->format('Y/m/d');
                    $project->reference_name = $request->reference_name;
                    $project->reference_phone = $request->reference_phone;
                    $project->statename = $request->state;
                    $project->cityname = $request->city;
                    $project->save();

                    $log = new Log();
                    $log->user_id = $user->name;
                    $log->module = 'Project';
                    $log->log = 'Project Updated.';
                    $log->save();
                    return response()->json(['status' => 1, 'message' => 'Project has been Updated successfully.'], 200);
                } else {
                    return response()->json(['status' => 0, 'error' => 'Customer does not exist.'], 404);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Project does not exist.'], 404);
            }
            return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
        }
    }

    public function addPurchaseFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'purchase' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $project = Project::where('id', $request->project_id)->first();
            if (!blank($project)) {
                $materials = $request->purchase;
                if ($request->has('purchase') && !blank($materials)) {
                    foreach ($materials as $material) {
                        $filename = $material->getClientOriginalName();
                        $destinationPath = 'public/purchases/';
                        $extension = $material->getClientOriginalExtension();
                        $file_name = $filename;
                        $material->move($destinationPath, $file_name);

                        $purchase = new Purchase();
                        $purchase->project_id = $request->project_id;
                        $purchase->add_work = $project->add_work;
                        $purchase->purchase = $file_name;
                        $purchase->save();
                    }
                }
                $log = new Log();
                $log->user_id = $user->name;
                $log->module = 'Project - purchase';
                $log->log = 'Purchase file added.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Purchase file has been added successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Project does not exist.'], 404);
            }
            return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
        }
    }

    public function listPurchaseFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $project = Project::where('id', $request->project_id)->first();
            if (!blank($project)) {
                $materials = Purchase::where('project_id', $request->project_id)->orderBy('id', 'desc')->get();
                if (!blank($materials)) {
                    $array_push = array();
                    foreach ($materials as $material) {
                        $array = array();
                        $array['id'] = $material->id;
                        $array['project_id'] = $material->project_id;
                        $array['purchase_file'] = $material->purchase;
                        $array['created_at'] = ($material->created_at != NULL) ? date('d/m/Y', strtotime($material->created_at)) : "";
                        array_push($array_push, $array);
                    }
                    return response()->json([
                        'status' => 1,
                        'purchase_file' => $array_push
                    ], 200);
                }
                return response()->json(['status' => 1, 'message' => 'Purchase data does not exist.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Project does not exist.'], 404);
            }
            return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
        }
    }

    public function projectCostCalculation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $project = Project::where('id', $request->project_id)->first();
            if (!blank($project)) {
                $marginCost = $request->project_cost - ($request->quotation_cost + $request->transport_cost + $request->laber_cost);
                if ($project->add_work == 1) {
                    $addWork = AdditionalProjectDetail::where('project_id', $project->id)->where('status', 'add')->first();
                    $addWork->quotation_cost = $request->quotation_cost;
                    $addWork->project_cost = $request->project_cost;
                    $addWork->transport_cost = $request->transport_cost ?? 0;
                    $addWork->laber_cost = $request->laber_cost;
                    $addWork->margin_cost = $marginCost;
                    $addWork->save();
                } else {
                    $project->quotation_cost = $request->quotation_cost;
                    $project->project_cost = $request->project_cost;
                    $project->transport_cost = $request->transport_cost ?? 0;
                    $project->laber_cost = $request->laber_cost;
                    $project->margin_cost = $marginCost;
                    $project->save();

                    $addProject = AdditionalProjectDetail::where('project_id', $project->id)->where('status', 'issue')->first();
                    if (isset($addProject) && $project->add_type == 2) {
                        $addProject->quotation_cost = $request->quotation_cost;
                        $addProject->project_cost = $request->project_cost;
                        $addProject->transport_cost = $request->transport_cost ?? 0;
                        $addProject->laber_cost = $request->laber_cost;
                        $addProject->margin_cost = $marginCost;
                        $addProject->save();
                    }
                }

                return response()->json(['status' => 1, 'message' => 'Project Cost Calculation.', 'margin' => $marginCost], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Project does not exist.'], 404);
            }
            return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
        }
    }

    public function ConfirmProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'project_final' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $project = Project::where('id', $request->project_id)->first();
            if (!blank($project)) {
                $selection = $request->project_final;
                if ($selection == 1) {
                    $project->project_generated_id = str_replace('SGA_LD_', 'SGA_PR_', $project->lead_no);
                    $project->status = 1;
                    $project->type = 1;
                    $project->start_date = Carbon::now()->format('Y/m/d');
                    $msg = "Lead has been converted to project.";
                    $log = new Log();
                    $log->user_id = $user->name;
                    $log->module = 'Project';
                    $log->log = 'Lead has been converted to project.';
                    $log->save();
                } else {
                    $project->status = 0;
                    $project->type = 0;
                    $msg = 'Lead has not been converted to project.';
                }
                $project->save();
                return response()->json(['status' => 1, 'message' => $msg], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Project does not exist.'], 404);
            }
            return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
        }
    }

    public function deleteProject(Request $request, $id)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $project = Project::where('id', $id)->first();
        if (!blank($project)) {
            $project_tasks = TaskManagement::where('project_id', $id)->where('deleted_at', null)->count();
            if ($project_tasks > 0) {
                return response()->json(["status" => 0, "error" => "Cannot delete Project, please delete project related tasks first."]);
            } else {
                $project->delete();
                $log = new Log();
                $log->user_id = $user->name;
                $log->module = 'Project';
                $log->log = 'Project Deleted.';
                $log->save();
                return response()->json(["status" => 1, "message" => 'Project has been Deleted Successfully.']);
            }
        } else {
            return response()->json(["status" => 0, "error" => "Project not found."]);
        }
    }

    public function viewProject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            if ($accessToken->type == "customer") {
                $user = Customer::where('id', $accessToken->user_id)->first();
                $role = $user->role;
            } else {
                $user = User::where('id', $accessToken->user_id)->first();
                $role = $user->role;;
            }
            $project = Project::where('id', $id)->first();
            if (!blank($project)) {
                $array = array();
                $customer = Customer::where('id', $project->customer_id)->first();
                $array_push0 = array();
                if (!blank($customer)) {
                    $array0 = array();
                    $array0['id'] = $customer->id;
                    $array0['name'] = $customer->name;
                    $array0['email'] = $customer->email;
                    $array0['phone'] = $customer->phone;
                    $array0['address'] = $customer->address;
                    $array0['city'] = $customer->city;
                    $array0['state'] = $customer->state;
                    $array0['zipcode'] = $customer->zipcode;
                    $array0['created_at'] = date('d/m/Y', strtotime($customer->created_at));
                    array_push($array_push0, $array0);
                }
                $stepName = "Not Started";
                if ($project->status == 1) {
                    $stepName = "In Progress - ";
                    if ($project->step == 0) {
                        $stepName .= "Project Created";
                    }
                    if ($project->step == 1) {
                        $stepName .= "Measurement -";
                        if ($project->material_selection == 1) {
                            $stepName .= " Selection Done";
                        } else {
                            $stepName .= " Selection Not Done";
                        }
                    }
                    if ($project->step == 2) {
                        $stepName .= "Quotation";
                    }
                    if ($project->step == 3) {
                        $stepName .= "Purchase";
                    }
                    if ($project->step == 4) {
                        $stepName .= "Workshop";
                        if (!blank($project->sub_step)) {
                            $stepName .=  " - " . $project->sub_step;
                        }
                    }
                    if ($project->step == 5) {
                        $stepName .= "Site Installation";
                    }
                } elseif ($project->status == 2) {
                    $stepName = "Completed";
                }
                $array['id'] = $project->id;
                $array['parent_project_id'] = $project->sub_project_id ?? '';
                $array['project_generated_id'] = ($project->project_generated_id != NULL) ? $project->project_generated_id : "";
                $array['customer_id'] = ($project->customer_id != NULL) ? $project->customer_id : "";
                $array['phone_number'] = ($project->phone_number != NULL) ? $project->phone_number : "";
                $array['email'] = ($project->email != NULL) ? $project->email : "";
                $array['address'] = ($project->address != NULL) ? $project->address : "";
                $array['city'] = ($project->cityname != NULL) ? $project->cityname : "";
                $array['state'] = ($project->statename != NULL) ? $project->statename : "";
                $array['zipcode'] = ($project->zipcode != NULL) ? $project->zipcode : "";
                $array['description'] = ($project->description != NULL) ? $project->description : "";
                $array['reference_name'] = ($project->reference_name != NULL) ? $project->reference_name : "";
                $array['reference_phone'] = ($project->reference_phone != NULL) ? $project->reference_phone : "";
                $array['architecture_name'] = ($project->architecture_name != NULL) ? $project->architecture_name : "";
                $array['architecture_number'] = ($project->architecture_number != NULL) ? $project->architecture_number : "";
                $array['supervisor_name'] = ($project->supervisor_name != NULL) ? $project->supervisor_name : "";
                $array['supervisor_number'] = ($project->supervisor_number != NULL) ? $project->supervisor_number : "";
                $array['description'] = ($project->description != NULL) ? $project->description : "";
                $array['measurement_date'] = ($project->measurement_date != NULL) ? date('d/m/Y', strtotime($project->measurement_date)) : "";
                $array['lead_status'] = ($project->lead_status != NULL) ? (int) $project->lead_status : 0;
                $array['type'] = ($project->type != NULL) ? (int) $project->type : 0;
                $array['step'] = ($project->step != NULL) ? (int) $project->step : 0;
                $array['step_name'] = $stepName;
                $array['quotation_cost'] = ($project->quotation_cost != NULL) ? (int) $project->quotation_cost : 0;
                $array['project_cost'] = ($project->project_cost != NULL) ? (int) $project->project_cost : 0;
                $array['start_date'] = ($project->start_date != NULL) ? date('d/m/Y', strtotime($project->start_date)) : "";
                $array['end_date'] = ($project->end_date != NULL) ? date('d/m/Y', strtotime($project->end_date)) : "";
                $array['margin_cost'] = ($project->margin_cost != NULL) ? (int) $project->margin_cost : 0;
                $array['complete_at'] = $project->created_at != NULL ? date('d/m/Y', strtotime($project->created_at)) : "";
                $array['material_selection'] = ($project->material_selection != NULL) ? (int) $project->material_selection : 0;
                $array['add_work'] = ($project->add_work != NULL) ? (string) $project->add_work : (string) 0;
                $array['total_issue_work'] = $project->total_issue_work ?? 0;
                $array['total_additional_work'] = $project->total_additional_work ?? 0;
                $array['customer_details'] = $array_push0;
                $measurements = Measurement::where('project_id', $project->id)->get();
                $array_push1 = array();
                $subProjectIDArray = Project::where('sub_project_id', $project->id)->pluck('id')->toArray();
                $submeasurements = Measurement::whereIn('project_id', $subProjectIDArray)->orderBy('id', 'desc')->get();
                $subquotations = Quotation::whereIn('project_id', $subProjectIDArray)->get();
                $subPurchase = Purchase::whereIn('project_id', $subProjectIDArray)->get();
                $subsitePhotos = Sitephotos::whereIn('project_id', $subProjectIDArray)->get();
                $subProjectData = Project::whereIn('id', $subProjectIDArray)->get();

                $projectImage = ProjectCompleteImage::where('project_id', $id)->latest()->get();
                $arrayImage = array();
                if (!blank($projectImage)) {
                    $project_files = array();
                    foreach ($projectImage as $key => $image) {
                        $files = array();
                        $files['id'] = $image->id;
                        $files['measurement'] = url('/') . '/public/project/' . $image->file;
                        $files['created_at'] = date('d/m/Y', strtotime($image->created_at));
                        $files['add_work'] = 0;
                        array_push($project_files, $files);
                    }
                    $arrayImage = $project_files;
                }
                $array['project_files'] = $arrayImage;
                if (!blank($measurements)) {
                    foreach ($measurements as $measurement) {
                        $array1 = array();
                        $array1['id'] = $measurement->id;
                        $array1['project_id'] = ($measurement->project_id != NULL) ? $measurement->project_id : "";
                        $array1['description'] = ($measurement->description != NULL) ? $measurement->description : "";
                        $array1['measurement_date'] = ($measurement->measurement_date != NULL) ? date('d/m/Y', strtotime($measurement->measurement_date)) : "";
                        $array1['add_work'] = 0;
                        $array1['measurement_type'] = $measurement->measurement_type;
                        $array1['created_at'] = ($measurement->created_at != NULL) ? date('d/m/Y', strtotime($measurement->created_at)) : "";
                        $measurementfiles = Measurementfile::where('project_id', $project->id)->where('measurement_id', $measurement->id)->get();
                        if (!blank($measurementfiles)) {
                            $m_files = array();
                            foreach ($measurementfiles as $measurementfile) {
                                $files = array();
                                $files['id'] = $measurementfile->id;
                                $files['measurement_id'] = $measurementfile->measurement_id;
                                $files['measurement'] = url('/') . '/public/measurementfile/' . $measurementfile->measurement;
                                $files['created_at'] = date('d/m/Y', strtotime($measurementfile->created_at));
                                $files['measurement_type'] = $measurementfile->file_type;
                                $files['add_work'] = 0;
                                array_push($m_files, $files);
                            }
                            $array1['measurement_files'] = $m_files;
                        } else {
                            $array1['measurement_files'] = [];
                        }

                        $measurementSitephotos = MeasurementSitePhoto::where('project_id', $project->id)->where('measurement_id', $measurement->id)->get();
                        if (!blank($measurementSitephotos)) {
                            $m_photos = array();
                            foreach ($measurementSitephotos as $measurementPhoto) {
                                $files2 = array();
                                $files2['id'] = $measurementPhoto->id;
                                $files2['project_id'] = $measurementPhoto->project_id;
                                $files2['measurement_id'] = $measurementPhoto->measurement_id;
                                $files['add_work'] = 0;
                                $files2['sitePhoto'] = url('/') . '/public/sitephoto/' . $measurementPhoto->site_photo;
                                $files2['created_at'] = date('d/m/Y', strtotime($measurementPhoto->created_at));
                                array_push($m_photos, $files2);
                            }
                            $array1['measurement_sitephotos'] = $m_photos;
                        } else {
                            $array1['measurement_sitephotos'] = [];
                        }
                        array_push($array_push1, $array1);
                    }
                }
                if (!blank($submeasurements)) {
                    foreach ($submeasurements as $measurement) {
                        $array1 = array();
                        $array1['id'] = $measurement->id;
                        $array1['project_id'] = ($measurement->project_id != NULL) ? $measurement->project_id : "";
                        $array1['description'] = ($measurement->description != NULL) ? $measurement->description : "";
                        $array1['measurement_date'] = ($measurement->measurement_date != NULL) ? date('d/m/Y', strtotime($measurement->measurement_date)) : "";
                        $array1['add_work'] = 1;
                        $array1['created_at'] = ($measurement->created_at != NULL) ? date('d/m/Y', strtotime($measurement->created_at)) : "";
                        $submeasurementfiles = Measurementfile::where('measurement_id', $measurement->id)->get();
                        if (!blank($submeasurementfiles)) {
                            $m_files = array();
                            foreach ($submeasurementfiles as $measurementfile) {
                                $files = array();
                                $files['id'] = $measurementfile->id;
                                $files['measurement_id'] = $measurementfile->measurement_id;
                                $files['measurement'] = url('/') . '/public/measurementfile/' . $measurementfile->measurement;
                                $files['created_at'] = date('d/m/Y', strtotime($measurementfile->created_at));
                                $files['add_work'] = 1;
                                array_push($m_files, $files);
                            }
                            $array1['measurement_files'] = $m_files;
                        } else {
                            $array1['measurement_files'] = [];
                        }

                        $submeasurementSitephotos = MeasurementSitePhoto::where('measurement_id', $measurement->id)->get();
                        if (!blank($submeasurementSitephotos)) {
                            $m_photos = array();
                            foreach ($submeasurementSitephotos as $measurementPhoto) {
                                $files2 = array();
                                $files2['id'] = $measurementPhoto->id;
                                $files2['project_id'] = $measurementPhoto->project_id;
                                $files2['measurement_id'] = $measurementPhoto->measurement_id;
                                $files['add_work'] = 1;
                                $files2['sitePhoto'] = url('/') . '/public/sitephoto/' . $measurementPhoto->site_photo;
                                $files2['created_at'] = date('d/m/Y', strtotime($measurementPhoto->created_at));
                                array_push($m_photos, $files2);
                            }
                            $array1['measurement_sitephotos'] = $m_photos;
                        } else {
                            $array1['measurement_sitephotos'] = [];
                        }
                        array_push($array_push1, $array1);
                    }
                }
                $array['measurements'] = $array_push1;
                $quotations = Quotation::where('project_id', $project->id)->where('deleted_at', null)->get();
                $array_push2 = array();
                if (!blank($quotations)) {
                    foreach ($quotations as $quotation) {
                        $array2 = array();
                        $array2['id'] = $quotation->id;
                        $array2['project_id'] = $quotation->project_id;
                        $array2['quotation_date'] = ($quotation->quotation_date != NULL) ? date('d/m/Y', strtotime($quotation->quotation_date)) : "";
                        $array2['quotation_done'] = $quotation->quotation_done;
                        $array2['created_by'] = $quotation->created_by;
                        $array2['add_work'] = 0;
                        $array2['created_at'] = ($quotation->created_at != NULL) ? date('d/m/Y', strtotime($quotation->created_at)) : "";
                        $array2['quotation_final'] = 1;
                        $quotationfiles = Quotationfile::where('quotation_id', $quotation->id)->where('final', 1)->orderBy('id', 'desc')->get();
                        if (count($quotationfiles) == 0) {
                            $array2['quotation_final'] = 0;
                            $quotationfiles = Quotationfile::where('quotation_id', $quotation->id)->orderBy('id', 'desc')->get();
                        }
                        $q_files = array();
                        if (!blank($quotationfiles)) {
                            foreach ($quotationfiles as $files) {
                                $qfiles = array();
                                $qfiles['id'] = $files->id;
                                $qfiles['project_id'] = $files->project_id;
                                $qfiles['quotation_id'] = $files->quotation_id;
                                $qfiles['is_final'] = $files->final;
                                $qfiles['description'] = ($files->description != '') ? $files->description : "";
                                $qfiles['cost'] = $files->cost;
                                $qfiles['project_cost'] = $files->project_cost;
                                $qfiles['add_work'] = 0;
                                $qfiles['status'] = $files->status;
                                if ($files->status == 2) {
                                    if ($files->reject_reason == 1) {
                                        $reason = 'Delayed/Cool Of';
                                    } elseif ($files->reject_reason == 2) {
                                        $reason = 'Cancel';
                                    } elseif ($files->reject_reason == 3) {
                                        $reason = 'Addon';
                                    } else {
                                        $reason = '';
                                    }
                                    $qfiles['reject_reason'] = $reason;
                                    $qfiles['reject_note'] = ($files->reject_note != '') ? $files->reject_note : "";
                                }
                                $qfiles['created_at'] = date('d/m/Y', strtotime($files['created_at']));
                                array_push($q_files, $qfiles);
                            }
                        }
                        $array2['quotation_file'] = $q_files;
                        $quotation_uploads = QuotationUpload::where('quotation_id', $quotation->id)->get();
                        $q_uploads = array();
                        if (!blank($quotation_uploads)) {
                            foreach ($quotation_uploads as $uploads) {
                                $quploads = array();
                                $quploads['id'] = $uploads->id;
                                $quploads['quotation_id'] = $uploads->quotation_id;
                                $quploads['quotation_file_id'] = $uploads->quotation_file_id;
                                $quploads['project_id'] = $uploads->project_id;
                                $quploads['file'] = url('/') . '/public/quotationfile/' . $uploads->file;
                                $quploads['file_name'] = $uploads->file_name;
                                $quploads['add_work'] = 0;
                                $quploads['created_at'] = ($uploads->created_at != NULL) ? date('d/m/Y', strtotime($uploads->created_at)) : "";
                                array_push($q_uploads, $quploads);
                            }
                        }
                        $array2['quotation_uploads'] = $q_uploads;
                        array_push($array_push2, $array2);
                    }
                }
                if (!blank($subquotations)) {
                    foreach ($subquotations as $key => $subquotation) {
                        $array2 = array();
                        $array2['id'] = $subquotation->id;
                        $array2['project_id'] = $subquotation->project_id;
                        $array2['quotation_date'] = ($subquotation->quotation_date != NULL) ? date('d/m/Y', strtotime($subquotation->quotation_date)) : "";
                        $array2['quotation_done'] = $subquotation->quotation_done;
                        $array2['created_by'] = $subquotation->created_by;
                        $array2['add_work'] = 1;
                        $array2['created_at'] = ($subquotation->created_at != NULL) ? date('d/m/Y', strtotime($subquotation->created_at)) : "";
                        $subquotationfiles = Quotationfile::where('quotation_id', $subquotation->id)->orderBy('id', 'desc')->get();
                        $q_files = array();
                        if (!blank($subquotationfiles)) {
                            foreach ($subquotationfiles as $subfiles) {
                                $qfiles = array();
                                $qfiles['id'] = $subfiles->id;
                                $qfiles['project_id'] = $subfiles->project_id;
                                $qfiles['quotation_id'] = $subfiles->quotation_id;
                                $qfiles['is_final'] = $subfiles->final;
                                $qfiles['description'] = ($subfiles->description != '') ? $subfiles->description : "";
                                $qfiles['cost'] = $subfiles->cost;
                                $qfiles['project_cost'] = $subfiles->project_cost;
                                $qfiles['add_work'] = 1;
                                $qfiles['status'] = $subfiles->status;
                                if ($subfiles->status == 2) {
                                    if ($subfiles->reject_reason == 1) {
                                        $reason = 'Delayed/Cool Of';
                                    } elseif ($subfiles->reject_reason == 2) {
                                        $reason = 'Cancel';
                                    } elseif ($subfiles->reject_reason == 3) {
                                        $reason = 'Addon';
                                    } else {
                                        $reason = '';
                                    }
                                    $qfiles['reject_reason'] = $reason;
                                    $qfiles['reject_note'] = ($subfiles->reject_note != '') ? $subfiles->reject_note : "";
                                }
                                $qfiles['created_at'] = date('d/m/Y', strtotime($subfiles['created_at']));
                                array_push($q_files, $qfiles);
                            }
                        }
                        $array2['quotation_file'] = $q_files;
                        $subquotation_uploads = QuotationUpload::where('quotation_id', $subquotation->id)->get();
                        $q_uploads = array();
                        if (!blank($subquotation_uploads)) {
                            foreach ($subquotation_uploads as $subuploads) {
                                $quploads = array();
                                $quploads['id'] = $subuploads->id;
                                $quploads['quotation_id'] = $subuploads->quotation_id;
                                $quploads['quotation_file_id'] = $subuploads->quotation_file_id;
                                $quploads['project_id'] = $subuploads->project_id;
                                $quploads['file'] = url('/') . '/public/quotationfile/' . $subuploads->file;
                                $quploads['file_name'] = $subuploads->file_name;
                                $quploads['add_work'] = 1;
                                $quploads['created_at'] = ($subuploads->created_at != NULL) ? date('d/m/Y', strtotime($subuploads->created_at)) : "";
                                array_push($q_uploads, $quploads);
                            }
                        }
                        $array2['quotation_uploads'] = $q_uploads;
                        array_push($array_push2, $array2);
                    }
                }
                $array['quotations'] = $array_push2;
                $purchases = Purchase::where('project_id', $project->id)->get();
                $purchase_data = array();
                if (!blank($purchases)) {
                    foreach ($purchases as $purchaseItem) {
                        $purchaseDetails = array();
                        $purchaseDetails['id'] = $purchaseItem->id;
                        $purchaseDetails['project_id'] = $purchaseItem->project_id;
                        $purchaseDetails['file'] = url('/') . '/public/purchases/' . $purchaseItem->purchase;
                        $purchaseDetails['file_name'] = $purchaseItem->purchase;
                        $purchaseDetails['add_work'] = 0;
                        $purchaseDetails['created_at'] = date('d/m/Y', strtotime($purchaseItem->created_at));
                        array_push($purchase_data, $purchaseDetails);
                    }
                }
                if (!blank($subPurchase)) {
                    foreach ($subPurchase as $purchaseItem) {
                        $subPurchaseDetails = array();
                        $subPurchaseDetails['id'] = $purchaseItem->id;
                        $subPurchaseDetails['project_id'] = $purchaseItem->project_id;
                        $subPurchaseDetails['file'] = url('/') . '/public/purchases/' . $purchaseItem->purchase;
                        $subPurchaseDetails['file_name'] = $purchaseItem->purchase;
                        $subPurchaseDetails['add_work'] = 1;
                        $subPurchaseDetails['created_at'] = date('d/m/Y', strtotime($purchaseItem->created_at));
                        array_push($purchase_data, $subPurchaseDetails);
                    }
                }
                $array['purchases'] = $purchase_data;
                $material_received = array();
                if (!blank($project)) {
                    $purchaseDetails = array();
                    $purchaseDetails['project_id'] = $project->id;
                    $purchaseDetails['material_received_selection'] = $project->material_received_selection;
                    $purchaseDetails['material_received_date'] = $project->material_received_date;
                    $purchaseDetails['material_received_by'] = $project->material_received_by;
                    $purchaseDetails['material_received_number'] = $project->material_received_number;
                    $purchaseDetails['add_work'] = 0;
                    array_push($material_received, $purchaseDetails);
                }
                if (!blank($subPurchase)) {
                    foreach ($subProjectData as $subProject) {
                        $subProjectDetail = array();
                        $subProjectDetail['project_id'] = $project->id;
                        $subProjectDetail['material_received_selection'] = $subProject->material_received_selection;
                        $subProjectDetail['material_received_date'] = $subProject->material_received_date;
                        $subProjectDetail['material_received_by'] = $subProject->material_received_by;
                        $subProjectDetail['material_received_number'] = $subProject->material_received_number;
                        $subProjectDetail['add_work'] = 1;
                        array_push($material_received, $subProjectDetail);
                    }
                }
                $array['material_received'] = $material_received;

                $sitePhotos = Sitephotos::where('project_id', $project->id)->get();
                $siteData = array();
                if (!blank($sitePhotos)) {
                    foreach ($sitePhotos as $sitephoto) {
                        $sitephotoDetail = array();
                        $sitephotoDetail['id'] = $sitephoto->id;
                        $sitephotoDetail['project_id'] = $sitephoto->project_id;
                        $sitephotoDetail['site_photo'] = url('/') . '/public/sitephoto/' . $sitephoto->site_photo;
                        $sitephotoDetail['created_by'] = $sitephoto->created_by;
                        $sitephotoDetail['created_at'] = $sitephoto->created_at;
                        $sitephotoDetail['add_work'] = 0;
                        array_push($siteData, $sitephotoDetail);
                    }
                }
                if (!blank($subsitePhotos)) {
                    foreach ($subsitePhotos as $sitephoto) {
                        $sitephotoDetail = array();
                        $sitephotoDetail['id'] = $sitephoto->id;
                        $sitephotoDetail['project_id'] = $sitephoto->project_id;
                        $sitephotoDetail['site_photo'] = url('/') . 'public/sitephoto/' . $sitephoto->site_photo;
                        $sitephotoDetail['created_by'] = $sitephoto->created_by;
                        $sitephotoDetail['add_work'] = 1;
                        array_push($siteData, $sitephotoDetail);
                    }
                }
                // wokshop_process
                $array['sitePhotos'] = $siteData;
                $issueWork = AdditionalProjectDetail::where('project_id', $project->id)->where('status', 'issue')->get();
                $wokshop_process = [];

                $statusArray = [];
                $statusDataArray = [];

                // Main Project
                $statusDataArray['id'] = 1;
                $statusDataArray['add_work'] = 0;
                $cutting_status['cutting_selection'] = $project->cutting_selection;
                $cutting_status['add_work'] = 1;
                $cutting_status['cutting_date'] = ($project->cutting_date != null) ? date('d/m/Y H:i:s', strtotime($project->cutting_date)) : "";
                $statusDataArray['cutting_status'] = $cutting_status;

                $shutter_joinary = [];
                $shutter_joinary['shutter_selection'] = $project->shutter_selection;
                $shutter_joinary['add_work'] = 1;
                $shutter_joinary['shutter_date'] = ($project->shutter_date != null) ? date('d/m/Y H:i:s', strtotime($project->shutter_date)) : "";
                $statusDataArray['shutter_joinary_status'] = $shutter_joinary;

                $glassMeasurement = [];
                $glassMeasurement['glass_measurement'] = $project->glass_measurement;
                $glassMeasurement['add_work'] = 1;
                $glassMeasurement['glass_date'] = ($project->glass_date != null) ? date('d/m/Y H:i:s', strtotime($project->glass_date)) : "";
                $glassFile = GlassMeasurementFile::where('project_id', $project->id)->get();
                $glassFileDetail = [];
                foreach ($glassFile as $key => $file) {
                    $glassMeasurementFileDetail = array();
                    $glassMeasurementFileDetail['id'] = $file->id;
                    $glassMeasurementFileDetail['project_id'] = $file->project_id;
                    $glassMeasurementFileDetail['add_work'] = 1;
                    $glassMeasurementFileDetail['file'] = url('/') . 'public/glassmeasurementfiles/' . $file->file;
                    $glassMeasurementFileDetail['file_name'] = $file->file;
                    array_push($glassFileDetail, $glassMeasurementFileDetail);
                }
                $glassMeasurement['glass_measurement_file'] = $glassFileDetail;
                $statusDataArray['glass_measurement_status'] = $glassMeasurement;

                $glassReceived = [];
                $glassReceived['glass_receive'] = $project->glass_receive;
                $glassReceived['add_work'] = 1;
                $glassReceived['glass_receive_date'] = ($project->glass_receive_date != null) ? date('d/m/Y H:i:s', strtotime($project->glass_receive_date)) : "";
                $statusDataArray['glass_receive_status'] = $glassReceived;

                $fitting = [];
                $fitting['shutter_ready'] = $project->shutter_ready;
                $fitting['add_work'] = 1;
                $fitting['shutter_ready_date'] = ($project->shutter_ready_date != null) ? date('d/m/Y H:i:s', strtotime($project->shutter_ready_date)) : "";
                $statusDataArray['fitting_status'] = $fitting;

                $materialReceivedStatus = [];
                $materialReceivedStatus['material_received_selection'] = $project->material_received_selection;
                $materialReceivedStatus['material_received_by'] = $project->material_received_by;
                $materialReceivedStatus['add_work'] = 1;
                $materialReceivedStatus['material_received_date'] = ($project->material_received_date != null) ? date('d/m/Y H:i:s', strtotime($project->material_received_date)) : "";
                $addStatusArray['material_received_status'] = $materialReceivedStatus;

                $invoiceStatus = [];
                $invoiceStatus['invoice_status'] = $project->invoice_status;
                $invoiceStatus['add_work'] = 1;
                $invoiceStatus['invoice_date'] = ($project->invoice_date != null) ? date('d/m/Y H:i:s', strtotime($project->invoice_date)) : "";

                $invoce_docs = Invoicefile::where('project_id', $project->id)->where('add_work', 0)->get();
                $Ifiles = [];
                foreach ($invoce_docs as $doc) {
                    $invoiceFile = [];
                    $invoiceFile['id'] = $doc->id;
                    $invoiceFile['project_id'] = $doc->project_id;
                    $invoiceFile['add_work'] = 1;
                    $invoiceFile['invoice'] = url('/') . '/public/invoicefiles/' . $doc->invoice;
                    $invoiceFile['created_at'] = ($doc->created_at != null) ? date('d/m/Y', strtotime($doc->created_at)) : "";
                    array_push($Ifiles, $invoiceFile);
                }
                $invoiceStatus['invoice_documents'] = $Ifiles;
                $statusDataArray['invoicestatus'] = $invoiceStatus;

                $materialStatus = [];
                if ($project->material_delivered == 1 || $project->material_delivered == 0) {
                    $materialStatus['material_delivered'] = $project->material_delivered;
                    $materialStatus['delivered_by'] = $project->delivered_by ?? '';
                    $materialStatus['driver_number'] = $project->driver_number ?? '';
                    $materialStatus['deliver_date'] = ($project->deliver_date != null) ? date('d/m/Y H:i:s', strtotime($project->deliver_date)) : "";
                    $materialStatus['add_work'] = 1;
                    $statusDataArray['material_deliver_status'] = $materialStatus;
                } else {
                    $materialStatus['material_delivered'] = $project->material_delivered;
                    $partialDeliveries = PartialDeliverDetail::where('project_id', $project->id)->get();
                    $partialDeliveriesData = [];
                    foreach ($partialDeliveries as $deliver_by) {
                        $partialDeliveriesData[] = [
                            'id' => $deliver_by->id,
                            'delivered_by' => $deliver_by->partial_deliver_by ?? '',
                            'deliver_date' => ($deliver_by->partial_deliver_date != null) ? date('d/m/Y H:i:s', strtotime($deliver_by->partial_deliver_date)) : "",
                            'driver_number' => $deliver_by->driver_number,
                            'add_work' => 1,
                        ];
                    }
                    $statusDataArray['partial_deliveries'] = $partialDeliveriesData;
                    $statusDataArray['material_deliver_status'] = $materialStatus;
                }

                $statusArray[] = $statusDataArray;

                // Additional Projects
                $key1 = 1;
                foreach ($subProjectData as $add) {
                    $addStatusArray = [];
                    $addStatusArray['id'] = $key1 + 1;
                    $addStatusArray['add_work'] = 1;

                    $cutting_status = [];
                    $cutting_status['cutting_selection'] = $add->cutting_selection;
                    $cutting_status['add_work'] = 1;
                    $cutting_status['cutting_date'] = ($add->cutting_date != null) ? date('d/m/Y H:i:s', strtotime($add->cutting_date)) : "";
                    $addStatusArray['cutting_status'] = $cutting_status;

                    $shutter_joinary = [];
                    $shutter_joinary['shutter_selection'] = $add->shutter_selection;
                    $shutter_joinary['add_work'] = 1;
                    $shutter_joinary['shutter_date'] = ($add->shutter_date != null) ? date('d/m/Y H:i:s', strtotime($add->shutter_date)) : "";
                    $addStatusArray['shutter_joinary_status'] = $shutter_joinary;

                    $glassMeasurement = [];
                    $glassMeasurement['glass_measurement'] = $add->glass_measurement;
                    $glassMeasurement['add_work'] = 1;
                    $glassMeasurement['glass_date'] = ($add->glass_date != null) ? date('d/m/Y H:i:s', strtotime($add->glass_date)) : "";
                    $subGlassFile = GlassMeasurementFile::where('project_id', $add->id)->get();
                    $subGlassFileDetail = [];
                    foreach ($subGlassFile as $key => $file) {
                        $subGlassMeasurementFileDetail = array();
                        $subGlassMeasurementFileDetail['id'] = $file->id;
                        $subGlassMeasurementFileDetail['project_id'] = $file->project_id;
                        $subGlassMeasurementFileDetail['file'] = url('/') . 'public/glassmeasurementfiles/' . $file->file;
                        $subGlassMeasurementFileDetail['file_name'] = $file->file;
                        $subGlassMeasurementFileDetail['add_work'] = 1;
                        array_push($subGlassFileDetail, $subGlassMeasurementFileDetail);
                    }
                    $glassMeasurement['glass_measurement_file'] = $subGlassFileDetail;
                    $addStatusArray['glass_measurement_status'] = $glassMeasurement;

                    $glassReceived = [];
                    $glassReceived['glass_receive'] = $add->glass_receive;
                    $glassReceived['add_work'] = 1;
                    $glassReceived['glass_receive_date'] = ($add->glass_receive_date != null) ? date('d/m/Y H:i:s', strtotime($add->glass_receive_date)) : "";
                    $addStatusArray['glass_receive_status'] = $glassReceived;

                    $fitting = [];
                    $fitting['shutter_ready'] = $add->shutter_ready;
                    $fitting['add_work'] = 1;
                    $fitting['shutter_ready_date'] = ($add->shutter_ready_date != null) ? date('d/m/Y H:i:s', strtotime($add->shutter_ready_date)) : "";
                    $addStatusArray['fitting_status'] = $fitting;

                    $materialReceivedStatus = [];
                    $materialReceivedStatus['material_received_selection'] = $add->material_received_selection;
                    $materialReceivedStatus['material_received_by'] = $add->material_received_by;
                    $materialReceivedStatus['add_work'] = 1;
                    $materialReceivedStatus['material_received_date'] = ($add->material_received_date != null) ? date('d/m/Y H:i:s', strtotime($add->material_received_date)) : "";
                    $addStatusArray['material_received_status'] = $materialReceivedStatus;

                    $invoiceStatus = [];
                    $invoiceStatus['invoice_status'] = $add->invoice_status;
                    $invoiceStatus['add_work'] = 1;
                    $invoiceStatus['invoice_date'] = ($add->invoice_date != null) ? date('d/m/Y H:i:s', strtotime($add->invoice_date)) : "";

                    $invoce_docs = Invoicefile::where('project_id', $add->id)->where('add_work', 1)->get();
                    $Ifiles = [];
                    foreach ($invoce_docs as $doc) {
                        $invoiceFile = [];
                        $invoiceFile['id'] = $doc->id;
                        $invoiceFile['project_id'] = $doc->project_id;
                        $invoiceFile['add_work'] = 1;
                        $invoiceFile['invoice'] = url('/') . '/public/invoicefiles/' . $doc->invoice;
                        $invoiceFile['created_at'] = ($doc->created_at != null) ? date('d/m/Y', strtotime($doc->created_at)) : "";
                        array_push($Ifiles, $invoiceFile);
                    }
                    $invoiceStatus['invoice_documents'] = $Ifiles;
                    $addStatusArray['invoicestatus'] = $invoiceStatus;

                    $materialStatus = [];
                    if ($add->material_delivered == 1 || $add->material_delivered == 0) {
                        $materialStatus['material_delivered'] = $add->material_delivered;
                        $materialStatus['delivered_by'] = $add->delivered_by ?? '';
                        $materialStatus['driver_number'] = $add->driver_number ?? '';
                        $materialStatus['add_work'] = 1;
                        $materialStatus['deliver_date'] = ($add->deliver_date != null) ? date('d/m/Y H:i:s', strtotime($add->deliver_date)) : "";
                        $addStatusArray['material_deliver_status'] = $materialStatus;
                    } else {
                        $materialStatus['material_delivered'] = $add->material_delivered;
                        $partialDeliveries = PartialDeliverDetail::where('project_id', $add->id)->get();
                        $partialDeliveriesData = [];
                        foreach ($partialDeliveries as $deliver_by) {
                            $partialDeliveriesData[] = [
                                'id' => $deliver_by->id,
                                'delivered_by' => $deliver_by->partial_deliver_by ?? '',
                                'deliver_date' => ($deliver_by->partial_deliver_date != null) ? date('d/m/Y H:i:s', strtotime($deliver_by->partial_deliver_date)) : "",
                                'driver_number' => $deliver_by->driver_number,
                                'add_work' => 1,
                            ];
                        }
                        $addStatusArray['partial_deliveries'] = $partialDeliveriesData;
                        $addStatusArray['material_deliver_status'] = $materialStatus;
                    }
                    $statusArray[] = $addStatusArray;
                    $key1++;
                }
                foreach ($issueWork as $issue) {
                    $issueStatusArray = [];
                    $issueStatusArray['id'] = $key1 + 1;

                    $cutting_status = [];
                    $cutting_status['cutting_selection'] = $issue->cutting_selection;
                    $cutting_status['add_work'] = 2;
                    $cutting_status['cutting_date'] = ($issue->cutting_date != null) ? date('d/m/Y H:i:s', strtotime($issue->cutting_date)) : "";
                    $issueStatusArray['cutting_status'] = $cutting_status;

                    $shutter_joinary = [];
                    $shutter_joinary['shutter_selection'] = $issue->shutter_selection;
                    $shutter_joinary['add_work'] = 2;
                    $shutter_joinary['shutter_date'] = ($issue->shutter_date != null) ? date('d/m/Y H:i:s', strtotime($issue->shutter_date)) : "";
                    $issueStatusArray['shutter_joinary_status'] = $shutter_joinary;

                    $glassMeasurement = [];
                    $glassMeasurement['glass_measurement'] = $issue->glass_measurement;
                    $glassMeasurement['add_work'] = 2;
                    $glassMeasurement['glass_date'] = ($issue->glass_date != null) ? date('d/m/Y H:i:s', strtotime($issue->glass_date)) : "";
                    $issueStatusArray['glass_measurement_status'] = $glassMeasurement;

                    $glassReceived = [];
                    $glassReceived['glass_receive'] = $issue->glass_receive;
                    $glassReceived['add_work'] = 2;
                    $glassReceived['glass_receive_date'] = ($issue->glass_receive_date != null) ? date('d/m/Y H:i:s', strtotime($issue->glass_receive_date)) : "";
                    $issueStatusArray['glass_receive_status'] = $glassReceived;

                    $fitting = [];
                    $fitting['shutter_ready'] = $issue->shutter_ready;
                    $fitting['add_work'] = 2;
                    $fitting['shutter_ready_date'] = ($issue->shutter_ready_date != null) ? date('d/m/Y H:i:s', strtotime($issue->shutter_ready_date)) : "";
                    $issueStatusArray['fitting_status'] = $fitting;

                    $materialReceivedStatus = [];
                    $materialReceivedStatus['material_received_selection'] = $issue->material_received_selection;
                    $materialReceivedStatus['material_received_by'] = $issue->material_received_by;
                    $materialReceivedStatus['add_work'] = 2;
                    $materialReceivedStatus['material_received_date'] = ($issue->material_received_date != null) ? date('d/m/Y H:i:s', strtotime($issue->material_received_date)) : "";
                    $issueStatusArray['material_received_status'] = $materialReceivedStatus;

                    $invoiceStatus = [];
                    $invoiceStatus['invoice_status'] = $issue->invoice_status;
                    $invoiceStatus['add_work'] = 2;
                    $invoiceStatus['invoice_date'] = ($issue->invoice_date != null) ? date('d/m/Y H:i:s', strtotime($issue->invoice_date)) : "";

                    $invoce_docs = Invoicefile::where('project_id', $add->id)->where('add_work', 2)->get();
                    $Ifiles = [];
                    foreach ($invoce_docs as $doc) {
                        $invoiceFile = [];
                        $invoiceFile['id'] = $doc->id;
                        $invoiceFile['project_id'] = $doc->project_id;
                        $invoiceFile['add_work'] = $doc->add_work;
                        $invoiceFile['invoice'] = url('/') . '/public/invoicefiles/' . $doc->invoice;
                        $invoiceFile['created_at'] = ($doc->created_at != null) ? date('d/m/Y', strtotime($doc->created_at)) : "";
                        array_push($Ifiles, $invoiceFile);
                    }
                    $invoiceStatus['invoice_documents'] = $Ifiles;
                    $issueStatusArray['invoicestatus'] = $invoiceStatus;

                    $materialStatus = [];
                    if ($issue->material_delivered == 1 || $issue->material_delivered == 0) {
                        $materialStatus['material_delivered'] = $issue->material_delivered;
                        $materialStatus['delivered_by'] = $issue->delivered_by ?? '';
                        $materialStatus['driver_number'] = $issue->driver_number ?? '';
                        $materialStatus['deliver_date'] = ($issue->deliver_date != null) ? date('d/m/Y H:i:s', strtotime($issue->deliver_date)) : "";
                        $issueStatusArray['material_deliver_status'] = $materialStatus;
                    } else {
                        $materialStatus['material_delivered'] = $issue->material_delivered;
                        $partialDeliveries = PartialDeliverDetail::where('project_id', $project->id)->get();
                        $partialDeliveriesData = [];
                        foreach ($partialDeliveries as $deliver_by) {
                            $partialDeliveriesData[] = [
                                'id' => $deliver_by->id,
                                'delivered_by' => $deliver_by->partial_deliver_by ?? '',
                                'deliver_date' => ($deliver_by->partial_deliver_date != null) ? date('d/m/Y H:i:s', strtotime($deliver_by->partial_deliver_date)) : "",
                                'driver_number' => $deliver_by->driver_number,
                                'add_work' => $deliver_by->add_work,
                            ];
                        }
                        $addStatusArray['partial_deliveries'] = $partialDeliveriesData;
                        $addStatusArray['material_deliver_status'] = $materialStatus;
                    }
                    $statusArray[] = $addStatusArray;
                    $key1++;
                }


                $array['wokshop_process'] = $statusArray;

                $costArray = array();
                $costData = array();
                $costArray['id'] = 1;
                $costArray['quotation_cost'] = $project->quotation_cost;
                $costArray['project_cost'] = $project->project_cost;
                $costArray['transport_cost'] = $project->transport_cost;
                $costArray['labor_cost'] = $project->laber_cost;
                $costArray['margin_cost'] = $project->margin_cost;
                $costArray['add_work'] = 0;
                $costData[] = $costArray;
                foreach ($subProjectData as $add) {
                    $cost_status = [];
                    $cost_status['id'] = $key1 + 1;
                    $cost_status['cutting_selection'] = $add->quotation_cost;
                    $cost_status['project_cost'] = $add->project_cost;
                    $cost_status['transport_cost'] = $add->transport_cost;
                    $cost_status['labor_cost'] = $add->laber_cost;
                    $cost_status['margin_cost'] = $add->margin_cost;
                    $cost_status['add_work'] = 1;
                    array_push($costData, $cost_status);
                    $key1++;
                }
                foreach ($issueWork as $issue) {
                    $issue_status = [];
                    $issue_status['id'] = $key1 + 1;
                    $issue_status['cutting_selection'] = $issue->quotation_cost;
                    $issue_status['project_cost'] = $issue->project_cost;
                    $issue_status['transport_cost'] = $issue->transport_cost;
                    $issue_status['labor_cost'] = $issue->laber_cost;
                    $issue_status['margin_cost'] = $issue->margin_cost;
                    $issue_status['add_work'] = 2;
                    array_push($costData, $issue_status);
                    $key1++;
                }
                $array['purchase_cost'] = $costData;
                $array['created_at'] = ($project->created_at != NULL) ? date('d/m/Y', strtotime($project->created_at)) : "";
                $array['status'] = ($project->status != NULL) ? (int) $project->status : 0;
                return response()->json([
                    'status' => 1,
                    'Project' => $array
                ], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Project Not Found.'], 404);
            }
        }
    }

    public function projectDone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'project_selection' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $selection = $request->project_selection;
            $project = Project::where('id', $request->project_id)->first();
            if (!blank($project)) {
                if ($selection == 1) {
                    $where['project_id'] = $project->id;
                    $project->add_work = 0;
                    $update['end_date'] = $request->end_date;
                    AdditionalProjectDetail::where($where)->update($update);

                    $project->end_date = $request->end_date;
                    $project->status = 2;
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

                    try {
                        $projectId = $project->id;
                        $customer = Customer::where('id', $project->customer_id)->first();
                        $customerAddress = $customer->address . " " . $customer->cityDetail->name . " " . $customer->stateDetail->name . " " . $customer->zipcode;
                        $mobileNumber = $customer->phone;
                        $route = route('feedbackForm', $project->id);
                        $templateid = '1407171593897710905';
                        $setting = Setting::first();
                        if ($setting->wa_message_sent == 1) {
                            $message = "Dear " . $customer->name . ", your " . $project->project_generated_id . " has been completed. Kindly review us in this " . $route . "and address is ". $customerAddress.". Shree Ganesh Aluminum.";
                            $isSent = SmsHelper::sendSmsWithImage($mobileNumber, $message,$projectId, true);
                            
                            $measurementList = MeasurementTask::where('project_id', $request->project_id)->get();
                            foreach ($measurementList as $measurement) {
                                $userDetail = User::where('id', $measurement->user_id)->first();
                                $mobile = $userDetail->phone;
                                $isSent = SmsHelper::sendSmsWithImage($mobile, $message,$projectId, false);
                            }
                            $filltingUser = User::where('role', '5')->get();
                            foreach ($filltingUser as $fitting) {
                                $mobile = $fitting->phone;
                                $isSent = SmsHelper::sendSmsWithImage($mobile, $message,$projectId, false);
                            }
                            
                            $quatationUser = User::where('role', '6')->get();
                            foreach ($quatationUser as $quatation) {
                                $mobile = $quatation->phone;
                                $isSent = SmsHelper::sendSmsWithImage($mobile, $message,$projectId, false);
                            }
                        } else {
                            $message = "Dear " . $customer->name . ", your " . $project->project_generated_id . " has been completed. Kindly review us in this " . $route . ". Shree Ganesh Aluminum.";
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
                        }
                    } catch (Exception $e) {
                        echo 'Message:' . $e->getMessage();
                    }
                    $log = new Log();
                    $log->user_id = $user->name;
                    $log->module = 'Project';
                    $log->log = 'Project Completed.';
                    $log->save();
                    return response()->json(['status' => 1, 'message' => 'Project has been completed.'], 200);
                } else {
                    $project->status = 1;
                    $project->save();
                    return response()->json(['status' => 1, 'message' => 'Project has not been completed.'], 200);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
    public function resumeWork(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'resume_work_option' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $projectDetail = Project::where('id', $request->project_id)->first();
            if ($request->resume_work_option == "2") {
                $update['step'] = 4;
                $update['status'] = 1;
                $update['add_work'] = 2;
                $where['id'] = $request->project_id;
                $update['total_issue_work'] = isset($projectDetail) ? $projectDetail->total_issue_work + 1 : 1;
                Project::where($where)->update($update);
                $additionalProject['status'] = "issue";
                $additionalProject['project_id'] = $request->project_id;
                AdditionalProjectDetail::create($additionalProject);
                return response()->json(['status' => 1, 'message' => 'Project status updated succesfully.'], 200);
            }
        }
    }

    public function addCloneProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $project = Project::where('id', $request->project_id)->first();
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
                    'status' => 1,
                    'step' => 1,
                    'type' => 1,
                ];
                $createProject = Project::create($create);
                if ($createProject) {
                    return response()->json(['data' => $createProject, 'status' => 1, 'message' => 'project Add successfully.'], 200);
                }
                return response()->json(['data' => null, 'status' => 0, 'error' => 'Something wen to wrong.'], 500);
            }
            return response()->json(['data' => null, 'status' => 0, 'error' => 'Project Not Found.'], 500);
        }
    }

    public function convertLead(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required|exists:projects,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $project = Project::where('id', $request->project_id)->first();
            if ($project) {
                $project->lead_status = 1;
                $project->type = 0;
                $project->status = 0;
                $update = $project->save();
                if ($update) {
                    $log = new Log();
                    $log->user_id = $user->name;
                    $log->module = 'Project';
                    $log->log = 'Project has been converted to Lead.';
                    $log->save();
                    return response()->json(['status' => 1, 'message' => 'project Converted into lead successfully.'], 200);
                }
                return response()->json(['status' => 0, 'error' => 'Something wen to wrong.'], 500);
            }
            return response()->json(['data' => null, 'status' => 0, 'error' => 'Project Not Found.'], 500);
        }
    }
}
