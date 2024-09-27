<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Setting;
use App\Models\UserPermission;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view)
        {
            if (Auth::check()) {
                $setting = Setting::first();
                View::share('setting', $setting);
                $userid =  Auth::user()->id;
                $user = User::where('id',$userid)->first();
                $permissions = UserPermission::where('user_id',$user->id)->get();
                View::share('permissions', $permissions);
                $notification = $user->unreadNotifications->take(7);
                view()->share('notifications', $notification);
                if($user->role == 2){
                    $permissions = UserPermission::where('user_id',$user->id)->get();
                    View::share('permissions', $permissions);
                    $view->with('permissions', $permissions );
                    $users = User::where('id',$userid)->first();
                    $notification = $user->unreadNotifications->take(7);
                    view()->share('notifications', $notification);
                }elseif($user->role == 3){
                    $permissions = UserPermission::where('user_id',$user->id)->get();
                    View::share('permissions', $permissions);
                    $view->with('permissions', $permissions);
                    $notification = $user->unreadNotifications->take(7);
                    view()->share('notifications', $notification);
                }
             }
        });
    }
}
