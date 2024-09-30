<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\QuotationUpload;
use App\Models\MeasurementTask;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use App\Http\Helpers\SmsHelper;

class QuotationController extends Controller
{

    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }

    public function getQuotation(Request $request, $id)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $quotations = Quotation::where('id', $id)->orderBy('id', 'desc')->get();
        if (!blank($quotations)) {
            $type = 0;
            if ($request->add_type == 1) {
                $type = 1;
            }
            $array_push = array();
            foreach ($quotations as $quotation) {
                $array = array();
                $array['id']                                = $quotation->id;
                $array['project_id']                        = ($quotation->project_id != NULL) ? $quotation->project_id : "";
                $array['quotation_date']                    = ($quotation->quotation_date != NULL) ? date('d/m/Y', strtotime($quotation->quotation_date)) : "";
                $array['quotation_done']                    = ($quotation->quotation_done != NULL) ? $quotation->quotation_done : "";
                $array['created_at']                        = ($quotation->created_at != NULL) ? date('d/m/Y', strtotime($quotation->created_at)) : "";

                $q_file = array();
                $quotationfile = Quotationfile::where('quotation_id', $id)->first();
                if (!blank($quotationfile)) {
                    $q_file['id'] = $quotationfile->id;
                    $q_file['project_id'] = ($quotationfile->project_id != NULL) ? $quotationfile->project_id : "";
                    $q_file['quotation_id'] = ($quotationfile->quotation_id != NULL) ? $quotationfile->quotation_id : "";
                    $q_file['description'] = ($quotationfile->description != NULL) ? $quotationfile->description : "";
                }
                $array['quotationfile'] = $q_file;

                $q_uploads = array();
                $quotationuploads =  QuotationUpload::where('quotation_id', $id)->get();
                $uploads = array();
                if (!blank($quotationuploads)) {
                    foreach ($quotationuploads as $quotationupload) {
                        $files = array();
                        $files['id'] = $quotationupload->id;
                        $files['project_id'] = $quotationupload->project_id;
                        $files['quotation_id'] = $quotationupload->quotation_id;
                        $files['add_work'] = $quotationupload->type;
                        $files['quotation'] = url('/') . '/public/quotationfile/' . $quotationupload->file_name;
                        array_push($uploads, $files);
                    }
                }

                $array['quotationupload'] = $uploads;
                array_push($array_push, $array);
            }
            return response()->json([
                'status' => 1,
                'quotation' => $array_push
            ], 200);
        } else {
            return response()->json(['status' => 0, 'error' => 'Quotation Not Found.'], 404);
        }
    }

    public function addQuotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'quotationfile' => 'required',
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
                $project = Project::where('id', $request->project_id)->first();
                $project->step = 2;
                $project->save();
                $quotations = new Quotation();
                $quotations->project_id     = $request->project_id;
                $quotations->quotation_date = Carbon::now()->format('Y/m/d');
                $quotations->created_by     = $request->user_id;
                $quotations->add_work = $project->add_work;
                $quotations->save();

                $q_file = new Quotationfile();
                $q_file->project_id     = $request->project_id;
                $q_file->quotation_id   = $quotations->id;
                $q_file->created_by     = $request->user_id;
                $q_file->description    = $request->description;
                $q_file->add_work = $project->add_work;
                $q_file->final          = $request->is_quotation_final;
                $q_file->save();

                if ($request->hasFile('quotationfile')) {
                    $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx'];
                    $files = $request->file('quotationfile');

                    foreach ($files as $file) {
                        $filename = $file->getClientOriginalName();
                        $destinationPath = 'public/quotationfile/';
                        $extension = date('dmYHis') . "." . $file->getClientOriginalExtension();
                        $file_name = $filename;
                        $file->move($destinationPath, $file_name);

                        $quotation_upload = new QuotationUpload();
                        $quotation_upload->quotation_id         = $quotations->id;
                        $quotation_upload->project_id           = $request->project_id;
                        $quotation_upload->quotation_file_id    = $q_file->id;
                        $quotation_upload->file                 = $extension;
                        $quotation_upload->add_work = $project->add_work;
                        $quotation_upload->file_name            = $file_name;
                        $quotation_upload->created_by           = $request->user_id;
                        $quotation_upload->save();
                    }
                }

                try {
                    $customer = Customer::where('id', $project->customer_id)->first();
                    $customerAddress = $userDetail->address . " " . $userDetail->cityDetail->name . " " . $userDetail->stateDetail->name . " " . $userDetail->zipcode;
                    $mobileNumber = $customer->phone;
                    $route = route('projectView', $project->id);
                    $templateid = '1407171593880088737';
                    $setting = Setting::first();
                    if ($setting->is_sms == 1) {
                        $message = "Dear " . $customer->name . ", your quotation has been uploaded in our system for the " . $project->project_generated_id . ". To check, use the following " . $route . " and address is ". $customerAddress.". Shree Ganesh Aluminum";
                        $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, true);
                        
                        $measurementList = MeasurementTask::where('project_id', $request->project_id)->get();
                        foreach ($measurementList as $measurement) {
                            $userDetail  = User::where('id', $measurement->user_id)->first();
                            $mobile = $userDetail->phone;
                            $isSent = SmsHelper::sendSmsWithTemplate($mobile, $message, false);
                        }
                        $fittingUser = User::where('role', '5')->get();
                        foreach ($fittingUser as $fitting) {
                            $mobile = $fitting->phone;
                            $isSent = SmsHelper::sendSmsWithTemplate($mobile, $message, false);
                        }
                        
                        $quotationUser = User::where('role', '6')->get();
                        foreach ($quotationUser as $quotation) {
                            $mobile = $quotation->phone;
                            $isSent = SmsHelper::sendSmsWithTemplate($mobile, $message, false);
                        }
                    } else {
                        $message = "Dear " . $customer->name . ", your quotation has been uploaded in our system for the " . $project->project_generated_id . ". To check, use the following " . $route . ". Shree Ganesh Aluminum";
                        $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);

                        $measurementList = MeasurementTask::where('project_id', $request->project_id)->get();
                        foreach ($measurementList as $measurement) {
                            $userDetail  = User::where('id', $measurement->user_id)->first();
                            $mobile = $userDetail->phone;
                            $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                        }
                        $fittingUser = User::where('role', '5')->get();
                        foreach ($fittingUser as $fitting) {
                            $mobile = $fitting->phone;
                            $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                        }

                        $quotationUser = User::where('role', '6')->get();
                        foreach ($quotationUser as $quatation) {
                            $mobile = $quatation->phone;
                            $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                        }
                    }
                } catch (Exception $e) {
                    echo 'Message:' . $e->getMessage();
                }
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Project - quotation';
                $log->log       = 'Quotation Added.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Quotation has been added successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function updateQuotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'quotation_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $quotation = Quotation::find($request->quotation_id);
            $q_file = Quotationfile::where('quotation_id', $request->quotation_id)->first();
            if (!blank($quotation)) {
                $quotation = Quotation::find($request->quotation_id);
                $quotation->project_id     = $request->project_id;
                $quotation->quotation_date = Carbon::now()->format('Y/m/d');
                $quotation->save();

                $q_file = Quotationfile::where('quotation_id', $request->quotation_id)->first();
                $q_file->project_id     = $request->project_id;
                $q_file->created_by     = $request->user_id;
                $q_file->description    = $request->description;
                $q_file->final          = $request->is_quotation_final;
                $q_file->save();

                if ($request->hasFile('quotationfile')) {
                    $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx'];
                    $files = $request->file('quotationfile');

                    foreach ($files as $file) {
                        $filename = $file->getClientOriginalName();
                        $destinationPath = 'public/quotationfile/';
                        $extension = date('dmYHis') . "." . $file->getClientOriginalExtension();
                        $file_name = $filename;
                        $file->move($destinationPath, $file_name);

                        $quotation_upload = new QuotationUpload();
                        $quotation_upload->quotation_id         = $request->quotation_id;
                        $quotation_upload->quotation_file_id    = $q_file->id;
                        $quotation_upload->project_id           = $request->project_id;
                        $quotation_upload->file                 = $extension;
                        $quotation_upload->file_name            = $file_name;
                        $quotation_upload->created_by           = $request->user_id;
                        $quotation_upload->save();
                    }
                }
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Project - quotation';
                $log->log       = 'Quotation Updated.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Quotation has been updated successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function deleteQuotation(Request $request, $id)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $quotation = Quotation::find($id);
        $quotation_file = Quotationfile::where('quotation_id', $id)->first();
        $quotation_upload = QuotationUpload::where('quotation_id', $id)->first();
        if (!blank($quotation)) {
            $quotation->delete();
            $quotation_file->delete();
            $quotation_upload->delete();
            $log = new Log();
            $log->user_id   = $user->name;
            $log->module    = 'Quotation';
            $log->log       = 'Quotation Deleted.';
            $log->save();
            return response()->json(["status" => 1, "message" => 'Quotation has been Deleted Successfully.']);
        } else {
            return response()->json(["status" => 0, "error" => "Quotation not found."]);
        }
    }

    public function deleteQuotationDoc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quotation_id' => 'required',
            'project_id' => 'required',
            'doc_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $quotation_file = QuotationUpload::find($request->doc_id);
            if (!blank($quotation_file)) {
                $quotation_file->delete();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Project - quotation';
                $log->log       = 'Quotation Document deleted.';
                $log->save();
                return response()->json(["status" => 1, "message" => 'Quotation document has been Deleted Successfully.']);
            } else {
                return response()->json(["status" => 0, "error" => "Quotation document not found."]);
            }
        }
    }

    public function rejectQuotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'quotationfile_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $quotationFile = Quotationfile::where('id', $request->quotationfile_id)->first();
            if (!blank($quotationFile)) {
                $quotationFile = Quotationfile::where('id', $request->quotationfile_id)->first();
                $quotationFile->status         = 2;
                $quotationFile->reject_reason  = $request->reject_reason;
                $quotationFile->reject_note    = $request->reject_note;
                $quotationFile->save();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Project - quotation';
                $log->log       = 'Quotation Rejected.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Quotation has been rejected successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
}
