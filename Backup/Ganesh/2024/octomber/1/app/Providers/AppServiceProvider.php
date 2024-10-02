<?php

namespace App\Providers;

use Notification;
use App\Notifications\OffersNotification;
use Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view){
            if(auth()->user()){
                $user = auth()->user();
                $notification = $user->unreadNotifications;
               view()->share('notifications', $notification);
            }
       });
    }
}
