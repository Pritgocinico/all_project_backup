<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nnjeim\World\WorldHelper;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use App\Models\Customer;
use App\Models\Log;
use App\Models\Sitephotos;
use App\Models\MeasurementSitePhoto;
use App\Models\MeasurementTask;
use Nnjeim\World\World;
use Carbon\Carbon;
use App\Models\Measurement;
use App\Models\Measurementfile;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use App\Http\Helpers\SmsHelper;

class MeasurementController extends Controller
{
    protected $world;
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }
    public function viewMeasurementByProject(Request $request, $id)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $measurements = Measurement::where('project_id', $id)->orderBy('id', 'desc')->get();
        if (!blank($measurements)) {
            $array_push = array();
            foreach ($measurements as $measurement) {
                $array = array();
                $array['id']                                = $measurement->id;
                $array['project_id']                        = ($measurement->project_id != NULL) ? $measurement->project_id : "";
                $array['description']                       = ($measurement->description != NULL) ? $measurement->description : "";
                $array['measurement_date']                  = ($measurement->measurement_date != NULL) ? date('d/m/Y', strtotime($measurement->measurement_date)) : "";
                $array['created_at']                        = ($measurement->created_at != NULL) ? date('d/m/Y', strtotime($measurement->created_at)) : "";
                $measurementfiles = Measurementfile::where('project_id', $id)->where('measurement_id', $measurement->id)->get();
                if (!blank($measurementfiles)) {
                    $m_files = array();
                    foreach ($measurementfiles as $measurementfile) {
                        $files = array();
                        $files['id']                = $measurementfile->id;
                        $files['measurement_id']    = $measurementfile->measurement_id;
                        $files['measurement']       = url('/') . '/public/measurementfile/' . $measurementfile->measurement;
                        $files['created_at']        = ($measurementfile->created_at != NULL) ? date('d/m/Y', strtotime($measurementfile->created_at)) : "";
                        array_push($m_files, $files);
                    }
                    $array['measurement_files'] = $m_files;
                } else {
                    $array['measurement_files'] = [];
                }


                $measurementPhotos = MeasurementSitePhoto::where('project_id', $id)->where('measurement_id', $measurement->id)->get();
                if (!blank($measurementPhotos)) {
                    $m_photos = array();
                    foreach ($measurementPhotos as $sitePhoto) {
                        $files2 = array();
                        $files2['id']                = $sitePhoto->id;
                        $files2['project_id']        = $sitePhoto->project_id;
                        $files2['measurement_id']    = $sitePhoto->measurement_id;
                        $files2['site_photo']        = url('/') . '/public/sitephoto/' . $sitePhoto->site_photo;
                        $files2['created_at']        = date('d/m/Y', strtotime($sitePhoto->created_at));
                        array_push($m_photos, $files2);
                    }
                    $array['measurement_sitephotos'] = $m_photos;
                } else {
                    $array['measurement_sitephotos'] = [];
                }
                array_push($array_push, $array);
            }
            return response()->json([
                'status' => 1,
                'project_id' => $id,
                'measurement' => $array_push
            ], 200);
        } else {
            return response()->json(['status' => 0, 'error' => 'Measurements Not Found.'], 404);
        }
    }

    public function addMeasurement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'project_id' => 'required',
            'measurementfile' => 'required'
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
                $measData = Measurement::where('project_id', $request->project_id)->count();
                if ($measData == 0) {
                    $project = Project::where('id', $request->project_id)->first();
                    $project->step = 1;
                    $project->save();
                }
                $measurements = new Measurement();
                $measurements->project_id = $request->project_id;
                $measurements->description = $request->description;
                $measurements->add_work = $project->add_work;
                $measurements->measurement_date = Carbon::today()->format('Y/m/d');
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
                            $measurements->add_work = $project->add_work;
                            $newMeasurement->project_id = $request->project_id;
                            $newMeasurement->measurement_date = Carbon::today()->format('Y/m/d');
                            $newMeasurement->save();
                            $m_file->measurement_id = $newMeasurement->id;
                        }
                        $m_file->project_id = $request->project_id;
                        $m_file->measurement = $file_name;
                        $m_file->add_work = $project->add_work;
                        $m_file->save();
                    }
                }

                if ($request->hasFile('sitephotos')) {
                    $allowedfileExtension = ['jpg', 'png', 'jpeg', 'pdf'];
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
                            $siteMeasurement->add_work = $request->add_work;
                            $siteMeasurement->measurement_date = Carbon::today()->format('Y/m/d');
                            $siteMeasurement->save();
                            $m_sitephoto->measurement_id = $siteMeasurement->id;
                        }
                        $m_sitephoto->project_id = $request->project_id;
                        $m_sitephoto->add_work = $project->add_work;
                        $m_sitephoto->site_photo = $file_name;
                        $m_sitephoto->save();
                    }
                }

                try {
                    $customer = Customer::with('cityDetail','cityDetail')->where('id', $project->customer_id)->first();
                    $mobileNumber = $customer->phone;
                    $customer_name = $customer->name;
                    $customerDetail = "Name:-".$customer->name . " and Phone Number:-". $customer->phone . " and Address:-" . $customer->address . ",".$customer->cityDetail->name.",".$customer->stateDetail->name."-".$customer->pincode;
                    $message = "Dear " . $customerDetail . ", your measurement for the " . $project->project_generated_id . " has been taken on " . date('d-m-Y h:i:s A') . " Shree Ganesh Aluminum";
                    $templateid = '1407171593796762936';
                    $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, true);

                    $measurementList = MeasurementTask::where('project_id', $request->project_id)->get();
                    foreach ($measurementList as $measurement) {
                        $userDetail  = User::where('id', $measurement->user_id)->first();
                        $mobile = $userDetail->phone;
                        $isSent = SmsHelper::sendSms($mobile, $message, $templateid, false);
                    }
                } catch (Exception $e) {
                    echo 'Messageff:' . $e->getMessage();
                }
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Project - measurement';
                $log->log       = 'Measurement added.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Measurement has been added successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function updateMeasurement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'project_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $measurement = Measurement::where('id', $request->id)->first();
            $project = Project::where('id', $measurement->project_id)->first();
            if (!blank($measurement)) {
                $measurement->description = $request->description;
                $measurement->project_id = $request->project_id;
                $measurement->measurement_date = Carbon::today()->format('Y/m/d');
                $measurement->save();
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
                        $m_file->project_id = $request->project_id;
                        $m_file->measurement_id  = $request->id;
                        $m_file->measurement = $file_name;
                        $m_file->save();
                    }
                }
                if ($request->hasFile('sitephotos')) {
                    $allowedfileExtension = ['jpg', 'png', 'jpeg', 'png'];
                    $files = $request->file('sitephotos');

                    foreach ($files as $file) {
                        $filename = $file->getClientOriginalName();
                        $destinationPath = 'public/sitephoto/';
                        $extension = $file->getClientOriginalExtension();
                        $file_name = $filename;
                        $file->move($destinationPath, $file_name);
                        $m_sitephoto = new MeasurementSitePhoto();
                        $m_sitephoto->project_id = $request->project_id;
                        $m_sitephoto->measurement_id  = $request->id;
                        $m_sitephoto->site_photo = $file_name;
                        $m_sitephoto->save();
                    }
                }
                try {
                    //http Url to send sms.
                    $url = "http://trans.jaldisms.com/smsstatuswithid.aspx";
                    $fields = array(
                        'mobile' => env('SMS_ID'),
                        'pass' => env('SMS_PWD'),
                        'senderid' => env('SMS_SENDER_ID'),
                        'to' => $project->phone_number,
                        'templateid' => '1407171213851378763',
                        'msg' => "તમારું માપ " . $request->project_id . " અપ્રુવ થઇ ગયું છે. - શ્રી ગણેશ એલ્યૂમીનિયમ",
                        'msgtype' => 'uc',
                    );
                    //url-ify the data for the POST
                    $fields_string = '';
                    foreach ($fields as $key => $value) {
                        $fields_string .=
                            $key . '=' . $value . '&';
                    }
                    rtrim($fields_string, '&');
                    //open connection
                    $ch = curl_init();
                    //set the url, number of POST vars, POST data
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, count($fields));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    //execute post
                    $result = curl_exec($ch);
                    //close connection
                    curl_close($ch);
                } catch (Exception $e) {
                    echo 'Message:' . $e->getMessage();
                }
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Project - measurement';
                $log->log       = 'Measurement updated.';
                $log->save();
                return response()->json(['status' => 1, 'message' => 'Measurement has been updated successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }

    public function deleteMeasurement(Request $request, $id)
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;

        $measurement = Measurement::find($id);
        if (!blank($measurement)) {
            $measurement->delete();
            $log = new Log();
            $log->user_id   = $user->name;
            $log->module    = 'Project - measurement';
            $log->log       = 'Measurement deleted.';
            $log->save();
            return response()->json(["status" => 1, "message" => 'Measurement has been Deleted Successfully.']);
        } else {
            return response()->json(["status" => 0, "error" => "Measurement not found."]);
        }
    }

    public function deleteMeasurementDoc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'measurement_id' => 'required',
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
            $measurement_file = Measurementfile::find($request->doc_id);
            if (!blank($measurement_file)) {
                $measurement_file->delete();
                $measurement_id = $measurement_file->measurement_id;

                $measurementFiles = Measurementfile::where('measurement_id', $measurement_id)->get();

                $measurementpics = MeasurementSitePhoto::where('measurement_id', $measurement_id)->get();

                if (count($measurementFiles) === 0 && count($measurementpics) === 0) {
                    $measurement = Measurement::where('id', $measurement_id)->delete();
                }
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Project - measurement';
                $log->log       = 'Measurement document deleted.';
                $log->save();
                return response()->json(["status" => 1, "message" => 'Measurement document has been Deleted Successfully.']);
            } else {
                return response()->json(["status" => 0, "error" => "Measurement document not found."]);
            }
        }
    }

    public function deleteSitephoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'measurement_id' => 'required',
            'project_id' => 'required',
            'sitephoto_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $measurementpic = MeasurementSitePhoto::find($request->sitephoto_id);
            if (!blank($measurementpic)) {
                $measurementpic->delete();
                $measurement_id = $measurementpic->measurement_id;

                $measurementFiles = Measurementfile::where('measurement_id', $measurement_id)->get();

                $measurementpics = MeasurementSitePhoto::where('measurement_id', $measurement_id)->get();

                if (count($measurementFiles) === 0 && count($measurementpics) === 0) {
                    $measurement = Measurement::where('id', $measurement_id)->delete();
                }
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Project - measurement';
                $log->log       = 'Measurement sitrphoto deleted.';
                $log->save();
                return response()->json(["status" => 1, "message" => 'Measurement sitephoto has been Deleted Successfully.']);
            } else {
                return response()->json(["status" => 0, "error" => "Measurement sitephoto not found."]);
            }
        }
    }

    public function measurementSitephotos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'sitephotos' => 'required'
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
                if ($request->hasFile('sitephotos')) {
                    $allowedfileExtension = ['jpg', 'png', 'jpeg', 'pdf'];
                    $files = $request->file('sitephotos');

                    foreach ($files as $file) {
                        $filename = $file->getClientOriginalName();
                        $destinationPath = 'public/sitephoto/';
                        $extension = $file->getClientOriginalExtension();
                        $file_name = $filename;
                        $file->move($destinationPath, $file_name);

                        $f_sitephoto = new Sitephotos();
                        $f_sitephoto->project_id = $request->project_id;
                        $f_sitephoto->site_photo = $file_name;
                        $f_sitephoto->created_by = $user->id;
                        $f_sitephoto->save();
                    }
                    return response()->json(['status' => 1, 'message' => 'Sitephoto uploaded successfully.'], 200);
                }
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
}
