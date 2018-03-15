<?php

namespace App\Providers;

use App\Services\VendorService;
use Illuminate\Support\ServiceProvider;

class VendorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\VendorService', function ($app) {
            return new VendorService($app->make('Illuminate\Database\DatabaseManager'));
        });
    }
}
