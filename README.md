# Rapidez Mollie

## Requirements

You need to have the Mollie Magento 2 module installed and configured within your Magento 2 installation.

## Installation

```
composer require rapidez/mollie
```
And add the JS to `resources/js/app.js`:
```
require('Vendor/rapidez/mollie/resources/js/mollie')
```

### Configuration

You need to enable and set a custom return url in the Magento 2 configuration:
```
https://yourdomain.com/mollie-return/{{ORDER_HASH}}
```

> If you're working locally and your environment isn't accessible from the web you need to disable the webhook setting as Mollie can't reach your environment. This results in getting the "pending payment" page after each payment.

### Views

You can publish the views with:
```
php artisan vendor:publish --provider="Rapidez\Mollie\MollieServiceProvider" --tag=views
```
