document.addEventListener('turbolinks:load', () => {
    window.app.$on('CheckoutCredentialsSaved', () => {
        window.app.magentoCart('get', 'mollie/payment-token').then(response => {
            window.app.checkout.mollie = response.data
        })
    });

    window.app.$on('CheckoutPaymentSaved', () => {
        window.app.checkout.doNotGoToTheNextStep = true
        window.magento.post('mollie/transaction/start', {
            token: window.app.checkout.mollie
        }).then(response => {
            window.location.replace(response.data)
        })
    });
})
