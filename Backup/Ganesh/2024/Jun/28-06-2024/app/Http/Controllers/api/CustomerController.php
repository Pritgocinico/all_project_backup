<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Log;
use App\Models\Setting;
use App\Models\City;
use App\Models\TaskManagement;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\MeasurementSitePhoto;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\QuotationUpload;
use Illuminate\Support\Facades\Validator;
use App\Models\AccessToken;
use Nnjeim\World\WorldHelper;
use App\Http\Helpers\SmsHelper;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }
    public function Customers(Request $request)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $customers = Customer::orderBy('id', 'Desc')->get();
        $array_push = array();
        if (!blank($customers)) {
            foreach ($customers as $customer) {
                $array = array();
                $array['id']            = $customer->id;
                $array['name']          = ($customer->name != NULL) ? $customer->name : "";
                $array['email']         = ($customer->email != NULL) ? $customer->email : "";
                $array['phone']         = ($customer->phone != NULL) ? $customer->phone : "";
                $array['address']       = ($customer->address != NULL) ? $customer->address : "";
                $array['city']          = ($customer->city != NULL) ? $customer->city : "";
                $array['state']         = ($customer->state != NULL) ? $customer->state : "";
                $array['zipcode']       = ($customer->zipcode != NULL) ? $customer->zipcode : "";
                $array['status']        = ($customer->status != NULL) ? (int)$customer->status : 0;
                $array['created_at']    = ($customer->created_at != NULL) ? date('d/m/Y', strtotime($customer->created_at)) : "";
                array_push($array_push, $array);
            }
            return response()->json([
                'status' => 1,
                'customers' => $array_push
            ], 200);
        } else {
            return response()->json(['status' => 0, 'error' => 'Customers Not Found.'], 404);
        }
    }
    public function addCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('customers')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
                    ->ignore($request->customer_id)
            ],
            'user_id'             => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $projectCount = DB::table('customers')->get()->last() ? DB::table('customers')->get()->last()->id + 1 : 1;
            $projectIdPadding = str_pad($projectCount, 5, '0', STR_PAD_LEFT);
            $customerId = 'SGA_CUS' . '_' . $projectIdPadding;
            $customer = new Customer();
            $customer->name         = $request->name;
            $customer->email        = $request->email;
            $customer->phone        = $request->phone;
            $customer->address      = $request->address;
            $customer->address      = $request->address;
            $customer->customer_id         = $customerId;
            $customer->role         = 9;
            $customer->state        = $request->state;
            $customer->state        = $request->state;
            $customer->zipcode      = $request->zipcode;
            $customer->save();

            if ($customer) {
                $role = new RoleUser;
                $role->user_id = $customer->id;
                $role->role_id = 9;
                $ins = $role->save();
                // $mobileNumber = $customer->phone;
                // $message = "Welcome " . $request->name . " to Shree Ganesh Aluminum. Your details has been captured in our system for your upcoming project.";
                // $templateid = '1407171593756486272';
                // $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $mobileNumber = $request->phone;
                $password = $request->password;
                $message = "Your user id of Customer has been created in the CRM of Shree Ganesh Aluminum. Id: " . $user->customer_id . " , Password: " . $password;
                $templateid = '1407171593745579639';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);

                $user = User::where('id', $request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Customers';
                $log->log       = 'Customers (' . $request->name . ') Created.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Customers added successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
    public function updateCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('customers')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })->ignore($request->customer_id)
            ],
            'user_id'             => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $customer = Customer::where('id', $request->customer_id)->first();
            $customer->name         = $request->name;
            $customer->email        = $request->email;
            $customer->phone        = $request->phone;
            $customer->address      = $request->address;
            $customer->city         = $request->city;
            $customer->state        = $request->state;
            $customer->zipcode      = $request->zipcode;
            $customer->save();

            if ($customer) {
                $user = User::where('id', $request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Customers';
                $log->log       = 'Customers (' . $request->name . ') Updated.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Customers updated successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
    public function deletecustomer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $customer = Customer::find($id);
            if (!blank($customer)) {
                $query =  Project::where('customer_id', $id)->count();
                if ($query > 0) {
                    return response()->json(["status" => 0, "error" => "Customer not deleted."]);
                } else {
                    $customer->delete();
                    $user = User::where('id', $request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Customers';
                    $log->log       = 'Customers (' . $request->name . ') deleted.';
                    $log->save();
                    return response()->json(["status" => 1, "message" => 'Customer Deleted Successfully.']);
                }
            } else {
                return response()->json(["status" => 0, "error" => "Customer not found."]);
            }
        }
    }

    public function listState(WorldHelper $world, Request $request)
    {
        try {
            $this->world = $world;
            $state_action = $this->world->states([
                'filters' => [
                    'country_id' => 102,
                ],
            ]);
            if ($state_action->success) {
                return json_decode($state_action->data);
            } else {
                return response()->json(["status" => 0, "error" => "States not found."]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "error" => $e->getMessage()]);
        }
    }

    public function listCity(Request $request)
    {
        try {
            $cities = City::where('country_id', 102)->get();
            if (!blank($cities)) {
                return json_decode($cities);
            } else {
                return response()->json(["status" => 0, "error" => "Cities not found."]);
            }
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }
    public function getCustomerProjectList(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = Customer::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            if (!blank($user)) {
                $projects =  Project::where('customer_id', $user->id)->get();
                if ($projects->count() > 0) {
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
                                $measurementfiles = Measurementfile::where('project_id', $project->id)->where('measurement_id', $measurement->id)->get();
                                if (!blank($measurementfiles)) {
                                    $m_files = array();
                                    foreach ($measurementfiles as $measurementfile) {
                                        $files = array();
                                        $files['id'] = $measurementfile->id;
                                        $files['add_work'] = $measurementfile->add_work;
                                        $files['measurement_id'] = $measurementfile->measurement_id;
                                        $files['measurement'] = url('/') . '/public/measurementfile/' . $measurementfile->measurement;
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
                        array_push($array_push, $array);
                    }
                    return response()->json(["status" => 1, "data" => $array_push]);
                } else {
                    return response()->json(["status" => 0, "error" => "Projects not found."]);
                }
            } else {
                return response()->json(["status" => 0, "error" => "Customer not found."]);
            }
        }
    }
    public function getProjectPendingTaskCustomer(Request $request)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = Customer::where('id', $accessToken->user_id)->first();
        $projectIdArray = Project::where('customer_id', $user->id)->pluck('id')->toArray();
        $tasks = TaskManagement::whereIn('project_id', $projectIdArray)->where('task_status', 'Pending')->get();
        if (!blank($tasks)) {
            $array_push = array();
            foreach ($tasks as $task) {
                if (!blank($task->project)) {
                    $array = array();
                    $array['id'] = $task->id;
                    $array['task'] = $task->task;
                    $project_data = array();
                    $project = Project::where('id', $task->project_id)->first();
                    if (!blank($project)) {
                        $project_data['id'] = $project->id;
                        $project_data['project_generated_id'] = $project->project_generated_id;
                        $project_data['customer_id'] = $project->customer_id;
                        $project_data['phone_number'] = $project->phone_number;
                        $project_data['architecture_name'] = $project->architecture_name;
                        $project_data['architecture_number'] = $project->architecture_number;
                        $project_data['supervisor_name'] = $project->supervisor_name;
                        $project_data['supervisor_number'] = $project->supervisor_number;
                        $project_data['email'] = $project->email;
                        $project_data['address'] = $project->address;
                        $project_data['city'] = $project->city;
                        $project_data['statename'] = $project->statename;
                        $project_data['zipcode'] = $project->zipcode;
                        $project_data['description'] = $project->description;
                        $project_data['start_date'] = ($project->start_date != NULL) ? date('d/m/Y', strtotime($project->start_date)) : "";
                        $project_data['measurement_date'] = ($project->measurement_date != NULL) ? date('d/m/Y', strtotime($project->measurement_date)) : "";
                        $project_data['reference_name'] = $project->reference_name;
                        $project_data['reference_phone'] = $project->reference_phone;
                        $project_data['status'] = $project->status;
                        $project_data['lead_no'] = $project->lead_no;
                        $project_data['lead_status'] = $project->lead_status;
                        $project_data['quotation_cost'] = $project->quotation_cost;
                        $project_data['project_cost'] = $project->project_cost;
                        $project_data['material_selection'] = $project->material_selection;
                        $project_data['reject_reason'] = $project->reject_reason;
                        $project_data['created_at'] = ($project->created_at != NULL) ? date('d/m/Y', strtotime($project->created_at)) : "";
                    }
                    $array['project_detail'] = $project_data;
                    $array['task_type'] = $task->task_type;
                    $array['task_status'] = $task->task_status;
                    $array['created_at'] = ($task->created_at != NULL) ? date('d/m/Y', strtotime($task->created_at)) : "";

                    array_push($array_push, $array);
                }
            }
            return response()->json([
                'status' => 1,
                'task' => $array_push
            ], 200);
        } else {
            return response()->json(['status' => 0, 'error' => 'Task Not Found.'], 404);
        }
    }
}
