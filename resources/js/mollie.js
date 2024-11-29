document.addEventListener('vue:loaded', () => {
    window.app.$on('checkout-credentials-saved', () => {
        window.app.magentoCart('get', 'mollie/payment-token').then(response => {
            window.app.checkout.mollie = response
        })
    });

    window.app.$on('checkout-payment-saved', (data) => {
        if (!data.order.payment_method_code.includes('mollie_')) {
            return;
        }
        window.app.checkout.doNotGoToTheNextStep = true
        window.magentoAPI('post', 'mollie/transaction/start', {
            token: window.app.checkout.mollie
        }).then(response => {
            window.location.replace(response)
        })
    });
})
