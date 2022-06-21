<?php

Route::middleware('web')->group(function () {
    Route::get('mollie-return/{orderHash}/{paymentToken}', function ($orderHash, $paymentToken) {
        $url = config('rapidez.magento_url').'/graphql';

        $response = Http::post($url, [
            'query' => 'mutation MollieProcessTransaction($payment_token: String!) {
                mollieProcessTransaction (input: { payment_token: $payment_token }) {
                    paymentStatus,
                    cart {
                        mollie_available_issuers {
                            name,
                            code
                        }
                    }
                }
            }',
            'variables' => [
                'payment_token' => $paymentToken,
            ],
        ])->throw()->object()->data;

        // The cart is only available when the payment status is failed, canceled or expired.
        if (!$response->mollieProcessTransaction
            || isset($response->mollieProcessTransaction->cart)
            || $response->mollieProcessTransaction->paymentStatus === 'ERROR') {
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
