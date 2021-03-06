<?php

namespace Sinevia\Business;

use Illuminate\Support\ServiceProvider;

class BusinessServiceProvider extends ServiceProvider {

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            dirname(__DIR__) . '/config/business.php' => config_path('business.php'),
            $this->loadMigrationsFrom(dirname(__DIR__) . '/database/migrations'),
            $this->loadViewsFrom(dirname(__DIR__) . '/resources/views', 'business'),
            //$this->loadRoutesFrom(dirname(__DIR__).'/routes.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {

    }

}
