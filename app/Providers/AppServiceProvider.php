<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('partials.nav-app', \App\Http\Composers\ProyectoComposer::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        setlocale(LC_TIME, 'es_MX.UTF8', 'Spanish_Spain.1252');
        Carbon::setLocale('es');

        $this->app->bind(
            \App\Contracts\ProyectoRepository::class,
            \App\Repositories\EloquentProyectoRepository::class
        );      
    }
}
