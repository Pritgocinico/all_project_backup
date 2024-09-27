<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Business;
use App\Models\Setting;
use App\Models\UserRecipient;
use App\Models\UserEmailSetting;
use Mail;
use App\Mail\SendMailable;
use Carbon\Carbon;
use App\Models\CustomerFeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserRecipientImport;

class RecipientController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function recipients(Request $req){
        $page = 'Recipients';
        $icon = 'dashboard.png';
        if(Auth::user()->business != 0){
            $business = Business::where('id',Auth::user()->business)->first();
        }else{
            $business = Business::where('client_id',Auth::user()->id)->where('status',1)->first();
        }
        $sent = UserEmailSetting::where('user_id',$business->id)->count();
        $recipients = UserRecipient::where('user_id',$business->id)->get();
        $inqueue = UserRecipient::where('user_id',$business->id)->where('status',0)->where('email_activity', 0)->get();
        $active = UserRecipient::where('user_id',$business->id)->where('sent','<',$sent)->where('email_activity', 0)->get();
        $complete = UserRecipient::where('user_id',$business->id)->where('last_sent','!=',NULL)->where('email_activity', 0)->get();
        $funnel_data = CustomerFeedback::where('user_id',$business->id)->where('status',1)->get();
        return view('client.recipients.recipients',compact('page','icon','recipients','active','complete','inqueue','business','funnel_data'));
    }
    public function requestReview(Request $request){
        $review = new UserRecipient();
        $review->user_id = Auth::user()->business;
        $review->first_name = $request->first_name;
        $review->last_name = $request->last_name;
        $review->email = $request->email;
        $review->save();
        return response()->json(['message'=>'success'], 200);
    }
    public function importRequest(Request $request){
        $file = $request->file('bulk_upload');
        $fileContents = file($file->getPathname());
        $i = 0;
        $import = new UserRecipientImport();
        Excel::import($import, $file);
        // foreach ($fileContents as $line) {
        //     $i++;
        //     if($i != 1){
        //         $data = str_getcsv($line);
        //         $user = User::where('id',Auth::user()->id)->first();
        //         // echo '<pre>';
        //         // print_r($user->business);
        //         // exit;
        //         $recipients = new UserRecipient();
        //         $recipients->user_id    = $user->business;
        //         $recipients->email      = $data[0];
        //         $recipients->first_name = $data[1];
        //         $recipients->last_name  = $data[2];
        //         $recipients->phone      = $data[3];
        //         $recipients->save();
        //         $email_settings = UserEmailSetting::where('user_id',$user->business)->orderBy('id','ASC')->get();
        //         $i = 0;
        //         foreach($email_settings as $e_setting){
        //             $token = array(
        //                 'first_name'    => $recipients->first_name.' '.$recipients->last_name,
        //                 'profile_name'  => $recipients->first_name.' '.$recipients->last_name,
        //                 'profile_url'   => '',
        //             );
        //             $pattern = '[[%s]]';
        //             foreach($token as $key=>$val){
        //                 $varMap[sprintf($pattern,$key)] = $val;
        //             }     
        //             $emailContent = strtr($e_setting->email_html,$varMap);
        //             $subject = strtr($e_setting->subject,$varMap);
        //             $fromname = strtr($recipients->name,$varMap);
        //             $data = [ 
        //                 'content'       => $emailContent,
        //                 'email'         => $recipients->email,
        //                 'subject'       => $subject,
        //                 'fromname'      => $fromname,
        //                 'admin_email'   => 'rajantest76@gmail.com',
        //                 'id'            => $recipients->id,
        //             ];
        //             $delay_days = $e_setting->delay_days;
        //             if($delay_days == 0){
        //                 Mail::send('mail', $data, function($message) use ($data) {
        //                     $message->to($data['email'], 'fwdReviews')
        //                     ->subject($data['subject']);
        //                     $message->from($data['admin_email'],$data['fromname']);
        //                     $message->getHeaders()->addTextHeader('X-Model-ID',$data['id']);
        //                 });
        //                 $user_email = UserRecipient::where('id',$recipients->id)->first();
        //                 $user_email->sent       = $user_email->sent + 1;
        //                 $user_email->status     = 1;
        //                 $user_email->last_sent  = Carbon::now();
        //                 $user_email->save();
        //             }
        //             $i++;
        //         }
        //     }
        // }
        Session::flash('success','File Import Successfully.');
        return redirect()->back();
    }

    public function reactiveRecipient(Request $request){
        $recipient = UserRecipient::where('id',$request->id)->first();
        $recipient->status = 0;
        $recipient->sent = 0;
        $recipient->save();
        return response()->json(['message'=>'success'], 200);
    }

    public function endCampaign(Request $request){
        $recipient = UserRecipient::where('id',$request->id)->first();
        $recipient->email_activity = 1;
        $recipient->save();
        return response()->json(['message'=>'success'], 200);
    }
}
