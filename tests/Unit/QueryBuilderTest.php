<?php

use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicCollectionIterator;
use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Resources\Customer;

function customerCollectionResponse(array $customerNumbers, int $results): EconomicResponse
{
    return new EconomicResponse(200, [
        'collection' => array_map(fn (int $customerNumber) => [
            'customerNumber' => $customerNumber,
            'name' => "Customer $customerNumber",
        ], $customerNumbers),
        'pagination' => [
            'results' => $results,
        ],
    ]);
}

describe('all()', function () {
    it('sends the filter as a query argument', function () {
        $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
            'filter' => 'customerNumber$eq:12345$or:(customerNumber$eq:22222)',
            'skipPages' => 0,
            'pageSize' => 20,
        ])
            ->once()
            ->andReturn(customerCollectionResponse([12345, 22222], 2));

        $customers = Customer::where('customerNumber', 12345)
            ->orWhere('customerNumber', 22222)
            ->all();

        expect($customers->all())->toHaveCount(2)
            ->and($customers->getSelf())->toBe('customers?filter=customerNumber%24eq%3A12345%24or%3A%28customerNumber%24eq%3A22222%29');
    });

    it('keeps the filter on the next page', function () {
        $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
            'filter' => 'customerNumber$gt:100',
            'skipPages' => 0,
            'pageSize' => 20,
        ])
            ->once()
            ->andReturn(customerCollectionResponse(range(101, 120), 25));

        $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
            'filter' => 'customerNumber$gt:100',
            'skipPages' => 1,
            'pageSize' => 20,
        ])
            ->once()
            ->andReturn(customerCollectionResponse(range(121, 125), 25));

        $customers = Customer::where('customerNumber', '>', 100)->all()->all();

        expect($customers)->toHaveCount(25)
            ->and($customers[24]->customerNumber)->toBe(125);
    });

    it('sends e-conomic escapes unchanged', function () {
        $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
            'filter' => 'name$eq:A$, B $(C$) $*$[D$] $$',
            'skipPages' => 0,
            'pageSize' => 20,
        ])
            ->once()
            ->andReturn(customerCollectionResponse([], 0));

        Customer::where('name', 'A, B (C) *[D] $')->all()->all();
    });
});

describe('a collection from a URL', function () {
    it('keeps the query string of the URL and pages on its own', function () {
        $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
            'sort' => 'name',
            'skipPages' => 0,
            'pageSize' => 20,
        ])
            ->once()
            ->andReturn(customerCollectionResponse([1], 1));

        $collection = new EconomicCollection(new EconomicCollectionIterator(
            'customers?sort=name&skippages=3&pageSize=5',
            Customer::class
        ));

        expect($collection->all())->toHaveCount(1)
            ->and($collection->getSelf())->toBe('customers?sort=name&skippages=3&pageSize=5');
    });

    it('sends a URL without a query string as it is', function () {
        $this->driver->expects()->get('https://restapi.e-conomic.com/customers/1/contacts', [
            'skipPages' => 0,
            'pageSize' => 20,
        ])
            ->once()
            ->andReturn(new EconomicResponse(200, ['collection' => [], 'pagination' => ['results' => 0]]));

        $collection = new EconomicCollection(new EconomicCollectionIterator(
            'customers/1/contacts',
            Customer::class
        ));

        expect($collection->all())->toBeEmpty()
            ->and($collection->getSelf())->toBe('customers/1/contacts');
    });
});
