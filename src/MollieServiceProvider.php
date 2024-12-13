<?php

namespace Rapidez\Mollie;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Rapidez\Mollie\Actions\CheckSuccessfulOrder;
use TorMorten\Eventy\Facades\Eventy;

class MollieServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'mollie');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/mollie'),
            ], 'views');

            $this->publishes([
                __DIR__.'/../resources/payment-icons' => public_path('payment-icons'),
            ], 'payment-icons');
        }

        // Fallback for default mollie url
        Route::get('mollie/checkout/process', fn() => redirect(route('checkout.success', request()->query()), 308));
        // Fallback for legacy mollie url
        Route::get('mollie-return/{orderHash}/{paymentToken}', fn ($orderHash, $paymentToken) => redirect(route('checkout.success', ['order_hash' => $orderHash, 'payment_token' => $paymentToken, ...request()->query()]), 308));

        Eventy::addFilter('checkout.queries.order.data', function($attributes = []) {
            $attributes[] = 'mollie_redirect_url';
            $attributes[] = 'mollie_payment_token';
            return $attributes;
        });

        Eventy::addFilter('checkout.checksuccess', function($success = true) {
            return $success && App::call(CheckSuccessfulOrder::class);
        });
    }
}
