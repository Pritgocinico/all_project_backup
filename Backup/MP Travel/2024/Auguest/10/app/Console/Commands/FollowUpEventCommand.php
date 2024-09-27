<?php

namespace App\Console\Commands;

use App\Models\FollowUpEvent;
use App\Models\FollowUpMember;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class FollowUpEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:follow-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $followUpEventList = FollowUpEvent::where('event_status',1)->get();
        foreach ($followUpEventList as $key => $follow) {
            $followEventMember = FollowUpMember::where('followup_id',$follow->id)->get();
            foreach ($followEventMember as $key => $member) {
                $userDetail = User::where('id',$member->user_id)->first();
                $email = $userDetail->email;
                $data = [
                    'userDetail' => $userDetail,
                    'followUpEvent' => $follow,
                    'createdUser' => User::where('id',$follow->created_by)->first(),
                ];
                try {
                    Mail::send('admin.email.follow_up',$data, function ($message) use ($email) {
                        $message->to($email)
                                ->subject("Need to follow of pending follow up");
                    });
                } catch (\Throwable $th) {
                    dd($th->getMessage());
                }
            }
        }
    }
}
