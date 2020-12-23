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
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'redeem-codes');
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/redeem-codes'),
        ], 'views');
        // $this->publishes([
        //     __DIR__ . '/../config/redeem-codes.php' => config_path('redeem-codes.php'),
        // ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Furic\RedeemCodes\Http\Controllers\RedeemCodeController');
        $this->app->make('Furic\RedeemCodes\Http\Controllers\RedeemController');
        // $this->mergeConfigFrom(
        //     __DIR__ . '/../config/redeem-codes.php', 'redeem-codes'
        // );
    }
    
}