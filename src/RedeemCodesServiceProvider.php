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
        $this->loadViewsFrom(__DIR__.'/views', 'redeem-codes');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/furic/redeem-codes'),
        ]);
        // $this->publishes([
        //     __DIR__ . '/../config/redeem-codes.php' => config_path('redeem-codes.php'),
        // ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('furic\redeem-codes\RedeemCodeController');
        $this->app->make('furic\redeem-codes\RedeemController');
        // $this->mergeConfigFrom(
        //     __DIR__ . '/../config/redeem-codes.php', 'redeem-codes'
        // );
    }
}
