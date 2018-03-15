<?php

namespace App\Providers;

use App\Services\Blendr\Api;
use App\Services\Blendr\Auth;
use Illuminate\Support\ServiceProvider;

class BlendrServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\Blendr\Api', function ($app) {
            $blendr = new Api(env('BLENDR_HOST'));
            $auth = new Auth(env('BLENDR_USERNAME'), env('BLENDR_PASSWORD'));
            $blendr->setAuth($auth);
            return $blendr;
        });
    }
}
