# PHP SDK for E-conomic

[![Latest Version on Packagist](https://img.shields.io/packagist/v/morningtrain/economic.svg?style=flat-square)](https://packagist.org/packages/morningtrain/economic)
[![Tests](https://img.shields.io/github/actions/workflow/status/morning-train/e-conomic/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/morning-train/economic/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/morningtrain/economic.svg?style=flat-square)](https://packagist.org/packages/morningtrain/economic)

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

Some resources are divided into sub resources based on the REST API.

Every resource class is implementing function corresponding to the endpoints in the API.

Some resources are just a DTO (Data Transfer Object) that is not represented with endpoints in the REST API. These resources are used to make it easy to work with objects represented in other Resources.

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
Every resource in the e-conomic REST API is represented by a class in this SDK. And every class is implementing function corresponding to the endpoints in the API.


### Get multiple resources
When fetching multiple resources from the e-conomic REST API, you will get a collection of the resources. The collection is an implementation of the Laravel lazy collection, so you can use all the methods from the Laravel collection class on the collection.

To get multiple resources from the e-conomic REST API, you can use the `all` method on the resource class.

```php
use Morningtrain\Economic\Resources\Customer;

$customers = Customer::all();
```

#### Filtering
You can filter the resources you want to get, if the endpoint allows it. See [https://restdocs.e-conomic.com/#endpoints](https://restdocs.e-conomic.com/#endpoints) for more information about which filters every resource support. This will return af collection of resources matching the filter.

```php
use Morningtrain\Economic\Resources\Customer;

$customers = Customer::where('email', 'ms@morningtrain.dk');
```

### Get a single resource
When fetching a single resource from the e-conomic REST API, you will get an instance of the resource class you are asking for.

To get a single resource from the e-conomic REST API, you can use the `find` method on the resource class. The find method is using the primary key to find the resource. The primary key is different from resource to resource, so you need to look in the e-conomic REST API documentation to find the primary key for the resource you want to get.
The type of primary key is mixed, so you can use a string or an integer to find the resource. 

```php
use Morningtrain\Economic\Resources\Customer;
use Morningtrain\Economic\Resources\Product;

$customer = Customer::find(1); // Where 1 is the customer number

$product = Product::find('proudct-1'); // Where 'product-1' is the product number
```

### Creating a resource
Some resources can be created in E-conomic. When created succesfully you will get the resource you just created. 
We have implemented a `create` method on the resource class, so you can create a resource like this:

The parameters you need to provide when creating a resource is different from resource to resource, so you need to look in the implementation of the resource class to see which parameters you need to provide.
Some parameter is required and some is optional. You can see which parameters are required and optional in the implementation of the resource class.
To just use some of the optional parameters, you can use named parameters in PHP.

```php
use Morningtrain\Economic\Resources\Product;

$product = Product::create(
    'Product 1', // product name
    1, // product group number
    'p-1', // product number
    barCode: '1234567890', 
    costPrice: 100.0, 
    recommendedPrice: 150.0, 
    salesPrice: 199.95, 
    description: 'test', 
    unit: 1 // unit number
);
```

Some resources can be created in an alternative way with the `new` method. 
This will create a new instance of the resource class with the required properties. 
Some of these resources can then use the `save` method to create the resource in E-conomic.

```php
use Morningtrain\Economic\Resources\Invoice\DraftInvoice;

$draftInvoice = DraftInvoice::new(
    'DKK',
    1,
    new DateTime('2024-02-13T12:20:18+00:00'),
    14,
    1,
    Recipient::new(
        'John Doe',
        new VatZone(1),
    ),
    [
        ProductLine::new(
            description: 'T-shirt - Size L',
            product: new Product([
                'productNumber' => 1,
            ]),
            quantity: 1,
            unitNetPrice: 500
        )
    ]
    notes: Note::new(
        heading: 'Heading',
        textLine1: 'Text line 1',
        textLine2: 'Text line 2'
    )
);

$draftInvoice->save();
```

### Updating a resource
Some resources can be updated after creation. This can be done as follows:

```php
use Morningtrain\Economic\Resources\Customer;

$customer = new Customer::find(1); // Where 1 is the customer number

$customer->name = 'New name';

$customer->save();
```

This will update the customer name in E-conomic. You can also simply provide all the new values when instantiating the customer.

```php
use Morningtrain\Economic\Resources\Customer;

$customer = new Customer([
    'customerNumber' => 1,
    'name' => 'New Name',
]);

$customer->save();
```

### Deleting a resource
Some resources can be deleted in E-conomic. This can be done using the `delete` method on the resource class.

```php
use Morningtrain\Economic\Resources\Customer;

$customer = new Customer::find(1); // Where 1 is the customer number

$customer->delete();
```

This will delete the customer in E-conomic.

You can also delete a resource by calling the static method `deleteByPrimaryKey`.

```php
use Morningtrain\Economic\Resources\Customer;

Customer::deleteByPrimaryKey(1); // Where 1 is the customer number
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

## Testing

```bash
composer test
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
