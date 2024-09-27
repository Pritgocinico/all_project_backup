<?php

namespace App\Http\Controllers\common;

use Nnjeim\World\WorldHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\Fitting;
use App\Models\City;
use App\Models\Workshop;
use App\Models\Purchase;
use App\Models\Setting;
use App\Models\Feedback;
use App\Models\Feedbackuploads;
use App\Models\Log;
use Nnjeim\World\World;
use Carbon\Carbon;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;

class FeedbackController extends Controller
{
    protected $world;
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }
    
    public function feedBack(){
        $feedbacks = Feedback::orderBy('id', 'DESC')->get();
        $page = 'Feedback';
        if(Auth::user()->role == 1){
            return view('admin/feedback/feedbacks', compact('feedbacks'));
        }else{
            return view('quotation/feedback/feedbacks', compact('feedbacks', 'page'));
        }
    }

    public function viewFeedback(Request $request, $id){
        $feedback = Feedback::where('id', $id)->first();
        $feedbackfiles = Feedbackuploads::where('feedback_id', $id)->get();
        if(Auth::user()->role == 1){
            return view('admin/feedback/view_feedback', compact('feedback', 'feedbackfiles'));
        }else{
            return view('quotation/feedback/view_feedback', compact('feedback', 'feedbackfiles'));
        }
    }

    public function deleteFeedback($id){
        $feedback = Feedback::find($id);
        $feedback->delete();
        if(Auth::user()->role == 1){
            $user = User::where('id',Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Feedback - ',
                'text' => 'Feedback Deleted',
                'url' => route('feedbacks'),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        }else{
            $user = User::where('id',Auth::user()->id)->first();
            $notificationData = [
                'type' => 'message',
                'title' => 'Feedback - ',
                'text' => 'Feedback Deleted',
                'url' => route('quotation_feedbacks'),
            ];
            Notification::send($user, new OffersNotification($notificationData));
        }
        

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Feedback';
        $log->log       = 'Feedback Deleted';
        $log->save();
        return response()->json("success", 200);
    }

}
