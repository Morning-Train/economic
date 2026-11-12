<?php

use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Services\EconomicApiService;

it('creates a URL with exactly one slash after the base URL', function (string $endpoint) {
    expect(EconomicApiService::createURL($endpoint))
        ->toBe('https://restapi.e-conomic.com/customers/1/contacts');
})->with([
    'full URL' => 'https://restapi.e-conomic.com/customers/1/contacts',
    'relative path' => 'customers/1/contacts',
    'path with leading slash' => '/customers/1/contacts',
]);

it('requests a full URL from e-conomic without a double slash', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/customers/1/contacts', [])
        ->once()
        ->andReturn(new EconomicResponse(200, []));

    EconomicApiService::get('https://restapi.e-conomic.com/customers/1/contacts');
});

it('requests a path with a leading slash without a double slash', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/customers/1/contacts', [])
        ->once()
        ->andReturn(new EconomicResponse(200, []));

    EconomicApiService::get('/customers/1/contacts');
});
