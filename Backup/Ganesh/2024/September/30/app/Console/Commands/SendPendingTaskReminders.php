<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskManagement;
use App\Models\User;
use App\Models\MeasurementTask;
use App\Models\Project;
use App\Http\Helpers\SmsHelper;
use App\Models\Customer;
use App\Models\Setting;

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

        foreach ($pendingTasks as $pTask) {
            $projectDetail = Project::where('id', $pTask->project_id)->get();
            $setting= Setting::first();
            $customer = Customer::where('id',$projectDetail->customer_id)->first();
            $customerAddress = $customer->address . " " . $customer->cityDetail->name . " " . $customer->stateDetail->name . " " . $customer->zipcode;
            // For Measurement
            if ($pTask->task_type == "measurement") {
                $measurementtaskUsers = MeasurementTask::where('project_id', $pTask->project_id)->get();
                foreach ($measurementtaskUsers as $key => $measurementUsers) {
                    $measurement_user = User::where('id', $measurementUsers->user_id)->where('deleted_at', NULL)->first();
                    try {
                        $sentAdmin = false;
                        if ($key == 0) {
                            $sentAdmin = true;
                        }
                        $mobileNumber = $measurement_user->phone;
                        $templateid = '1407171593780916615';
                        if($setting->wa_message_sent == 1){
                            $message = "Dear " . $measurement_user->name . ", your task is pending for the measurement of " . $projectDetail->project_generated_id . " and address is ". $customerAddress.". Shree Ganesh Aluminum";
                            $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                        } else {
                            $message = "Dear " . $measurement_user->name . ", your task is pending for the measurement of " . $projectDetail->project_generated_id . ". Shree Ganesh Aluminum";
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                        }
                    } catch (Exception $e) {
                        echo 'Message:' . $e->getMessage();
                    }
                }
            }


            // For Workshop
            if ($pTask->task_type == "workshop") {
                $customerDetail = Customer::where('id',$projectDetail->customer_id)->first();
                if ($projectDetail->cutting_selection == 0) {
                    $usersWithPendingTasks = User::where('role', 5)->where('deleted_at', NULL)->get();
                    foreach ($usersWithPendingTasks as $key => $user) {
                        try {
                            $sentAdmin = false;
                            if ($key == 0) {
                                $sentAdmin = true;
                            }
                            $mobileNumber = $user->phone;
                            $templateid = '1407171593914575779';
                            if($setting->wa_message_sent == 1){
                                $message = "Workshop cutting for " .$customerDetail->name. " - " .$projectDetail->project_generated_id . " is not done, kindly complete it withing next 24 hours and address is ". $customerAddress.". - Shri Ganesh Aluminum.";
                                $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                            } else {
                                $message = "Workshop cutting for " .$customerDetail->name. " - " .$projectDetail->project_generated_id . " is not done, kindly complete it withing next 24 hours. - Shri Ganesh Aluminum.";
                                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                            }
                        } catch (Exception $e) {
                            echo 'Message:' . $e->getMessage();
                        }
                    }
                }
                if ($projectDetail->shutter_selection == 0) {
                    $usersWithPendingTasks = User::where('role', 5)->where('deleted_at', NULL)->get();
                    foreach ($usersWithPendingTasks as $key => $user) {
                        try {
                            $sentAdmin = false;
                            if ($key == 0) {
                                $sentAdmin = true;
                            }
                            $mobileNumber = $user->phone;
                            $templateid = '1407171593956996465';
                            if($setting->wa_message_sent == 1){
                                $message = "Workshop shutter joinery for ".$customerDetail->name. " - " .$projectDetail->project_generated_id." is not done. Kindly complete it within next 24 hours and address is ". $customerAddress.". Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                            } else {
                                $message = "Workshop shutter joinery for ".$customerDetail->name. " - " .$projectDetail->project_generated_id." is not done. Kindly complete it within next 24 hours and address is ". $customerAddress.". Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                            }
                        } catch (Exception $e) {
                            echo 'Message:' . $e->getMessage();
                        }
                    }
                }
                if ($projectDetail->glass_measurement == 0) {
                    $usersWithPendingTasks = User::where('role', 5)->where('deleted_at', NULL)->get();
                    foreach ($usersWithPendingTasks as $key => $user) {
                        try {
                            $sentAdmin = false;
                            if ($key == 0) {
                                $sentAdmin = true;
                            }
                            $mobileNumber = $user->phone;
                            $templateid = '1407171594268172670';
                            if($setting->wa_message_sent == 1){
                                $message = "Workshop glass measurement for ".$customerDetail->name. " - " .$projectDetail->project_generated_id." is not done. Kindly complete it within next 24 hours and address is ". $customerAddress.". Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                            } else {
                                $message = "Workshop glass measurement for ".$customerDetail->name. " - " .$projectDetail->project_generated_id." is not done. Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                            }
                        } catch (Exception $e) {
                            echo 'Message:' . $e->getMessage();
                        }
                    }
                }
                if ($projectDetail->glass_receive == 0) {
                    $usersWithPendingTasks = User::where('role', 5)->where('deleted_at', NULL)->get();
                    foreach ($usersWithPendingTasks as $key => $user) {
                        try {
                            $sentAdmin = false;
                            if ($key == 0) {
                                $sentAdmin = true;
                            }
                            $mobileNumber = $user->phone;
                            $templateid = '1407171594282255716';
                            if($setting->wa_message_sent == 1){
                                $message = "Workshop glass is not received for ".$customerDetail->name. " - " .$projectDetail->project_generated_id." and address is ". $customerAddress.". Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                            } else {
                                $message = "Workshop glass is not received for ".$customerDetail->name. " - " .$projectDetail->project_generated_id.". Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                            }
                        } catch (Exception $e) {
                            echo 'Message:' . $e->getMessage();
                        }
                    }
                }
                if ($projectDetail->shutter_ready == 0) {
                    $usersWithPendingTasks = User::where('role', 5)->where('deleted_at', NULL)->get();
                    foreach ($usersWithPendingTasks as $key => $user) {
                        try {
                            $sentAdmin = false;
                            if ($key == 0) {
                                $sentAdmin = true;
                            }
                            $mobileNumber = $user->phone;
                            $templateid = '1407171594295256186';
                            if($setting->wa_message_sent == 1){
                                $message = "Workshop glass fitting is not done for ".$customerDetail->name. " - " . $projectDetail->project_generated_id.". Kindly complete it within next 24 hours and address is ". $customerAddress.". Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                            } else {
                                $message = "Workshop glass fitting is not done for ".$customerDetail->name. " - " . $projectDetail->project_generated_id.". Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                            }
                        } catch (Exception $e) {
                            echo 'Message:' . $e->getMessage();
                        }
                    }
                }
                if ($projectDetail->shutter_ready == 0) {
                    $usersWithPendingTasks = User::where('role', 5)->where('deleted_at', NULL)->get();
                    foreach ($usersWithPendingTasks as $key => $user) {
                        try {
                            $sentAdmin = false;
                            if ($key == 0) {
                                $sentAdmin = true;
                            }
                            $mobileNumber = $user->phone;
                            $templateid = '1407171594295256186';
                            if($setting->wa_message_sent == 1){
                                $message = "Workshop glass fitting is not done for ".$customerDetail->name. " - " .$projectDetail->project_generated_id.". Kindly complete it within next 24 hours and address is ". $customerAddress.". Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                            } else {
                                $message = "Workshop glass fitting is not done for ".$customerDetail->name. " - " .$projectDetail->project_generated_id.". Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                            }
                        } catch (Exception $e) {
                            echo 'Message:' . $e->getMessage();
                        }
                    }
                }
                if ($projectDetail->material_delivered == 0) {
                    $usersWithPendingTasks = User::where('role', 5)->where('deleted_at', NULL)->get();
                    foreach ($usersWithPendingTasks as $key => $user) {
                        try {
                            $sentAdmin = false;
                            if ($key == 0) {
                                $sentAdmin = true;
                            }
                            $mobileNumber = $user->phone;
                            $templateid = '1407171594323827401';
                            if($setting->wa_message_sent == 1){
                                $message = "Material delivery of the ".$customerDetail->name. " - " .$projectDetail->project_generated_id." is not dispatched yet from workshop kindly complete the delivery within next 24 hours and address is ". $customerAddress.". Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                            } else {
                                $message = "Material delivery of the ".$customerDetail->name. " - " .$projectDetail->project_generated_id." is not dispatched yet from workshop kindly complete the delivery within next 24 hours. Shree Ganesh Aluminum";
                                $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                            }
                        } catch (Exception $e) {
                            echo 'Message:' . $e->getMessage();
                        }
                    }
                }
            }


            // For Fitting
            if ($pTask->task_type == "site_installation") {
                $usersWithPendingTasks = User::where('role', 6)->where('deleted_at', NULL)->get();
                foreach ($usersWithPendingTasks as $key=>$user) {
                    try {
                        $sentAdmin = false;
                        if ($key == 0) {
                            $sentAdmin = true;
                        }
                        $mobileNumber = $user->phone;
                        $templateid = '1407171594295256186';
                        if($setting->wa_message_sent == 1){
                            $message = "Workshop glass fitting is not done for ".$projectDetail->project_generated_id." Kindly complete it within next 24 hours and address is ". $customerAddress.". Shree Ganesh Aluminum";
                            $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                        } else {
                            $message = "Workshop glass fitting is not done for ".$projectDetail->project_generated_id." Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                        }
                    } catch (Exception $e) {
                        echo 'Message:' . $e->getMessage();
                    }
                }
            }


            // For Quotation
            if ($pTask->task_type == "quotation") {
                $usersWithPendingTasks = User::where('role', 4)->where('deleted_at', NULL)->get();

                foreach ($usersWithPendingTasks as $user) { 
                    try {
                        $sentAdmin = false;
                        if ($key == 0) {
                            $sentAdmin = true;
                        }
                        $mobileNumber = $user->phone;
                        $templateid = '1407171593888532613';
                        if($setting->wa_message_sent == 1){
                            $message = "Dear ".$user->name.", you have not uploaded quotation for the ".$projectDetail->project_generated_id." and address is ". $customerAddress.". Shri Ganesh Aluminum.";
                            $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                        } else {
                            $message = "Dear ".$user->name.", you have not uploaded quotation for the ".$projectDetail->project_generated_id.". Shri Ganesh Aluminum.";
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                        }
                    } catch (Exception $e) {
                        echo 'Message:' . $e->getMessage();
                    }
                }
            }
            if ($pTask->task_type == "purchase") {
                $usersWithPendingTasks = User::where('role', 8)->where('deleted_at', NULL)->get();

                foreach ($usersWithPendingTasks as $user) { 
                    try {
                        $sentAdmin = false;
                        if ($key == 0) {
                            $sentAdmin = true;
                        }
                        $mobileNumber = $user->phone;
                        $templateid = '1407171593888532613';
                        if($setting->wa_message_sent == 1){
                            $message = "Dear " . $user->name . ", you have not yet uploaded the purchase document for project " . $projectDetail->project_generated_id . " and address provided is " . $customerAddress . ". Shri Ganesh Aluminum.";
                            $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                        } else {
                            $message = "Dear " . $user->name . ", you have not yet uploaded the purchase document for project " . $projectDetail->project_generated_id . ". Shri Ganesh Aluminum.";
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                        }
                    } catch (Exception $e) {
                        echo 'Message:' . $e->getMessage();
                    }
                }
            }
            if ($pTask->task_type == "qa") {
                $usersWithPendingTasks = User::where('role', 10)->where('deleted_at', NULL)->get();

                foreach ($usersWithPendingTasks as $user) { 
                    try {
                        $sentAdmin = false;
                        if ($key == 0) {
                            $sentAdmin = true;
                        }
                        $mobileNumber = $user->phone;
                        $templateid = '1407171593888532613';
                        if($setting->wa_message_sent == 1){
                            $message = "Dear " . $user->name . ", you have not yet checking for project " . $projectDetail->project_generated_id . " and address is " . $customerAddress . ". Shri Ganesh Aluminum.";
                            $isSent = SmsHelper::sendSmsWithTemplate($mobileNumber, $message, $sentAdmin);
                        } else {
                            $message = "Dear " . $user->name . ", you have not yet checking for project " . $projectDetail->project_generated_id . ". Shri Ganesh Aluminum.";
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                        }
                    } catch (Exception $e) {
                        echo 'Message:' . $e->getMessage();
                    }
                }
            }
            info("Cron Job Run Successfully.");
        }
    }
}
