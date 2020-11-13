<?php

use Rapidez\Core\Models\Category;
use Rapidez\Core\Models\Page;
use Rapidez\Core\Models\Product;
use Rapidez\Core\Models\Rewrite;

Route::middleware('web')->group(function () {
    Route::get('mollie-return/{orderHash}', function ($orderHash) {
        $url = config('rapidez.magento_url').'/rest/'.config('rapidez.store_code').'/V1/mollie/get-order/'.$orderHash;
        return view('mollie::' . Http::get($url)->throw()[0]['status']);
    });
});
