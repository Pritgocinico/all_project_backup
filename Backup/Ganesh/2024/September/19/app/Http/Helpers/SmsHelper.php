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
            $adminDetail = User::where('role', '1')->where('status', 1)->get();
            foreach ($adminDetail as $admin) {
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
    public static function sendSmsWithTemplate($mobileNumber, $message, $sendToAdmin = false)
    {
        
        $numberArray[]="91".$mobileNumber;
        if($sendToAdmin){
            $adminDetail = User::where('role', '1')->where('status', 1)->get();
            foreach ($adminDetail as $admin) {
                $numberArray[] = "91".$admin->phone;
            }
        }
        // foreach ($numberArray as $number) {
            self::sendWAMessage($numberArray, $message);
        // }
        
    }
    public static function sendWAMessage($number,$message){
        $apiUrl = env('TELSELL_API_URL') . 'sendMessage';
        $apiKey = 'e84ad9d01fd9491393f0c2a603cd5cc4';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode(array(
                'key' => $apiKey,
                "to" => $number,
                "message" => $message,
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            info(curl_error($curl));
        } else {
            curl_close($curl);
            info($response);
        }
    }
}