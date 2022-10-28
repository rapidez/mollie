<?php

Route::middleware('web')->group(function () {
    Route::get('mollie-return/{orderHash}/{paymentToken}', function ($orderHash, $paymentToken) {
        $url = config('rapidez.magento_url').'/graphql';

        $response = Http::post($url, [
            'query' => view('mollie::graphql.process-transaction')->render(),
            'variables' => [
                'payment_token' => $paymentToken,
            ],
        ])->throw()->object()->data;

        if (!$response->mollieProcessTransaction
            || in_array($response->mollieProcessTransaction->paymentStatus, ['ERROR', 'EXPIRED', 'CANCELED', 'FAILED'])) {
            return redirect('cart');
        }

        $order = DB::table('mollie_payment_paymenttoken')
            ->join('sales_order', 'sales_order.entity_id', '=', 'mollie_payment_paymenttoken.order_id')
            ->join('sales_order_payment', 'sales_order_payment.parent_id', '=', 'sales_order.entity_id')
            ->where('mollie_payment_paymenttoken.token', $paymentToken)
            ->first();

        if ($order->status === 'canceled') {
            return redirect('cart');
        }

        return view('mollie::'.$order->status, ['order' => $order]);
    });
});
