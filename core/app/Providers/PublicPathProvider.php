<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PublicPathProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->setBasePath(dirname(dirname(dirname(__DIR__))));
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
