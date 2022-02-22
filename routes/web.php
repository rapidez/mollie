<?php

Route::middleware('web')->group(function () {
    Route::get('mollie-return/{orderHash}/{paymentToken}', function ($orderHash, $paymentToken) {
        $url = config('rapidez.magento_url').'/rest/'.config('rapidez.store_code').'/V1/mollie/get-order/'.$orderHash;
        $status = Http::get($url)->throw()[0]['status'];

        if ($status === 'canceled') {
            return redirect('cart');
        }

        $order = DB::table('mollie_payment_paymenttoken')
            ->join('sales_order', 'sales_order.entity_id', '=', 'mollie_payment_paymenttoken.order_id')
            ->join('sales_order_payment', 'sales_order_payment.parent_id', '=', 'sales_order.entity_id')
            ->where('mollie_payment_paymenttoken.token', $paymentToken)
            ->first();

        return view('mollie::'.$status, ['order' => $order]);
    });
});
