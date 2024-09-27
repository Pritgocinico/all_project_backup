<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Notification;
use App\Notifications\SendNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
  
    public function index()
    {
        return view('product');
    }
    
    public function readNotification(Request $req){
        $notificationId = $req->id;
        $userUnreadNotification = auth()->user()
                                    ->unreadNotifications
                                    ->where('id', $notificationId)
                                    ->first();
        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }
        return 1;
    }
    
    public function readAllNotifications(Request $request) {
        Auth::user()->unreadNotifications->each(function ($notification) {
            $notification->markAsRead();
        });
    
        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function sendOfferNotification() {
        $userSchema = User::where('id','9')->latest()->get();
  
        $offerData = [
            'user_id' => '9',
            'type' => 'message',
            'title' => 'You received an offer.',
            'text' => 'Thank you',
            'url' => url('/'),
        ];
  
        Notification::send($userSchema, new SendNotification($offerData));
        
        $pusher = new Pusher(env('key'),  env('secret'), env('app_id'), [
            'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
        ]);
        $pusher->trigger('notifications', 'new-notification', $offerData);

        return 'Task completed!';
    }
}
