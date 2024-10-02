<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Notification;
use App\Notifications\OffersNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        $userSchema = User::where('id','1')->latest()->get();
  
        $offerData = [
            // 'user_id' => '9',
            'type' => 'message',
            'title' => 'You received an offer.',
            'text' => 'Thank you',
            'url' => url('/'),
        ];
  
        Notification::send($userSchema, new OffersNotification($offerData));
        
        

        return 'Task completed!';
    }
}
