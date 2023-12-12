<?php

namespace Rapidez\Mollie;

use Illuminate\Support\ServiceProvider;

class MollieServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'mollie');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/mollie'),
            ], 'views');

            $this->publishes([
                __DIR__ . '/../resources/payment-icons' => public_path('payment-icons'),
            ], 'payment-icons');
        }
    }
}
