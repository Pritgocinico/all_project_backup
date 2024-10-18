<?php



namespace App\Console\Commands;



use App\Models\FollowUpEvent;

use App\Models\FollowUpMember;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Log;



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
        $threeDay = Carbon::now()->subDays(3)->toDateString();
        $followUpEventList = FollowUpEvent::with('followUpMemberDetail')->whereDoesntHave('commentDetail')->whereDoesntHave('followUpFiles')->whereDate('created_at', $threeDay)->where('event_status', 1)->get();

        foreach ($followUpEventList as $key => $follow) {
            if (isset($follow->followUpMemberDetail)) {
                foreach ($follow->followUpMemberDetail as $key => $member) {
                    if (isset($member->userDetail) && isset($follow->userDetail)) {
                        $email = $member->userDetail->email;
                        $data = [

                            'userDetail' => $member->userDetail,

                            'followUpEvent' => $follow,

                            'createdUser' => $follow->userDetail,

                        ];

                        try {

                            Mail::send('admin.email.follow_up', $data, function ($message) use ($email) {

                                $message->to($email)

                                    ->subject("Need to follow of pending follow up");
                            });
                        } catch (\Throwable $th) {

                            Log::info('Error sending follow-up email: ' . $th->getMessage());
                        }
                    }
                }
            }
        }

        Log::info('Cron job run successfully.');
    }
}
