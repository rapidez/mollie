import { cart } from 'Vendor/rapidez/core/resources/js/stores/useCart'
import { addBeforePaymentMethodHandler, addBeforePlaceOrderHandler, addAfterPlaceOrderHandler } from 'Vendor/rapidez/core/resources/js/stores/usePaymentHandlers'

document.addEventListener('vue:loaded', (event) => {
    window.app.config.globalProperties.custom.mollie_selected_issuer = window.app.config.globalProperties?.custom?.mollie_selected_issuer
});

addBeforePaymentMethodHandler(async function (query, variables, options) {
    if (!variables.code.includes('mollie_') || !window?.app?.config?.globalProperties?.custom?.mollie_selected_issuer)
    {
        return [query, variables, options];
    }

    // Add mollie_selected_issuer to setPaymentMethodOnCart
    query = config.fragments.cart +
    `

    mutation setMolliePaymentMethodOnCart(
        $cart_id: String!,
        $code: String!,
        $mollie_selected_issuer: String
    ) {
        setPaymentMethodOnCart(input: {
            cart_id: $cart_id,
            payment_method: {
                code: $code,
                mollie_selected_issuer: $mollie_selected_issuer
            }
        }) {
            cart { ...cart }
        }
    }`

    variables.mollie_selected_issuer = window.app.config.globalProperties.custom.mollie_selected_issuer

    return [query, variables, options];
});

addBeforePlaceOrderHandler(async function (query, variables, options) {
    if (!cart.value?.selected_payment_method?.code?.includes('mollie_')) {
        return [query, variables, options];
    }

    // Add mollie_return_url to placeorder
    query = config.fragments.order + config.fragments.orderV2 +
    `

    mutation molliePlaceOrder($cart_id: String!, $mollie_return_url: String) {
        placeOrder(
            input: {
                cart_id: $cart_id,
                mollie_return_url: $mollie_return_url
            }
        ) {
            order {
                ...order
            }
            orderV2 {
                ...orderV2
            }
            errors {
                code
                message
            }
        }
    }`

    variables.mollie_return_url = url('/checkout/success?payment_token={{payment_token}}');

    return [query, variables, options]
});

addAfterPlaceOrderHandler(async function (response, mutationComponent) {
    mutationComponent.redirect = response?.data?.placeOrder?.order?.mollie_redirect_url || mutationComponent.redirect;
});
