<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserRecipient;
use App\Models\UserEmailSetting;
use Mail;
use Carbon\Carbon;
use App\Mail\SendMailable;

class EmailScheduling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:scheduling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = UserRecipient::where('status','!=',2)->get();
        foreach ($user as $a)
        {
            $email_settings = UserEmailSetting::where('user_id',$a->user_id)->orderBy('id','ASC')->get();
            $i = 0;
            foreach($email_settings as $e_setting){
                if($a->sent == $i){
                    $token = array(
                        'first_name'    => $a->first_name.' '.$a->last_name,
                        'profile_name'  => $a->first_name.' '.$a->last_name,
                        'profile_url'   => '',
                    );
                    $pattern = '[[%s]]';
                    foreach($token as $key=>$val){
                        $varMap[sprintf($pattern,$key)] = $val;
                    }     
                    $emailContent = strtr($e_setting->email_html,$varMap);
                    $subject = strtr($e_setting->subject,$varMap);
                    $fromname = strtr($a->name,$varMap);
                    $data = [ 
                        'content'       => $emailContent,
                        'email'         => $a->email,
                        'subject'       => $subject,
                        'fromname'      => $fromname,
                        'admin_email'   => 'rajantest76@gmail.com',
                        // 'admin_email'   => 'prit@gocinico.com',
                        'id'            => $a->id,
                    ];
                    $delay_days = $e_setting->delay_days;
                    if($delay_days == 0){
                        Mail::send('mail', $data, function($message) use ($data) {
                            $message->to($data['email'], 'fwdReviews')
                            ->subject($data['subject']);
                            $message->from($data['admin_email'],$data['fromname']);
                            $message->getHeaders()->addTextHeader('X-Model-ID',$data['id']);
                        });
                        $user_email = UserRecipient::where('id',$a->id)->first();
                        $user_email->sent       = $user_email->sent + 1;
                        $user_email->status     = 1;
                        $user_email->last_sent  = Carbon::now();
                        $user_email->save();
                    }else{
                        $send_date = date('Y-m-d', strtotime($a->last_sent. ' + '.$delay_days.' days'));
                        $today = date('Y-m-d');
                        if($today == $send_date){
                            Mail::send('mail', $data, function($message) use ($data) {
                                $message->to($data['email'], 'fwdReviews')
                                ->subject($data['subject']);
                                $message->from($data['admin_email'],$data['fromname']);
                                $message->getHeaders()->addTextHeader('X-Model-ID',$data['id']);
                            });
                            $user_email = UserRecipient::where('id',$a->id)->first();
                            $user_email->sent       = $user_email->sent + 1;
                            $user_email->last_sent  = Carbon::now();
                            $user_email->save();
                        }
                    }
                    $this->info('Email has been send successfully');
                }
                $i++;
            }
        }
        
    }
}
