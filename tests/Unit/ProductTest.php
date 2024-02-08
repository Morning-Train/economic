<?php

use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Resources\Product;
use Morningtrain\Economic\Resources\ProductGroup;
use Morningtrain\Economic\Resources\Unit;

it('gets all products', function() {
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
                        'currencySpecificSalesPrices' => 'https://restapi.e-conomic.com/products/p-1/pricing/currency-specific-sales-prices'
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
                        'currencySpecificSalesPrices' => 'https://restapi.e-conomic.com/products/2/pricing/currency-specific-sales-prices'
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
                'currencySpecificSalesPrices' => 'https://restapi.e-conomic.com/products/p-1/pricing/currency-specific-sales-prices'
            ],
            'self' => 'https://restapi.e-conomic.com/products/p-1',
        ]));

    $product = Product::find('p-1');

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

it('creates a product', function() {
    $this->driver->expects()->post()
        ->with('https://restapi.e-conomic.com/products', [
            'name' => 'Product 1',
            'productGroup' => [
                'productGroupNumber' => 1,
            ],
            'productNumber' => 'p-1',
            'barCode' => '1234567890',
            'costPrice' => 100,
            'description' => 'test',
            'recommendedPrice' => 150,
            'salesPrice' => 199.95,
            'unit' => [
                'unitNumber' => 1,
            ],
        ])
        ->andReturn(new EconomicResponse(201, [
            'productNumber' => 'p-1',
            'description' => 'test',
            'name' => 'Product 1',
            'costPrice' => 100.0,
            'recommendedPrice' => 150.0,
            'salesPrice' => 199.95,
            'barCode' => '1234567890',
            'barred' => false,
            'lastUpdated' => '2022-01-13T12:43:00Z',
            'productGroup' => [
                'productGroupNumber' => 1,
                'name' => 'Product Group 1',
                'self' => 'https://restapi.e-conomic.com/product-groups/1',
            ],
            'unit' => [
                'unitNumber' => 1,
                'name' => 'Piece',
                'self' => 'https://restapi.e-conomic.com/units/1',
            ],
            'invoices' => [
                'drafts' => 'https://restapi.e-conomic.com/products/p-1/invoices/drafts',
                'booked' => 'https://restapi.e-conomic.com/products/p-1/invoices/booked',
                'self' => 'https://restapi.e-conomic.com/products/p-1/invoices',
            ],
            'pricing' => [
                'currencySpecificSalesPrices' => 'https://restapi.e-conomic.com/products/p-1/pricing/currency-specific-sales-prices'
            ],
            'self' => 'https://restapi.e-conomic.com/products/p-1',
        ]));

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
        ->unit->self->toBe('https://restapi.e-conomic.com/units/1');
});

// it('updates a product'); // TODO: setup test

// it('deletes a product'); // TODO: setup test


