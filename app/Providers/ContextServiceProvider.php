<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ContextServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Contracts\Context::class,
            \App\ContextSession::class
        );

        $this->app->singleton('app.context', \App\ContextSession::class);
    }
}
