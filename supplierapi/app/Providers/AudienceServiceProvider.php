<?php

namespace App\Providers;

use App\Services\AudienceService;
use Illuminate\Support\ServiceProvider;

class AudienceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\AudienceService', function ($app) {
            return new AudienceService($app->make('Illuminate\Database\DatabaseManager'));
        });
    }
}
