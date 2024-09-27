<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Nnjeim\World\WorldHelper;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use App\Models\Customer;
use App\Models\Log;
use Nnjeim\World\World;
use Carbon\Carbon;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\MeasurementSitePhoto;
use App\Models\QuotationUpload;
use App\Models\Quotationfile;
use App\Models\Quotation;
use App\Models\Purchase;
use App\Models\TaskManagement;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use App\Http\Helpers\SmsHelper;

class LeadController extends Controller
{
    protected $world;
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }
    public function leads(Request $request)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $leads = Project::with('customer')->orderBy('id', 'DESC')->where('type', 0)->get();
        $array_push = array();
        if (!blank($leads)) {
            foreach ($leads as $lead) {
                $customer = Customer::where('id', $lead->customer_id)->first();
                $array = array();
                $array['id']                                = $lead->id;
                $array['lead_no']                           = ($lead->lead_no != NULL) ? $lead->lead_no : "";
                $array['customer_id']                       = ($lead->customer_id != NULL) ? $lead->customer_id : "";
                $customer = Customer::where('id', $lead->customer_id)->first();
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
                $array['phone_number']                      = ($lead->phone_number != NULL) ? $lead->phone_number : "";
                $array['email']                             = ($lead->email != NULL) ? $lead->email : "";
                $array['address']                           = ($lead->address != NULL) ? $lead->address : "";
                $array['city']                              = ($lead->cityname != NULL) ? $lead->cityname : "";
                $array['state']                             = ($lead->statename != NULL) ? $lead->statename : "";
                $array['zipcode']                           = ($lead->zipcode != NULL) ? $lead->zipcode : "";
                $array['description']                       = ($lead->description != NULL) ? $lead->description : "";
                $array['reference_name']                    = ($lead->reference_name != NULL) ? $lead->reference_name : "";
                $array['reference_phone']                   = ($lead->reference_phone != NULL) ? $lead->reference_phone : "";
                $array['description']                       = ($lead->description != NULL) ? $lead->description : "";
                $array['estimated_measurement_date']        = ($lead->measurement_date != NULL) ? date('d/m/Y', strtotime($lead->measurement_date)) : "";
                $array['lead_status']                       = ($lead->lead_status != NULL) ? (int)$lead->lead_status : 0;
                $array['type']                              = ($lead->type != NULL) ? (int)$lead->type : 0;
                $array['step']                              = ($lead->step != NULL) ? (int)$lead->step : 0;
                $array['quotation_cost']                    = ($lead->quotation_cost != NULL) ? (int)$lead->quotation_cost : 0;
                $array['project_cost']                      = ($lead->project_cost != NULL) ? (int)$lead->project_cost : 0;
                $array['material_selection']                = ($lead->material_selection != NULL) ? (int)$lead->material_selection : 0;
                $measurements = Measurement::where('project_id', $lead->id)->get();
                $array_push1 = array();
                if (!blank($measurements)) {
                    foreach ($measurements as $measurement) {
                        $array1 = array();
                        $array1['id']                                = $measurement->id;
                        $array1['project_id']                        = ($measurement->project_id != NULL) ? $measurement->project_id : "";
                        $array1['description']                       = ($measurement->description != NULL) ? $measurement->description : "";
                        $array1['measurement_date']                  = ($measurement->measurement_date != NULL) ? date('d/m/Y', strtotime($measurement->measurement_date)) : "";
                        $array1['created_at']                        = ($measurement->created_at != NULL) ? date('d/m/Y', strtotime($measurement->created_at)) : "";
                        $measurementfiles = Measurementfile::where('project_id', $lead->id)->where('measurement_id', $measurement->id)->get();
                        if (!blank($measurementfiles)) {
                            $m_files = array();
                            foreach ($measurementfiles as $measurementfile) {
                                $files = array();
                                $files['id']                = $measurementfile->id;
                                $files['measurement_id']    = $measurementfile->measurement_id;
                                $files['measurement']       = url('/') . '/public/measurementfile/' . $measurementfile->measurement;
                                $files['created_at']        = date('d/m/Y', strtotime($measurementfile->created_at));
                                array_push($m_files, $files);
                            }
                            $array1['measurement_files'] = $m_files;
                        } else {
                            $array1['measurement_files'] = [];
                        }

                        $measurementSitephotos = MeasurementSitePhoto::where('project_id', $lead->id)->where('measurement_id', $measurement->id)->get();
                        if (!blank($measurementSitephotos)) {
                            $m_photos = array();
                            foreach ($measurementSitephotos as $measurementPhoto) {
                                $files2 = array();
                                $files2['id'] = $measurementPhoto->id;
                                $files2['project_id'] = $measurementPhoto->project_id;
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
                $array['measurements']                      = $array_push1;

                $quotations = Quotation::where('project_id', $lead->id)->where('deleted_at', null)->get();
                $array_push2 = array();
                if (!blank($quotations)) {
                    foreach ($quotations as $quotation) {
                        $array2 = array();
                        $array2['id']             = $quotation->id;
                        $array2['project_id']     = $quotation->project_id;
                        $array2['quotation_date'] = ($quotation->quotation_date != NULL) ? date('d/m/Y', strtotime($quotation->quotation_date)) : "";
                        $array2['quotation_done'] = $quotation->quotation_done;
                        $array2['created_by']     = $quotation->created_by;
                        $array2['created_at']     = ($quotation->created_at != NULL) ? date('d/m/Y', strtotime($quotation->created_at)) : "";
                        $quotationfiles = Quotationfile::where('quotation_id', $quotation->id)->orderBy('id', 'desc')->get();
                        $q_files = array();
                        if (!blank($quotationfiles)) {
                            foreach ($quotationfiles as $files) {
                                $qfiles = array();
                                $qfiles['id']              = $files->id;
                                $qfiles['project_id']      = $files->project_id;
                                $qfiles['quotation_id']    = $files->quotation_id;
                                $qfiles['is_final']        = $files->final;
                                $qfiles['description']     = ($files->description != '') ? $files->description : "";
                                $qfiles['cost']            = $files->cost;
                                $qfiles['project_cost']    = $files->project_cost;
                                $qfiles['status']          = $files->status;
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
                                    $qfiles['reject_reason']   = $reason;
                                    $qfiles['reject_note']     = ($files->reject_note != '') ? $files->reject_note : "";
                                }
                                $qfiles['created_at']      = date('d/m/Y', strtotime($files['created_at']));
                                array_push($q_files, $qfiles);
                            }
                        }
                        $array2['quotation_file']     = $q_files;
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
                                $quploads['created_at'] = ($uploads->created_at != NULL) ? date('d/m/Y', strtotime($uploads->created_at)) : "";
                                array_push($q_uploads, $quploads);
                            }
                        }
                        $array2['quotation_uploads']     = $q_uploads;
                        array_push($array_push2, $array2);
                    }
                }
                $array['quotations']                        = $array_push2;
                $purchases = Purchase::where('project_id', $lead->id)->get();
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
                $array['purchases']                         = $purchase_data;
                $array['created_at']                        = ($lead->created_at != NULL) ? date('d/m/Y', strtotime($lead->created_at)) : "";
                $array['status']                            = ($lead->status != NULL) ? (int)$lead->status : 0;
                array_push($array_push, $array);
            }
            return response()->json([
                'status' => 1,
                'leads' => $array_push
            ], 200);
        } else {
            return response()->json(['status' => 0, 'error' => 'Leads Not Found.'], 404);
        }
    }
    public function addLead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'       => 'required|not_in:0|exists:customers,id',
            'measurement_date'  => 'required',
            'phone_number'      => 'required',
            'user_id'           => 'required',
            'city'           => 'required',
            'address'           => 'required',
            'state'           => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $currentMonthYear = date('Ym');
            $leadCount = Project::whereRaw('DATE_FORMAT(created_at, "%Y%m") = ?', [$currentMonthYear])->count();
            $leadCount++;
            $leadIdPadding = str_pad($leadCount, 5, '0', STR_PAD_LEFT);
            $leadNo = 'SGA_LD' . '_' . $leadIdPadding;
            // Check if the lead number already exists
            while (Project::where('lead_no', $leadNo)->exists()) {
                // If it exists, increment the count and regenerate the lead number
                $leadCount++;
                $leadIdPadding = str_pad($leadCount, 5, '0', STR_PAD_LEFT);
                $leadNo = 'SGA_LD' . '_' . $leadIdPadding;
            }
            $date = Carbon::createFromFormat('d/m/Y', $request->measurement_date)->format('Y/m/d');
            $leads = $request->all();
            $leads = new Project();
            $leads->lead_no             = $leadNo;
            $leads->reference_name      = $request->reference_name;
            $leads->reference_phone     = $request->reference_number;
            $leads->customer_id         = $request->customer_id;
            $leads->phone_number        = $request->phone_number;
            $leads->email               = $request->email;
            $leads->address             = $request->address;
            $leads->cityname            = $request->city;
            $leads->statename            = $request->state;
            $leads->zipcode             = $request->zipcode;
            $leads->description         = $request->description;
            $leads->measurement_date    = $date;
            $leads->lead_status         = 1;
            $leads->save();

            if ($leads) {
                try {
                    $setting = Setting::first();
                    $customer = Customer::where('id', $request->customer_id)->first();
                    $mobileNumber = $leads->phone_number;
                    $message = "Dear " . $customer->name . ", your lead " . $leads->lead_no . " has been added to our system. - Shree Ganesh Aluminum";
                    if($setting->wa_message_sent == 1){
                        $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message,true);
                    } else {
                        $templateid = '1407171593766914336';
                        $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);
                    }
                } catch (Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                }
                $user = User::where('id', $request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Leads';
                $log->log       = 'Leads (' . $request->name . ') Created.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Leads added successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
    public function updateLead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'       => 'required|not_in:0',
            'measurement_date'  => 'required',
            'phone_number'      => 'required',
            'user_id'           => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $currentMonthYear = date('Ym');
            $date = Carbon::createFromFormat('d/m/Y', $request->measurement_date)->format('Y/m/d');
            $leads = Project::where('id', $request->lead_id)->first();
            if (!blank($leads)) {
                $leads->reference_name      = $request->reference_name;
                $leads->reference_phone     = $request->reference_number;
                $leads->customer_id         = $request->customer_id;
                $leads->phone_number        = $request->phone_number;
                $leads->email               = $request->email;
                $leads->address             = $request->address;
                $leads->cityname            = $request->city;
                $leads->statename           = $request->state;
                $leads->zipcode             = $request->zipcode;
                $leads->description         = $request->description;
                $leads->measurement_date    = $date;
                $leads->lead_status         = 1;
                $leads->save();

                if ($leads) {
                    $user = User::where('id', $request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Leads';
                    $log->log       = 'Lead Updated.';
                    $log->save();
                    return response()->json(['status' => 1, 'message' => 'Leads updated successfully.'], 200);
                } else {
                    return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Lead Not Found.'], 404);
            }
        }
    }
    public function deleteLead(Request $request, $id)
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
            $lead = Project::find($id);
            if (!blank($lead)) {
                $lead_tasks = TaskManagement::where('project_id', $id)->where('deleted_at', null)->count();
                if ($lead_tasks > 0) {
                    return response()->json(["status" => 0, "error" => "Cannot delete project lead please delete project lead related tasks first."]);
                } else {
                    $lead->delete();
                    $user = User::where('id', $request->user_id)->first();
                    $log = new Log();
                    $log->user_id   = $user->name;
                    $log->module    = 'Leads';
                    $log->log       = 'Leads (' . $request->name . ') deleted.';
                    $log->save();
                    return response()->json(["status" => 1, "message" => 'Lead Deleted Successfully.']);
                }
            } else {
                return response()->json(["status" => 0, "error" => "Lead not found."]);
            }
        }
    }

    public function viewLead(Request $request, $id)
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
            $lead = Project::where('id', $id)->first();
            if (!blank($lead)) {
                $customer = Customer::where('id', $lead->customer_id)->first();
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
                $array = array();
                $array['id']                                = $lead->id;
                $array['lead_no']                           = ($lead->lead_no != NULL) ? $lead->lead_no : "";
                $array['customer_id']                       = ($lead->customer_id != NULL) ? $lead->customer_id : "";
                $array['phone_number']                      = ($lead->phone_number != NULL) ? $lead->phone_number : "";
                $array['email']                             = ($lead->email != NULL) ? $lead->email : "";
                $array['address']                           = ($lead->address != NULL) ? $lead->address : "";
                $array['city']                              = ($lead->cityname != NULL) ? $lead->cityname : "";
                $array['state']                             = ($lead->statename != NULL) ? $lead->statename : "";
                $array['zipcode']                           = ($lead->zipcode != NULL) ? $lead->zipcode : "";
                $array['description']                       = ($lead->description != NULL) ? $lead->description : "";
                $array['reference_name']                    = ($lead->reference_name != NULL) ? $lead->reference_name : "";
                $array['reference_phone']                   = ($lead->reference_phone != NULL) ? $lead->reference_phone : "";
                $array['description']                       = ($lead->description != NULL) ? $lead->description : "";
                $array['estimated_measurement_date']        = ($lead->measurement_date != NULL) ? date('d/m/Y', strtotime($lead->measurement_date)) : "";
                $array['lead_status']                       = ($lead->lead_status != NULL) ? (int)$lead->lead_status : 0;
                $array['type']                              = ($lead->type != NULL) ? (int)$lead->type : 0;
                $array['step']                              = ($lead->step != NULL) ? (int)$lead->step : 0;
                $array['quotation_cost']                    = ($lead->quotation_cost != NULL) ? (int)$lead->quotation_cost : 0;
                $array['project_cost']                      = ($lead->project_cost != NULL) ? (int)$lead->project_cost : 0;
                $array['material_selection']                = ($lead->material_selection != NULL) ? (int)$lead->material_selection : 0;
                $array['customer_details'] = $array_push0;
                $measurements = Measurement::where('project_id', $lead->id)->get();
                $array_push1 = array();
                if (!blank($measurements)) {
                    foreach ($measurements as $measurement) {
                        $array1 = array();
                        $array1['id']                                = $measurement->id;
                        $array1['project_id']                        = ($measurement->project_id != NULL) ? $measurement->project_id : "";
                        $array1['description']                       = ($measurement->description != NULL) ? $measurement->description : "";
                        $array1['measurement_date']                  = ($measurement->measurement_date != NULL) ? date('d/m/Y', strtotime($measurement->measurement_date)) : "";
                        $array1['created_at']                        = ($measurement->created_at != NULL) ? date('d/m/Y', strtotime($measurement->created_at)) : "";
                        $measurementfiles = Measurementfile::where('project_id', $lead->id)->where('measurement_id', $measurement->id)->get();
                        if (!blank($measurementfiles)) {
                            $m_files = array();
                            foreach ($measurementfiles as $measurementfile) {
                                $files = array();
                                $files['id']                = $measurementfile->id;
                                $files['measurement_id']    = $measurementfile->measurement_id;
                                $files['measurement']       = url('/') . '/public/measurementfile/' . $measurementfile->measurement;
                                $files['created_at']        = date('d/m/Y', strtotime($measurementfile->created_at));
                                array_push($m_files, $files);
                            }
                            $array1['measurement_files'] = $m_files;
                        } else {
                            $array1['measurement_files'] = [];
                        }

                        $measurementSitephotos = MeasurementSitePhoto::where('project_id', $lead->id)->where('measurement_id', $measurement->id)->get();
                        if (!blank($measurementSitephotos)) {
                            $m_photos = array();
                            foreach ($measurementSitephotos as $measurementPhoto) {
                                $files2 = array();
                                $files2['id'] = $measurementPhoto->id;
                                $files2['project_id'] = $measurementPhoto->project_id;
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
                $array['measurements']                      = $array_push1;

                $quotations = Quotation::where('project_id', $lead->id)->where('deleted_at', null)->get();
                $array_push2 = array();
                if (!blank($quotations)) {
                    foreach ($quotations as $quotation) {
                        $array2 = array();
                        $array2['id']             = $quotation->id;
                        $array2['project_id']     = $quotation->project_id;
                        $array2['quotation_date'] = ($quotation->quotation_date != NULL) ? date('d/m/Y', strtotime($quotation->quotation_date)) : "";
                        $array2['quotation_done'] = $quotation->quotation_done;
                        $array2['created_by']     = ($quotation->created_by != NULL) ? date('d/m/Y', strtotime($quotation->created_by)) : "";
                        $array2['created_at']     = ($quotation->created_at != NULL) ? date('d/m/Y', strtotime($quotation->created_at)) : "";
                        $quotationfiles = Quotationfile::where('quotation_id', $quotation->id)->orderBy('id', 'desc')->get();
                        $q_files = array();
                        if (!blank($quotationfiles)) {
                            foreach ($quotationfiles as $files) {
                                $qfiles = array();
                                $qfiles['id']              = $files->id;
                                $qfiles['project_id']      = $files->project_id;
                                $qfiles['quotation_id']    = $files->quotation_id;
                                $qfiles['is_final']        = $files->final;
                                $qfiles['description']     = ($files->description != '') ? $files->description : "";
                                $qfiles['cost']            = $files->cost;
                                $qfiles['project_cost']    = $files->project_cost;
                                $qfiles['status']          = $files->status;
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
                                    $qfiles['reject_reason']   = $reason;
                                    $qfiles['reject_note']     = ($files->reject_note != '') ? $files->reject_note : "";
                                }
                                $qfiles['created_at']      = date('d/m/Y', strtotime($files['created_at']));
                                array_push($q_files, $qfiles);
                            }
                        }
                        $array2['quotation_file']     = $q_files;

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
                                $quploads['created_at'] = ($uploads->created_at != NULL) ? date('d/m/Y', strtotime($uploads->created_at)) : "";
                                array_push($q_uploads, $quploads);
                            }
                        }
                        $array2['quotation_uploads']     = $q_uploads;
                        array_push($array_push2, $array2);
                    }
                }
                $array['quotations']                        = $array_push2;

                $purchases = Purchase::where('project_id', $lead->id)->get();
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
                $array['purchases']                         = $purchase_data;
                $array['created_at']                        = ($lead->created_at != NULL) ? date('d/m/Y', strtotime($lead->created_at)) : "";
                $array['status']                            = ($lead->status != NULL) ? (int)$lead->status : 0;
                return response()->json([
                    'status' => 1,
                    'lead' => $array
                ], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Lead Not Found.'], 404);
            }
        }
    }
}
