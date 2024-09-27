<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Business;
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
                if($user->role == 2){
                    $client_business = Business::where('client_id',$userid)->get();
                    if(blank($client_business)){
                       $client_business = [];
                    }
                    View::share('client_business', $client_business);
                }
            }
            if(auth()->user()){
                $user = auth()->user();
                $notification = $user->unreadNotifications;
               view()->share('notifications', $notification);
            }
        });
    }
}
