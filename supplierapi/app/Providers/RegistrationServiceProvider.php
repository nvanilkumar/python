<?php

namespace App\Providers;

use App\Services\RegistrationService;
use Illuminate\Support\ServiceProvider;

class RegistrationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\RegistrationService', function ($app) {
            return new RegistrationService($app->make('Illuminate\Database\DatabaseManager'));
        });
    }
}
