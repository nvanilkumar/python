<?php

namespace App\Providers;

use App\Services\AudienceQuotaService;
use Illuminate\Support\ServiceProvider;

class AudienceQuotaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\AudienceQuotaService', function ($app) {
            return new AudienceQuotaService($app->make('Illuminate\Database\DatabaseManager'));
        });
    }
}
