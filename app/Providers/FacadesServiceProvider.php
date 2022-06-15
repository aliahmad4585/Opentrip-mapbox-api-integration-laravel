<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\HttpHelper;
use App\Helpers\LocationHelper;

class FacadesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //Helpers
        $this->app->bind('HttpCall', function () {
            return new HttpHelper();
        });

        $this->app->bind('Location', function () {
            return new LocationHelper();
        });
    }
}
