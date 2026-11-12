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

describe('in and not in', function () {
    it('sends a list with first()', function () {
        $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
            'pageSize' => 1,
            'skipPages' => 0,
            'filter' => 'customerNumber$in:[1,2]',
        ])
            ->once()
            ->andReturn(new EconomicResponse(200, []));

        Customer::where('customerNumber', 'in', [1, 2])->first();
    });

    it('sends a list with all()', function () {
        $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
            'filter' => 'customerNumber$in:[1,2]',
            'skipPages' => 0,
            'pageSize' => 20,
        ])
            ->once()
            ->andReturn(customerCollectionResponse([1], 1));

        expect(Customer::where('customerNumber', 'in', [1, 2])->all()->all())->toHaveCount(1);
    });

    it('converts the operator', function (string $operator, string $filter) {
        $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
            'pageSize' => 1,
            'skipPages' => 0,
            'filter' => $filter,
        ])
            ->once()
            ->andReturn(new EconomicResponse(200, []));

        Customer::where('customerNumber', $operator, [1, 2])->first();
    })->with([
        ['in', 'customerNumber$in:[1,2]'],
        ['IN', 'customerNumber$in:[1,2]'],
        ['$in:', 'customerNumber$in:[1,2]'],
        ['not in', 'customerNumber$nin:[1,2]'],
        ['NOT IN', 'customerNumber$nin:[1,2]'],
        ['$nin:', 'customerNumber$nin:[1,2]'],
    ]);

    it('works in orWhere() and a nested where()', function () {
        $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
            'pageSize' => 1,
            'skipPages' => 0,
            'filter' => 'name$eq:A$or:(customerNumber$in:[1,2])$and:customerNumber$nin:[3]',
        ])
            ->once()
            ->andReturn(new EconomicResponse(200, []));

        Customer::where('name', 'A')
            ->orWhere('customerNumber', 'in', [1, 2])
            ->where(fn ($query) => $query->where('customerNumber', 'not in', [3]))
            ->first();
    });

    it('sends a list of 200 integers', function () {
        $numbers = range(1, 200);

        $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
            'pageSize' => 1,
            'skipPages' => 0,
            'filter' => 'customerNumber$in:['.implode(',', $numbers).']',
        ])
            ->once()
            ->andReturn(new EconomicResponse(200, []));

        Customer::where('customerNumber', 'in', $numbers)->first();
    });

    it('throws before e-conomic is called', function (string $operator, mixed $value) {
        $this->driver->shouldNotReceive('get');

        expect(fn () => Customer::where('customerNumber', $operator, $value)->all()->all())
            ->toThrow(InvalidArgumentException::class);
    })->with([
        'an empty list' => ['in', []],
        '201 integers' => ['in', range(1, 201)],
        'a numeric string' => ['in', [1, '10']],
        'a float' => ['in', [1, 1.5]],
        'null' => ['not in', [1, null]],
        'a string instead of a list' => ['in', '[1,2]'],
        'no value' => ['in', null],
        'a list with =' => ['=', [1, 2]],
    ]);

    it('throws on a list without an operator', function () {
        $this->driver->shouldNotReceive('get');

        expect(fn () => Customer::where('customerNumber', [1, 2])->first())
            ->toThrow(InvalidArgumentException::class);
    });
});
