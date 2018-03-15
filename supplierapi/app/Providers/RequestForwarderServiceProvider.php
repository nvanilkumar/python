<?php

namespace App\Providers;

use App\Services\BlendrRequestForwarder;
use Illuminate\Support\ServiceProvider;

class RequestForwarderServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\RequestForwarderInterface', function ($app) {
            return new BlendrRequestForwarder($app->make('Illuminate\Http\Request'), $app->make('App\Services\Blendr\Api'));
        });
    }
}
