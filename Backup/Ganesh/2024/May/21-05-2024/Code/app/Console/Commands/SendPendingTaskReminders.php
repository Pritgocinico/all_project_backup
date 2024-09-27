<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskManagement;
use App\Models\User;
use App\Models\MeasurementTask;
use App\Models\Project;
use App\Http\Helpers\SmsHelper;

class SendPendingTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:pending-task-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to users with pending tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $usersWithPendingTasks
        $pendingTasks = TaskManagement::where('task_status', 'pending')->get();

        foreach($pendingTasks as $pTask){

                $projectDetail = Project::where('id', $pTask->project_id)->get();
            // For Measurement
            if($pTask->task_type == "measurement"){
                $measurementtaskUsers = MeasurementTask::where('project_id', $pTask->project_id)->get();
                foreach($measurementtaskUsers as $key=>$measurementUsers){
                    $measurement_user = User::where('id', $measurementUsers->user_id)->where('deleted_at', NULL)->first();
                    try{
                        $sentAdmin =false;
                        if($status == 0){
                            $sentAdmin =true;
                        }
                        $mobileNumber = $measurement_user->phone;
                        $message = "Dear ".$measurement_user->name.", your task is pending for the measurement of ".$projectDetail->project_generated_id.". Shree Ganesh Aluminum";
                        $templateid = '1407171593780916615';
                        $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                    }
                    catch(Exception $e){
                        echo 'Message:' .$e->getMessage();
                    }
    
                }
            }


            // For Workshop
            if($pTask->task_type == "workshop"){
                $usersWithPendingTasks = User::where('role', 5)->where('deleted_at', NULL)->get();
                
                foreach($usersWithPendingTasks as $user){
                    $user_phonenumber = $user->phone;
                    $admin = User::where('role', 1)->first();
                    
                    // send sms to admin
                    try{
                        //http Url to send sms.
                        $url="http://trans.jaldisms.com/smsstatuswithid.aspx";
                        $fields = array(
                            'mobile' => env('SMS_ID'),
                            'pass' => env('SMS_PWD'),
                            'senderid' => env('SMS_SENDER_ID'),
                            'to' => $admin->phone,
                            'templateid' => '1407171205556423561',
                            'msg' => "લીડ ".$pTask->task." અમારા સીઆરએમમાં ​​ઉમેરવામાં આવ્યું છે - શ્રી ગણેશ એલ્યુનિયમ",
                            'msgtype'=> 'uc',
                        );
                        //url-ify the data for the POST
                        $fields_string = '';
                        foreach($fields as $key=>$value) { $fields_string .=
                        $key.'='.$value.'&'; }
                        rtrim($fields_string, '&');
                        //open connection
                        $ch = curl_init();
                        //set the url, number of POST vars, POST data
                        curl_setopt($ch,CURLOPT_URL, $url);
                        curl_setopt($ch,CURLOPT_POST, count($fields));
                        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                        //execute post
                        $result = curl_exec($ch);
                        //close connection
                        curl_close($ch);
                    }
                    catch(Exception $e){
                        echo 'Message:' .$e->getMessage();
                    }

                    // Send SMS to Workshop User
                    try{
                        //http Url to send sms.
                        $url="http://trans.jaldisms.com/smsstatuswithid.aspx";
                        $fields = array(
                            'mobile' => env('SMS_ID'),
                            'pass' => env('SMS_PWD'),
                            'senderid' => env('SMS_SENDER_ID'),
                            'to' => $user_phonenumber,
                            'templateid' => '1407171205556423561',
                            'msg' => "લીડ ".$pTask->task." અમારા સીઆરએમમાં ​​ઉમેરવામાં આવ્યું છે - શ્રી ગણેશ એલ્યુનિયમ",
                            'msgtype'=> 'uc',
                        );
                        //url-ify the data for the POST
                        $fields_string = '';
                        foreach($fields as $key=>$value) { $fields_string .=
                        $key.'='.$value.'&'; }
                        rtrim($fields_string, '&');
                        //open connection
                        $ch = curl_init();
                        //set the url, number of POST vars, POST data
                        curl_setopt($ch,CURLOPT_URL, $url);
                        curl_setopt($ch,CURLOPT_POST, count($fields));
                        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                        //execute post
                        $result = curl_exec($ch);
                        //close connection
                        curl_close($ch);
                    }
                    catch(Exception $e){
                        echo 'Message:' .$e->getMessage();
                    }    
                }
            }


            // For Fitting
            if($pTask->task_type == "fitting"){
                $usersWithPendingTasks = User::where('role', 6)->where('deleted_at', NULL)->get();
                
                foreach($usersWithPendingTasks as $user){
                    $user_phonenumber = $user->phone;
                    $admin = User::where('role', 1)->first();
                    
                    // send sms to admin
                    try{
                        //http Url to send sms.
                        $url="http://trans.jaldisms.com/smsstatuswithid.aspx";
                        $fields = array(
                            'mobile' => env('SMS_ID'),
                            'pass' => env('SMS_PWD'),
                            'senderid' => env('SMS_SENDER_ID'),
                            'to' => $admin->phone,
                            'templateid' => '1407171205556423561',
                            'msg' => "લીડ ".$pTask->task." અમારા સીઆરએમમાં ​​ઉમેરવામાં આવ્યું છે - શ્રી ગણેશ એલ્યુનિયમ",
                            'msgtype'=> 'uc',
                        );
                        //url-ify the data for the POST
                        $fields_string = '';
                        foreach($fields as $key=>$value) { $fields_string .=
                        $key.'='.$value.'&'; }
                        rtrim($fields_string, '&');
                        //open connection
                        $ch = curl_init();
                        //set the url, number of POST vars, POST data
                        curl_setopt($ch,CURLOPT_URL, $url);
                        curl_setopt($ch,CURLOPT_POST, count($fields));
                        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                        //execute post
                        $result = curl_exec($ch);
                        //close connection
                        curl_close($ch);
                    }
                    catch(Exception $e){
                        echo 'Message:' .$e->getMessage();
                    }

                    // Send SMS to Fitting User
                    try{
                        //http Url to send sms.
                        $url="http://trans.jaldisms.com/smsstatuswithid.aspx";
                        $fields = array(
                            'mobile' => env('SMS_ID'),
                            'pass' => env('SMS_PWD'),
                            'senderid' => env('SMS_SENDER_ID'),
                            'to' => $user_phonenumber,
                            'templateid' => '1407171205556423561',
                            'msg' => "લીડ ".$pTask->task." અમારા સીઆરએમમાં ​​ઉમેરવામાં આવ્યું છે - શ્રી ગણેશ એલ્યુનિયમ",
                            'msgtype'=> 'uc',
                        );
                        //url-ify the data for the POST
                        $fields_string = '';
                        foreach($fields as $key=>$value) { $fields_string .=
                        $key.'='.$value.'&'; }
                        rtrim($fields_string, '&');
                        //open connection
                        $ch = curl_init();
                        //set the url, number of POST vars, POST data
                        curl_setopt($ch,CURLOPT_URL, $url);
                        curl_setopt($ch,CURLOPT_POST, count($fields));
                        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                        //execute post
                        $result = curl_exec($ch);
                        //close connection
                        curl_close($ch);
                    }
                    catch(Exception $e){
                        echo 'Message:' .$e->getMessage();
                    }    
                }
            }


            // For Quotation
            if($pTask->task_type == "quotation"){
                $usersWithPendingTasks = User::where('role', 4)->where('deleted_at', NULL)->get();
                
                foreach($usersWithPendingTasks as $user){
                    $user_phonenumber = $user->phone;
                    $admin = User::where('role', 1)->first();
                    
                    // send sms to admin
                    try{
                        //http Url to send sms.
                        $url="http://trans.jaldisms.com/smsstatuswithid.aspx";
                        $fields = array(
                            'mobile' => env('SMS_ID'),
                            'pass' => env('SMS_PWD'),
                            'senderid' => env('SMS_SENDER_ID'),
                            'to' => $admin->phone,
                            'templateid' => '1407171205556423561',
                            'msg' => "લીડ ".$pTask->task." અમારા સીઆરએમમાં ​​ઉમેરવામાં આવ્યું છે - શ્રી ગણેશ એલ્યુનિયમ",
                            'msgtype'=> 'uc',
                        );
                        //url-ify the data for the POST
                        $fields_string = '';
                        foreach($fields as $key=>$value) { $fields_string .=
                        $key.'='.$value.'&'; }
                        rtrim($fields_string, '&');
                        //open connection
                        $ch = curl_init();
                        //set the url, number of POST vars, POST data
                        curl_setopt($ch,CURLOPT_URL, $url);
                        curl_setopt($ch,CURLOPT_POST, count($fields));
                        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                        //execute post
                        $result = curl_exec($ch);
                        //close connection
                        curl_close($ch);
                    }
                    catch(Exception $e){
                        echo 'Message:' .$e->getMessage();
                    }

                    // Send SMS to Quotation User
                    try{
                        //http Url to send sms.
                        $url="http://trans.jaldisms.com/smsstatuswithid.aspx";
                        $fields = array(
                            'mobile' => env('SMS_ID'),
                            'pass' => env('SMS_PWD'),
                            'senderid' => env('SMS_SENDER_ID'),
                            'to' => $user_phonenumber,
                            'templateid' => '1407171205556423561',
                            'msg' => "લીડ ".$pTask->task." અમારા સીઆરએમમાં ​​ઉમેરવામાં આવ્યું છે - શ્રી ગણેશ એલ્યુનિયમ",
                            'msgtype'=> 'uc',
                        );
                        //url-ify the data for the POST
                        $fields_string = '';
                        foreach($fields as $key=>$value) { $fields_string .=
                        $key.'='.$value.'&'; }
                        rtrim($fields_string, '&');
                        //open connection
                        $ch = curl_init();
                        //set the url, number of POST vars, POST data
                        curl_setopt($ch,CURLOPT_URL, $url);
                        curl_setopt($ch,CURLOPT_POST, count($fields));
                        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                        //execute post
                        $result = curl_exec($ch);
                        //close connection
                        curl_close($ch);
                    }
                    catch(Exception $e){
                        echo 'Message:' .$e->getMessage();
                    }    
                }
            }
        }
    }
}
