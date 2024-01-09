# PHP SDK for E-conomic

[![Latest Version on Packagist](https://img.shields.io/packagist/v/morning-train/e-conomic.svg?style=flat-square)](https://packagist.org/packages/morning-train/e-conomic)
[![Tests](https://img.shields.io/github/actions/workflow/status/morning-train/e-conomic/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/morning-train/e-conomic/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/morning-train/e-conomic.svg?style=flat-square)](https://packagist.org/packages/morning-train/e-conomic)

## Installation

You can install the package via composer:

```bash
composer require morningtrain/economic
```

## Usage

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
