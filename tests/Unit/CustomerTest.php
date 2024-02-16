<?php

use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Resources\Customer;
use Morningtrain\Economic\Resources\CustomerGroup;
use Morningtrain\Economic\Resources\PaymentTerm;
use Morningtrain\Economic\Resources\VatZone;

it('Filters by name', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
        'pageSize' => 1,
        'skipPages' => 0,
        'filter' => 'name$eq:Morningtrain',
    ])
        ->once()
        ->andReturn(new EconomicResponse(200, []));

    Customer::where('name', 'Morningtrain')->first();
});

it('Handles multiple where filters', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
        'pageSize' => 1,
        'skipPages' => 0,
        'filter' => 'email$eq:ms@morningtrain.dk$and:name$eq:Morningtrain',
    ])
        ->once()
        ->andReturn(new EconomicResponse(200, []));

    Customer::where('email', 'ms@morningtrain.dk')
        ->where('name', 'Morningtrain')
        ->first();
});

it('Handles OR filters', function () {
    $this->driver->shouldReceive('get')
        ->once()
        ->with('https://restapi.e-conomic.com/customers', [
            'pageSize' => 1,
            'skipPages' => 0,
            'filter' => 'email$eq:ms@morningtrain.dk$or:(name$eq:Morningtrain)',
        ])
        ->andReturn(new EconomicResponse(200, []));

    Customer::where('email', 'ms@morningtrain.dk')
        ->orWhere('name', 'Morningtrain')
        ->first();
});

it('creates customer', function () {
    $this->driver->expects()->post()
        ->withArgs(function (string $url, array $body) {
            return $url === 'https://restapi.e-conomic.com/customers'
                && $body === [
                    'name' => 'John Doe',
                    'customerGroup' => [
                        'customerGroupNumber' => 1,
                    ],
                    'currency' => 'DKK',
                    'vatZone' => [
                        'vatZoneNumber' => 1,
                    ],
                    'paymentTerms' => [
                        'paymentTermsNumber' => 1,
                    ],
                ];
        })
        ->once()
        ->andReturn(new EconomicResponse(201, fixture('Customers/create')));

    $customer = Customer::create(
        name: 'John Doe',
        customerGroup: 1,
        currency: 'DKK',
        vatZone: 1,
        paymentTerms: 1,
    );

    expect($customer)
        ->toBeInstanceOf(Customer::class)
        ->customerNumber->toBe(1)
        ->name->toBe('John Doe')
        ->customerGroup->toBeInstanceOf(CustomerGroup::class)
        ->customerGroup->customerGroupNumber->toBe(1)
        ->currency->toBe('DKK')
        ->vatZone->toBeInstanceOf(VatZone::class)
        ->vatZone->vatZoneNumber->toBe(1)
        ->paymentTerms->toBeInstanceOf(PaymentTerm::class)
        ->paymentTerms->paymentTermsNumber->toBe(1);
});

it('can update a customer', function () {
    $this->driver->expects()->put()
        ->withArgs(function (string $url, array $body) {
            return $url === 'https://restapi.e-conomic.com/customers/1'
                && $body === [
                    'customerNumber' => 1,
                    'name' => 'John Doe Renamed',
                    'self' => 'https://restapi.e-conomic.com/customers/1',
                ];
        })
        ->once()
        ->andReturn(new EconomicResponse(200, fixture('Customers/update')));

    $customer = new Customer([
        'customerNumber' => 1,
        'name' => 'John Doe Renamed',
    ]);

    $updatedCustomer = $customer->save();

    expect($updatedCustomer)
        ->toBeInstanceOf(Customer::class)
        ->customerNumber->toBe(1)
        ->name->toBe('John Doe Renamed');
});

it('filters null values', function () {
    $this->driver->expects()->post()
        ->withArgs(function (string $url, array $body) {
            return $url === 'https://restapi.e-conomic.com/customers'
                && $body === [
                    'name' => 'John Doe',
                    'customerGroup' => [
                        'customerGroupNumber' => 1,
                    ],
                    'currency' => 'DKK',
                    'vatZone' => [
                        'vatZoneNumber' => 1,
                    ],
                    'paymentTerms' => [
                        'paymentTermsNumber' => 1,
                    ],
                ];
        })
        ->once()
        ->andReturn(new EconomicResponse(201, fixture('Customers/create')));

    Customer::create(
        name: 'John Doe',
        customerGroup: 1,
        currency: 'DKK',
        vatZone: 1,
        paymentTerms: 1,
        email: null // Should not be present in request
    );
});

it('does not filter falsy values', function () {
    $this->driver->expects()->post()
        ->withArgs(function (string $url, array $body) {
            return $url === 'https://restapi.e-conomic.com/customers'
                && $body === [
                    'name' => 'John Doe',
                    'customerGroup' => [
                        'customerGroupNumber' => 1,
                    ],
                    'currency' => 'DKK',
                    'vatZone' => [
                        'vatZoneNumber' => 1,
                    ],
                    'paymentTerms' => [
                        'paymentTermsNumber' => 1,
                    ],
                    'customerNumber' => 0, // Should be present in request
                ];
        })
        ->once()
        ->andReturn(new EconomicResponse(201, fixture('Customers/create')));

    Customer::create(
        name: 'John Doe',
        customerGroup: 1,
        currency: 'DKK',
        vatZone: 1,
        paymentTerms: 1,
        customerNumber: 0,
    );
});
