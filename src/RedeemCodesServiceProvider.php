<?php

namespace Furic\RedeemCodes;

use Illuminate\Support\ServiceProvider;

class RedeemCodesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'redeemcodes');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/furic/redeemcodes'),
        ]);
        // $this->publishes([
        //     __DIR__ . '/../config/redeemcodes.php' => config_path('redeemcodes.php'),
        // ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('furic\redeemcodes\RedeemCodeController');
        $this->app->make('furic\redeemcodes\RedeemController');
        // $this->mergeConfigFrom(
        //     __DIR__ . '/../config/redeemcodes.php', 'redeemcodes'
        // );
    }
}
