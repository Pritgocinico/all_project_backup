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

        foreach ($pendingTasks as $pTask) {
            $projectDetail = Project::where('id', $pTask->project_id)->get();
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
                        $message = "Dear " . $measurement_user->name . ", your task is pending for the measurement of " . $projectDetail->project_generated_id . ". Shree Ganesh Aluminum";
                        $templateid = '1407171593780916615';
                        $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                    } catch (Exception $e) {
                        echo 'Message:' . $e->getMessage();
                    }
                }
            }


            // For Workshop
            if ($pTask->task_type == "workshop") {
                if ($projectDetail->cutting_selection == 0) {
                    $usersWithPendingTasks = User::where('role', 5)->where('deleted_at', NULL)->get();
                    foreach ($usersWithPendingTasks as $key => $user) {
                        try {
                            $sentAdmin = false;
                            if ($key == 0) {
                                $sentAdmin = true;
                            }
                            $mobileNumber = $user->phone;
                            $message = "Workshop cutting for " . $projectDetail->project_name . " is not done, kindly complete it withing next 24 hours. - Shri Ganesh Aluminum.";
                            $templateid = '1407171593914575779';
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
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
                            $message = "Workshop shutter joinery for ".$projectDetail->project_name." is not done. Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                            $templateid = '1407171593956996465';
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
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
                            $message = "Workshop glass measurement for ".$projectDetail->project_name." is not done. Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                            $templateid = '1407171594268172670';
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
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
                            $message = "Workshop glass is not received for ".$projectDetail->project_name.". Shree Ganesh Aluminum";
                            $templateid = '1407171594282255716';
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
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
                            $message = "Workshop glass fitting is not done for ".$projectDetail->project_name.". Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                            $templateid = '1407171594295256186';
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
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
                            $message = "Workshop glass fitting is not done for ".$projectDetail->project_name.". Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                            $templateid = '1407171594295256186';
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
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
                            $message = "Material delivery of the ".$projectDetail->project_name." is not dispatched yet from workshop kindly complete the delivery within next 24 hours. Shree Ganesh Aluminum";
                            $templateid = '1407171594323827401';
                            $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                        } catch (Exception $e) {
                            echo 'Message:' . $e->getMessage();
                        }
                    }
                }
            }


            // For Fitting
            if ($pTask->task_type == "fitting") {
                $usersWithPendingTasks = User::where('role', 6)->where('deleted_at', NULL)->get();
                foreach ($usersWithPendingTasks as $key=>$user) {
                    try {
                        $sentAdmin = false;
                        if ($key == 0) {
                            $sentAdmin = true;
                        }
                        $mobileNumber = $user->phone;
                        $message = "Workshop glass fitting is not done for ".$projectDetail->name." Kindly complete it within next 24 hours. Shree Ganesh Aluminum";
                        $templateid = '1407171594295256186';
                        $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
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
                        $message = "Dear ".$user->name.", you have not uploaded quotation for the ".$projectDetail->project_name.". Shri Ganesh Aluminum.";
                        $templateid = '1407171593888532613';
                        $isSent = SmsHelper::sendSms($mobileNumber, $message, $templateid, $sentAdmin);
                    } catch (Exception $e) {
                        echo 'Message:' . $e->getMessage();
                    }
                }
            }
        }
    }
}
