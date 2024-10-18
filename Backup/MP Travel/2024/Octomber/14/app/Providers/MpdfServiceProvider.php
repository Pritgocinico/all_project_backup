<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mpdf\Mpdf;

class MpdfServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Mpdf::class, function ($app) {
            return new Mpdf();
        });
    }

    public function boot()
    {
        //
    }
}
