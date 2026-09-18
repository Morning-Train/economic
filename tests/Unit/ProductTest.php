<?php

use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Resources\DepartmentalDistribution;
use Morningtrain\Economic\Resources\Product;
use Morningtrain\Economic\Resources\Product\Inventory;
use Morningtrain\Economic\Resources\ProductGroup;
use Morningtrain\Economic\Resources\Unit;

it('gets all products', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/products',
        [
            'pageSize' => 20,
            'skipPages' => 0,
        ]
    )
        ->andReturn(new EconomicResponse(200, [
            'collection' => [
                [
                    'productNumber' => 'p-1',
                    'name' => 'Product 1',
                    'costPrice' => 100.0,
                    'recommendedPrice' => 150.0,
                    'salesPrice' => 199.95,
                    'barred' => false,
                    'lastUpdated' => '2022-01-13T12:43:00Z',
                    'productGroup' => [
                        'productGroupNumber' => 1,
                        'name' => 'Product Group 1',
                        'self' => 'https://restapi.e-conomic.com/product-groups/1',
                    ],
                    'invoices' => [
                        'drafts' => 'https://restapi.e-conomic.com/products/p-1/invoices/drafts',
                        'booked' => 'https://restapi.e-conomic.com/products/p-1/invoices/booked',
                        'self' => 'https://restapi.e-conomic.com/products/p-1/invoices',
                    ],
                    'pricing' => [
                        'currencySpecificSalesPrices' => 'https://restapi.e-conomic.com/products/p-1/pricing/currency-specific-sales-prices',
                    ],
                    'self' => 'https://restapi.e-conomic.com/products/p-1',
                ],
                [
                    'productNumber' => '2',
                    'name' => 'Product 2',
                    'costPrice' => 50.5,
                    'recommendedPrice' => 125.0,
                    'salesPrice' => 149.95,
                    'barred' => false,
                    'lastUpdated' => '2023-01-13T12:43:00Z',
                    'productGroup' => [
                        'productGroupNumber' => 1,
                        'name' => 'Product Group 1',
                        'self' => 'https://restapi.e-conomic.com/product-groups/1',
                    ],
                    'invoices' => [
                        'drafts' => 'https://restapi.e-conomic.com/products/2/invoices/drafts',
                        'booked' => 'https://restapi.e-conomic.com/products/2/invoices/booked',
                        'self' => 'https://restapi.e-conomic.com/products/2/invoices',
                    ],
                    'pricing' => [
                        'currencySpecificSalesPrices' => 'https://restapi.e-conomic.com/products/2/pricing/currency-specific-sales-prices',
                    ],
                    'self' => 'https://restapi.e-conomic.com/products/2',
                ],
            ],
            'pagination' => [
                'maxPageSize' => 20,
                'skipPages' => 0,
                'results' => 2,
            ],
        ]));

    $products = Product::all();

    expect($products)->toBeInstanceOf(EconomicCollection::class);

    expect($products->first())
        ->toBeInstanceOf(Product::class)
        ->productNumber->toBe('p-1')
        ->name->toBe('Product 1')
        ->costPrice->toBe(100.0)
        ->recommendedPrice->toBe(150.0)
        ->salesPrice->toBe(199.95)
        ->barred->toBeFalse()
        ->lastUpdated->toBeInstanceOf(DateTime::class)
        ->productGroup->toBeInstanceOf(ProductGroup::class)
        ->invoices->toBeArray()
        ->self->toBe('https://restapi.e-conomic.com/products/p-1')
        ->productGroup->productGroupNumber->toBe(1)
        ->productGroup->name->toBe('Product Group 1')
        ->productGroup->self->toBe('https://restapi.e-conomic.com/product-groups/1');
});

it('gets a specific product', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/products/p-1',
        []
    )
        ->andReturn(new EconomicResponse(200, [
            'productNumber' => 'p-1',
            'name' => 'Product 1',
            'costPrice' => 100.0,
            'recommendedPrice' => 150.0,
            'salesPrice' => 199.95,
            'barred' => false,
            'lastUpdated' => '2022-01-13T12:43:00Z',
            'departmentalDistribution' => [
                'departmentalDistributionNumber' => 1,
                'distributionType' => 'department',
                'self' => 'https://restapi.e-conomic.com/departmental-distributions/1',
            ],
            'inventory' => [
                'available' => 10.0,
                'inStock' => 12.0,
                'inventoryLastUpdated' => '2022-01-13T12:43:00Z',
                'recommendedCostPrice' => 75.0,
            ],
            'productGroup' => [
                'productGroupNumber' => 1,
                'name' => 'Product Group 1',
                'self' => 'https://restapi.e-conomic.com/product-groups/1',
            ],
            'invoices' => [
                'drafts' => 'https://restapi.e-conomic.com/products/p-1/invoices/drafts',
                'booked' => 'https://restapi.e-conomic.com/products/p-1/invoices/booked',
                'self' => 'https://restapi.e-conomic.com/products/p-1/invoices',
            ],
            'pricing' => [
                'currencySpecificSalesPrices' => 'https://restapi.e-conomic.com/products/p-1/pricing/currency-specific-sales-prices',
            ],
            'self' => 'https://restapi.e-conomic.com/products/p-1',
        ]));

    $product = Product::find('p-1');

    expect($product)
        ->departmentalDistribution->toBeInstanceOf(DepartmentalDistribution::class)
        ->departmentalDistribution->departmentalDistributionNumber->toBe(1)
        ->departmentalDistribution->distributionType->toBe('department')
        ->inventory->toBeInstanceOf(Inventory::class)
        ->inventory->available->toBe(10.0)
        ->inventory->inStock->toBe(12.0)
        ->inventory->recommendedCostPrice->toBe(75.0)
        ->inventory->inventoryLastUpdated->toBeInstanceOf(DateTime::class);

    expect($product)->toBeInstanceOf(Product::class)
        ->productNumber->toBe('p-1')
        ->name->toBe('Product 1')
        ->costPrice->toBe(100.0)
        ->recommendedPrice->toBe(150.0)
        ->salesPrice->toBe(199.95)
        ->barred->toBeFalse()
        ->lastUpdated->toBeInstanceOf(DateTime::class)
        ->productGroup->toBeInstanceOf(ProductGroup::class)
        ->invoices->toBeArray()
        ->self->toBe('https://restapi.e-conomic.com/products/p-1')
        ->productGroup->productGroupNumber->toBe(1)
        ->productGroup->name->toBe('Product Group 1')
        ->productGroup->self->toBe('https://restapi.e-conomic.com/product-groups/1');
});

// it('gets a product using filters'); // TODO: setup test

it('creates a product', function () {
    $this->driver->expects()->post()
        ->with('https://restapi.e-conomic.com/products', fixture('Products/create-request'), null)
        ->andReturn(new EconomicResponse(201, fixture('Products/create-response')));

    $product = Product::create('Product 1', 1, 'p-1', barCode: '1234567890', costPrice: 100.0, recommendedPrice: 150.0, salesPrice: 199.95, description: 'test', unit: 1);

    expect($product)->toBeInstanceOf(Product::class)
        ->productNumber->toBe('p-1')
        ->name->toBe('Product 1')
        ->costPrice->toBe(100.0)
        ->recommendedPrice->toBe(150.0)
        ->salesPrice->toBe(199.95)
        ->barCode->toBe('1234567890')
        ->barred->toBeFalse()
        ->lastUpdated->toBeInstanceOf(DateTime::class)
        ->productGroup->toBeInstanceOf(ProductGroup::class)
        ->unit->toBeInstanceOf(Unit::class)
        ->invoices->toBeArray()
        ->self->toBe('https://restapi.e-conomic.com/products/p-1')
        ->productGroup->productGroupNumber->toBe(1)
        ->productGroup->name->toBe('Product Group 1')
        ->productGroup->self->toBe('https://restapi.e-conomic.com/product-groups/1')
        ->unit->unitNumber->toBe(1)
        ->unit->name->toBe('Piece')
        ->unit->self->toBe('https://restapi.e-conomic.com/units/1')
        ->departmentalDistribution->toBeInstanceOf(DepartmentalDistribution::class)
        ->departmentalDistribution->departmentalDistributionNumber->toBe(1)
        ->inventory->toBeInstanceOf(Inventory::class)
        ->inventory->inStock->toBe(12.0)
        ->inventory->packageVolume->toBe(1.5)
        ->pricing->toBeArray()
        ->metaData->toBeArray()
        ->productGroup->products->toBe('https://restapi.e-conomic.com/product-groups/1/products')
        ->productGroup->salesAccounts->toBe('https://restapi.e-conomic.com/product-groups/1/sales-accounts');
});

// it('updates a product'); // TODO: setup test

// it('deletes a product'); // TODO: setup test

it('does not log about unknown properties when e-conomic returns a full product', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/products/p-1',
        []
    )
        ->andReturn(new EconomicResponse(200, fixture('Products/create-response')));

    $logs = economicLogs(function () {
        Product::find('p-1');
    });

    expect($logs)->toBeEmpty();
});

it('hydrates a product that has no optional objects', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/products/p-1',
        []
    )
        ->andReturn(new EconomicResponse(200, [
            'productNumber' => 'p-1',
            'name' => 'Product 1',
            'self' => 'https://restapi.e-conomic.com/products/p-1',
        ]));

    $logs = economicLogs(function () use (&$product) {
        $product = Product::find('p-1');
    });

    expect($logs)->toBeEmpty();

    // Unset typed properties must not break serialisation - they are simply left out
    expect($product->toArray())
        ->not->toHaveKey('departmentalDistribution')
        ->not->toHaveKey('inventory')
        ->not->toHaveKey('pricing')
        ->not->toHaveKey('metaData')
        ->toHaveKey('productNumber', 'p-1');

    expect(fn () => json_encode($product))->not->toThrow(Throwable::class);
});

it('sends the inventory when creating a product', function () {
    $this->driver->expects()->post()
        ->with(
            'https://restapi.e-conomic.com/products',
            fixture('Products/create-request-with-inventory'),
            null
        )
        ->andReturn(new EconomicResponse(201, fixture('Products/create-response')));

    $product = Product::create(
        'Product 1',
        1,
        'p-1',
        inventory: new Inventory([
            'packageVolume' => 1.5,
            'recommendedCostPrice' => 75.0,
        ]),
    );

    expect($product)->toBeInstanceOf(Product::class)
        ->inventory->toBeInstanceOf(Inventory::class);
});

it('accepts a plain object as the inventory when creating a product', function () {
    $inventory = new stdClass;
    $inventory->packageVolume = 1.5;
    $inventory->recommendedCostPrice = 75.0;

    $this->driver->expects()->post()
        ->with(
            'https://restapi.e-conomic.com/products',
            fixture('Products/create-request-with-inventory'),
            null
        )
        ->andReturn(new EconomicResponse(201, fixture('Products/create-response')));

    $product = Product::create('Product 1', 1, 'p-1', inventory: $inventory);

    expect($product)->toBeInstanceOf(Product::class)
        ->inventory->toBeInstanceOf(Inventory::class);
});

it('hydrates a resource from a plain object', function () {
    $data = new stdClass;
    $data->packageVolume = 1.5;
    $data->inStock = 12.0;

    $logs = economicLogs(function () use ($data, &$inventory) {
        $inventory = new Inventory($data);
    });

    expect($logs)->toBeEmpty();

    expect($inventory)
        ->packageVolume->toBe(1.5)
        ->inStock->toBe(12.0);
});
