<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Http;
use App\Models\User;

class SmsHelper
{
    public static function sendSms($mobileNumber, $message, $templateid, $sendToAdmin = false)
    {
        $url = "http://trans.jaldisms.com/smsstatuswithid.aspx";
    
        $fields = [
            'mobile' => env('SMS_ID'),
            'pass' => env('SMS_PWD'),
            'senderid' => env('SMS_SENDER_ID'),
            'to' => $mobileNumber,
            'templateid' => $templateid,
            'msg' => $message,
            'msgtype' => 'uc',
        ];
    
        $fields_string = http_build_query($fields);
        $response = Http::asForm()->post($url, $fields);
        $isSent = $response->successful();
    
        if ($sendToAdmin) {
            $adminDetail = User::where('role','1')->where('status',1)->get();
            foreach($adminDetail as $admin){
                $adminMobileNumber = $admin->phone;
                $fields['to'] = $adminMobileNumber;
                $fields_string = http_build_query($fields);
                $adminResponse = Http::asForm()->post($url, $fields);
                $isSentToAdmin = $adminResponse->successful();
            }
    
            return $isSent && $isSentToAdmin;
        }
    
        return $isSent;
    }
}