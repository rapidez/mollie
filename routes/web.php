<?php

Route::middleware('web')->group(function () {
    Route::get('mollie-return/{orderHash}', function ($orderHash) {
        $url = config('rapidez.magento_url').'/rest/'.config('rapidez.store_code').'/V1/mollie/get-order/'.$orderHash;
        $status = Http::get($url)->throw()[0]['status'];

        if ($status === 'canceled') {
            return redirect('cart');
        }

        return view('mollie::'.$status);
    });
});
