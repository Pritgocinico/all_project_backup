<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Setting;

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
        view()->composer('*', function ($view)
        {
            if (Auth::check()) {
                $userid =  Auth::user()->id;
                $user = User::where('id',$userid)->first();
                if($user->role == 3){
                    $users = User::where('id',$userid)->first();
                    $notification = $users->unreadNotifications;
                    view()->share('notifications', $notification);
                }else{
                }
             }

        });
    }
}
