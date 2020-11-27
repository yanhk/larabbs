<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JPush\Client;

class JpushServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(Client::class, function ($app){
            return new Client(config('jpush.key'), config('jupsh.secret'));
        });

        $this->app->alias(Client::class, 'jupsh');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
