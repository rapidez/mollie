# Rapidez Mollie

## Requirements

You need to have the Mollie Magento 2 module installed and configured within your Magento 2 installation.

## Installation

```bash
composer require rapidez/mollie
```

### Configuration

> If you're working locally and your environment isn't accessible from the web you need to disable the webhook setting as Mollie can't reach your environment. This results in getting the "pending payment" page after each payment.

### Views

You can publish the views with:
```bash
php artisan vendor:publish --provider="Rapidez\Mollie\MollieServiceProvider" --tag=views
```

## License

GNU General Public License v3. Please see [License File](LICENSE) for more information.
