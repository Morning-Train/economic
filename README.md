# PHP SDK for E-conomic

[![Latest Version on Packagist](https://img.shields.io/packagist/v/morning-train/e-conomic.svg?style=flat-square)](https://packagist.org/packages/morningtrain/economic)
[![Tests](https://img.shields.io/github/actions/workflow/status/morning-train/e-conomic/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/morning-train/economic/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/morning-train/e-conomic.svg?style=flat-square)](https://packagist.org/packages/morningtrain/economic)

## Installation

You can install the package via composer:

```bash
composer require morningtrain/economic
```

## Basic Concepts
This SDK is built to make it simple to handle and use the e-conomic REST API.
You can read the e-conomic REST API documentation here: [https://restdocs.e-conomic.com/](https://restdocs.e-conomic.com/)

### Driver
The SDK uses a driver to handle the communication with the e-conomic REST API.
We have not implemented a driver in this package, since we want to make it possible to use the SDK with different frameworks.

You are free to use some of our implementations, or make your own driver:

| Framework | Git Repo                                                                            | Composer Package                                                                              |
|-----------|-------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------|
| Laravel   | [Morning-Train/laravel-economic](https://github.com/Morning-Train/laravel-economic) | [morningtrain/laravel-economic](https://packagist.org/packages/morningtrain/laravel-economic) |
| WordPress | [Morning-Train/wp-economic](https://github.com/Morning-Train/wp-economic)           | [morningtrain/wp-economic](https://packagist.org/packages/morningtrain/wp-economic)           |

If you make your own driver, you must implement the `Morningtrain\Economic\Interfaces\EconomicDriver` interface, and initialize the API like this:

```php
use Morningtrain\Economic\EconomicApi;

EconomicApiService::setDriver(new YourDriver($appSecretToken, $agreementGrantToken));
```

### Loggers
We have implemented a PSR logger interface, so you can log all the requests and responses from the e-conomic REST API.

You can register a PSR logger by calling the `registerLogger` method on the `Morningtrain\Economic\Services\EconomicLoggerService` class.

### Resources
Every resource in the e-conomic REST API is represented by a class in this SDK. 
The resources are located in the `src/Resources` folder.

### Collections
Collections are used to fetch multiple resources from the e-conomic REST API.
We make use of Laravels lazy collections to make it easy to work with the collection.
This means, that you can use all the methods from the Laravel collection class on the collections returned from the SDK.
The lazy collection will automatically get the resources you need and handle pagination for you. The API is only called when you need the resources, so the collection will not contain data before you need it.

### Filtering
When fetching resources from the e-conomic REST API, you can filter the resources you want to get, if the endpoint allows it. See [https://restdocs.e-conomic.com/#endpoints](https://restdocs.e-conomic.com/#endpoints) for more information about which filters every resource support.
The SDK has a simple way to filter resources, using af query builder like syntax.

A simple example could be:
```php
use Morningtrain\Economic\Resources\Customer;

$customer = Customer::where('email', 'ms@morningtrain.dk')
                ->orWhere('name', 'Morningtrain')
                ->first();
```

### Sorting
Is not yet implemented.

## Usage

### Get multiple resources

### Get a single resource

### Creating a resource

### Updating a resource
Some resources can be updated after creation. This can be done as follows:

```php
$customer = new \Morningtrain\Economic\Resources\Customer([
    'customerNumber' => 1,
]);

$customer->name = 'New name';

$customer->save();
```

This will update the customer name in E-conomic. You can also simply provide all the new values when instantiating the customer.

```php
$customer = new \Morningtrain\Economic\Resources\Customer([
    'customerNumber' => 1,
    'name' => 'New Name',
]);

$customer->save();
```

## Examples

### Get all customers

```php
use Morningtrain\Economic\Resources\Customer;

$customers = Customer::all();

foreach ($customers as $customer) {
    echo $customer->name;
}
```

### Get a single customer

```php
use Morningtrain\Economic\Resources\Customer;

$customer = Customer::find(1); // Where 1 is the customer number

if(!empty($customer)) {
    echo $customer->name;
}
```

### Get a customer by email

```php
use Morningtrain\Economic\Resources\Customer;

$customer = Customer::where('email', 'ms@morningtrain.dk');

if(!empty($customer)) {
    echo $customer->name;
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Martin Schadegg Brønniche](https://github.com/mschadegg)
- [Simon Jønsson](https://github.com/Morning-Train)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


---

<div align="center">
Developed by <br>
</div>
<br>
<div align="center">
<a href="https://morningtrain.dk" target="_blank">
<img src="https://morningtrain.dk/wp-content/themes/mtt-wordpress-theme/assets/img/logo-only-text.svg" width="200" alt="Morningtrain logo">
</a>
</div>
