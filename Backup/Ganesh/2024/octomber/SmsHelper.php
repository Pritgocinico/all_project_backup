<?php

namespace App\Http\Helpers;

use App\Models\GlassMeasurementFile;
use App\Models\Invoicefile;
use App\Models\Measurementfile;
use App\Models\ProjectCompleteImage;
use App\Models\Purchase;
use App\Models\QuotationUpload;
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
        $numberArray[] = ['to' => '91' . $mobileNumber];
        if ($sendToAdmin) {
            $adminDetail = User::where('role', '1')->where('status', 1)->get();
            foreach ($adminDetail as $admin) {
                $numberArray[] = ['to' => '91' . $admin->phone];
            }
        }
        // foreach ($numberArray as $number) {
        self::sendWAMessage($numberArray, $message);
        // }

    }
    public static function sendWAMessage($number, $message)
    {
        $apiUrl = 'http://whatsapp.tellsell.co.in/api/v1/' . 'sendBulkMessage';
        $apiKey = 'e84ad9d01fd9491393f0c2a603cd5cc4';
        $payload = json_encode([
            'apikeys' => [
                ['key' => $apiKey]
            ],
            'toNumbers' => $number,
            'message' => $message,
            'IsUrgent' => true,
        ]);
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
            CURLOPT_POSTFIELDS => $payload,
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

    public static function sendSmsWithFile($mobileNumber, $message, $projectId, $sendToAdmin = false)
    {
        $numberArray[] = ['to' => '91' . $mobileNumber];
        if ($sendToAdmin) {
            $adminDetail = User::where('role', '1')->where('status', 1)->get();
            foreach ($adminDetail as $admin) {
                $numberArray[] = ['to' => '91' . $admin->phone];
            }
        }
        $fileName = "Project Invoice";
        $apiUrl = "sendBulkForDocument";
        $invoiceFile = Invoicefile::where('project_id', $projectId)->get();
        foreach ($invoiceFile as $key => $file) {
            $fileUrl = url('/') . '/public/invoicefiles/' . $file->invoice;
            self::sendWAMessageWithFile($numberArray, $message, $fileUrl, $fileName, $apiUrl);
        }
    }
    public static function sendSmsWithMeasurement($mobileNumber, $message, $projectId, $sendToAdmin = false)
    {
        $numberArray[] = ['to' => '91' . $mobileNumber];
        if ($sendToAdmin) {
            $adminDetail = User::where('role', '1')->where('status', 1)->get();
            foreach ($adminDetail as $admin) {
                $numberArray[] = ['to' => '91' . $admin->phone];
            }
        }
        $fileName = "Project Measurement File";
        $apiUrl = "sendBulkForDocument";
        $invoiceFile = Measurementfile::where('project_id', $projectId)->latest()->first();
        $fileUrl = url('/') . '/public/measurementfile/' . $invoiceFile->measurement;
        if($projectId == 347){
            dd($fileUrl);
        }
        self::sendWAMessageWithFile($numberArray, $message, $fileUrl, $fileName, $apiUrl);
    }
    public static function sendSmsWithQuotationFile($mobileNumber, $message, $projectId, $sendToAdmin = false)
    {
        $numberArray[] = ['to' => '91' . $mobileNumber];
        if ($sendToAdmin) {
            $adminDetail = User::where('role', '1')->where('status', 1)->get();
            foreach ($adminDetail as $admin) {
                $numberArray[] = ['to' => '91' . $admin->phone];
            }
        }
        $fileName = "Project Quotation File";
        $apiUrl = "sendBulkForDocument";
        $invoiceFile = QuotationUpload::where('project_id', $projectId)->latest()->first();
        $fileUrl = url('/') . '/public/quotationfile/' . $invoiceFile->file;
        self::sendWAMessageWithFile($numberArray, $message, $fileUrl, $fileName, $apiUrl);
    }

    public static function sendSmsWithImage($mobileNumber, $message, $projectId, $sendToAdmin = false)
    {
        $numberArray[] = ['to' => '91' . $mobileNumber];
        if ($sendToAdmin) {
            $adminDetail = User::where('role', '1')->where('status', 1)->get();
            foreach ($adminDetail as $admin) {
                $numberArray[] = ['to' => '91' . $admin->phone];
            }
        }
        $fileName = "Project Completed File";
        $apiUrl = "sendBulkForImage";
        $invoiceFile = ProjectCompleteImage::where('project_id', $projectId)->get();
        foreach ($invoiceFile as $file) {
            $fileUrl = url('/') . '/public/project/' . $file->file;
            self::sendWAMessageWithFile($numberArray, $message, $fileUrl, $fileName, $apiUrl);
        }
    }


    public static function sendWAMessageWithFile($number, $message, $fileUrl, $fileName, $apiUrl)
    {
        $apiUrl = 'http://whatsapp.tellsell.co.in/api/v1/' . $apiUrl;
        $apiKey = 'e84ad9d01fd9491393f0c2a603cd5cc4';
        $payload = json_encode([
            'apikeys' => [
                ['key' => $apiKey]
            ],
            'toNumbers' => $number,
            'IsUrgent' => true,
            'url' => $fileUrl,
            'fileName' => $fileName,
            'caption' => $message,
        ]);
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
            CURLOPT_POSTFIELDS => $payload,
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
    public static function sendSmsWithPurchaseFile($message, $projectId, $sendToAdmin = false)
    {
        $numberArray = [];
        $purchaseUser = User::where('role', '8')->where('status', 1)->get();
        foreach ($purchaseUser as $purchase) {
            $numberArray[] = ['to' => '91' . $purchase->phone];
        }
        if ($sendToAdmin) {
            $adminDetail = User::where('role', '1')->where('status', 1)->get();
            foreach ($adminDetail as $admin) {
                $numberArray[] = ['to' => '91' . $admin->phone];
            }
        }
        $invoiceFile = Purchase::where('project_id', $projectId)->latest()->first();
        $fileUrl = url('/') . '/public/purchases/' . $invoiceFile->purchase;
        $fileName = "Project Purchase File";
        $apiUrl = "sendBulkForDocument";
        self::sendWAMessageWithFile($numberArray, $message, $fileUrl, $fileName, $apiUrl);
    }

    public static function sedSmsWithGlassFile($mobileNumber, $message, $projectId, $sendToAdmin = false)
    {
        $numberArray[] = ['to' => '91' . $mobileNumber];
        if ($sendToAdmin) {
            $adminDetail = User::where('role', '1')->where('status', 1)->get();
            foreach ($adminDetail as $admin) {
                $numberArray[] = ['to' => '91' . $admin->phone];
            }
        }
        $fileName = "Project Glass Measurement File";
        $apiUrl = "sendBulkForDocument";
        $invoiceFile = GlassMeasurementFile::where('project_id', $projectId)->latest()->first();
        $fileUrl = url('/') . '/public/glassmeasurementfiles/' . $invoiceFile->file;
        self::sendWAMessageWithFile($numberArray, $message, $fileUrl, $fileName, $apiUrl);
    }
}
