<?php

namespace Rapidez\Mollie\Actions;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckSuccessfulOrder
{
    /**
     * @throws RequestException
     */
    public function __invoke(Request $request)
    {
        $paymentToken = $request->get('payment_token');
        if (empty($paymentToken)) {
            return true;
        }

        $url = config('rapidez.magento_url').'/graphql';

        $response = Http::post($url, [
            'query'     => view('mollie::graphql.process-transaction')->render(),
            'variables' => [
                'payment_token' => $paymentToken,
            ],
        ])->throw()->object()->data;

        if ($response->mollieProcessTransaction && $response->mollieProcessTransaction->redirect_to_success_page) {
            return true;
        }

        // mollieProcessTransaction has already re-activated the cart for us.
        return false;
    }
}
