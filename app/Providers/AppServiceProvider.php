<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Local;
use App\Models\LocalSolicitud;
use App\Observers\LocalObserver;
use App\Observers\LocalSolicitudObserver;


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
        date_default_timezone_set('Europe/Madrid');
        Local::observe(LocalObserver::class);
        LocalSolicitud::observe(LocalSolicitudObserver::class);
    }
}
