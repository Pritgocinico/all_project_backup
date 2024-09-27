<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\Log;
use App\Models\RoleUser;
use App\Models\Project;
use App\Models\Customer;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\MeasurementSitePhoto;
use App\Models\QuotationUpload;
use App\Models\AdditionalProjectDetail;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\Purchase;
use App\Models\Role;
use App\Models\AccessToken;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\SmsHelper;
use Hash;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }
    public function listPurchaseUser(Request $request)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $quotations = User::where('role', 8)->whereNot('id', 1)->get();
        $array_push = array();
        if (!blank($quotations)) {
            foreach ($quotations as $quotation) {
                $array = array();
                $array['id'] = $quotation->id;
                $array['name'] = ($quotation->name != NULL) ? $quotation->name : "";
                $array['email'] = ($quotation->email != NULL) ? $quotation->email : "";
                $array['phone'] = ($quotation->phone != NULL) ? $quotation->phone : "";
                $array['status'] = ($quotation->status != NULL) ? (int) $quotation->status : 0;
                $array['created_at'] = ($quotation->created_at != NULL) ? date('d/m/Y', strtotime($quotation->created_at)) : "";
                array_push($array_push, $array);
            }
            return response()->json([
                'status' => 1,
                'purchase_users' => $array_push
            ], 200);
        } else {
            return response()->json(['status' => 0, 'error' => 'Purchase Users Not Found.'], 404);
        }
    }
    public function addPurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users,phone,NULL,id,deleted_at,NULL',
            'password' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $q_user = new User();
            $q_user->name = $request->name;
            $q_user->email = $request->email;
            $q_user->phone = $request->phone;
            $q_user->password = Hash::make($request->password);
            $q_user->role = 8;
            $q_user->status = 1;
            $q_user->save();

            $role = new RoleUser;
            $role->user_id = $q_user->id;
            $role->role_id = 8;
            $ins = $role->save();

            if ($q_user) {
                $role = Role::where('id', 8)->first();
                $mobileNumber = $q_user->phone;
                $message = "Your user id of " . $role->name . " has been created in the CRM of Shree Ganesh Aluminum. Id: " . $mobileNumber . ", Password: " . $request->password . "";
                $templateid = '1407171593756486272';
                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                $user = User::where('id', $request->user_id)->first();
                $log = new Log();
                $log->user_id = $user->name;
                $log->module = 'Purchase User';
                $log->log = 'Purchase User (' . $request->name . ') Created.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Purchase User added successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
    public function updatedPurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $request->purchase_user_id,
            'user_id' => 'required',
            'purchase_user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $q_user = User::where('id', $request->purchase_user_id)->first();
            if (!blank($q_user)) {
                $q_user->name = $request->name;
                $q_user->email = $request->email;
                $q_user->phone = $request->phone;
                if ($request->has('password') && !blank($request->password) && $request->password != '') {
                    $q_user->password = Hash::make($request->password);
                }
                if ($request->has('status')) {
                    $q_user->status = $request->status;
                }
                $q_user->save();
                if ($q_user) {
                    $user = User::where('id', $request->user_id)->first();
                    $log = new Log();
                    $log->user_id = $user->name;
                    $log->module = 'Purchase User';
                    $log->log = 'Purchase User (' . $request->name . ') Updated.';
                    $log->save();
                    return response()->json(['status' => 1, 'message' => 'Purchase User updated successfully.'], 200);
                } else {
                    return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Purchase User Not Found.'], 404);
            }
        }
    }

    public function deletedPurchaseUser(Request $request, $id)
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
            $quotationUser = User::find($id);
            if (!blank($quotationUser)) {
                $quotationUser->delete();
                return response()->json(["status" => 1, "message" => 'Purchase User Deleted Successfully.']);
            } else {
                return response()->json(["status" => 0, "error" => "Purchase User not found."]);
            }
        }
    }

    public function notUploadPurchaseProjectList(Request $request)
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
            $projects = Project::with('purchaseFile')->doesntHave('purchaseFile')->where('type', 1)->where('step', 3)->orderBy('id', 'desc')->get();
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

    public function materialReceiveStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'material_received_selection' => 'required',
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
                if ($request->material_received_selection == 1) {
                    $project->material_received_selection = $request->material_received_selection;
                    $project->material_received_by = $request->material_received_by;
                    $project->material_received_number = $request->material_received_number;
                    $project->material_received_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->material_received_date)->format('Y/m/d H:i:s');
                    $project->sub_step = "Material (4.2)";
                    $project->save();
                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {
                        $additionalProject->material_received_selection = $request->material_received_selection;
                        $additionalProject->material_received_by = $request->material_received_by;
                        $additionalProject->material_received_number = $request->material_received_number;
                        $additionalProject->material_received_date = Carbon::createFromFormat('d/m/Y H:i:s', $request->material_received_date)->format('Y/m/d H:i:s');
                        $additionalProject->sub_step = "Material (4.2)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Material Received.'], 200);
                } else {
                    $project->material_received_selection = $request->material_received_selection;
                    $project->sub_step = "Material (4.2)";
                    $project->save();
                    $additionalProject = AdditionalProjectDetail::where('project_id', $request->project_id)->where('status', 'issue')->orderBy('id', 'desc')->first();
                    if (isset($additionalProject) && $project->add_work == 2) {

                        $additionalProject->material_received_selection = $request->material_received_selection;
                        $additionalProject->material_received_date = null;
                        $additionalProject->sub_step = "Material (5.2)";
                        $additionalProject->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Material not Received.'], 200);
                }


            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }

        }
    }
}
