<?php

namespace App\Providers;
use App\Models\Access;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
            if(auth()->user()){
                
            }
        View::composer('*', function ($view) {
            $settings = Setting::first();
            $view->with('settings', $settings);
            if (auth()->check()) {
                $accesses = resolve(Access::class)->get(true);
                $view->with('accesses', $accesses);
                $notification = auth()->user()->unreadNotifications;
                $view->with('notifications', $notification);
                $getAllUserList = User::where('id','!=',Auth()->user()->id)->get();
                $view->with('getAllUserList', $getAllUserList);
            }
        });
    }
}
