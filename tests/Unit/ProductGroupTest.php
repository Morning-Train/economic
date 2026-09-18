<?php

use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Resources\Account;
use Morningtrain\Economic\Resources\ProductGroup;

it('gets all product groups', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/product-groups',
        [
            'pageSize' => 20,
            'skipPages' => 0,
        ]
    )
        ->andReturn(new EconomicResponse(200, [
            'collection' => [
                [
                    'productGroupNumber' => 1,
                    'name' => 'Product Group 1',
                    'salesAccounts' => 'https://restapi.e-conomic.com/product-groups/1/sales-accounts',
                    'products' => 'https://restapi.e-conomic.com/product-groups/1/products',
                    'self' => 'https://restapi.e-conomic.com/product-groups/1',
                ],
                [
                    'productGroupNumber' => 2,
                    'name' => 'Product Group 2',
                    'salesAccounts' => 'https://restapi.e-conomic.com/product-groups/2/sales-accounts',
                    'products' => 'https://restapi.e-conomic.com/product-groups/2/products',
                    'self' => 'https://restapi.e-conomic.com/product-groups/2',
                ],
            ],
            'pagination' => [
                'maxPageSize' => 20,
                'skipPages' => 0,
                'results' => 2,
            ],
        ]));

    $productGroups = ProductGroup::all();

    expect($productGroups)->toBeInstanceOf(EconomicCollection::class);

    expect($productGroups->first())
        ->toBeInstanceOf(ProductGroup::class)
        ->productGroupNumber->toBe(1)
        ->name->toBe('Product Group 1')
        ->self->toBe('https://restapi.e-conomic.com/product-groups/1');

    expect($productGroups->all())->toHaveCount(2);
});

it('gets a specific product group', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/product-groups/1',
        []
    )
        ->andReturn(new EconomicResponse(200, [
            'productGroupNumber' => 1,
            'name' => 'Product Group 1',
            'salesAccounts' => 'https://restapi.e-conomic.com/product-groups/1/sales-accounts',
            'products' => 'https://restapi.e-conomic.com/product-groups/1/products',
            'self' => 'https://restapi.e-conomic.com/product-groups/1',
        ]));

    $vatZone = ProductGroup::find(1);

    expect($vatZone)->toBeInstanceOf(ProductGroup::class)
        ->productGroupNumber->toBe(1)
        ->name->toBe('Product Group 1')
        ->self->toBe('https://restapi.e-conomic.com/product-groups/1');
});

it('hydrates the accrual account of a product group without logging', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/product-groups/1',
        []
    )
        ->andReturn(new EconomicResponse(200, [
            'productGroupNumber' => 1,
            'name' => 'Product Group 1',
            'inventoryEnabled' => false,
            'salesAccounts' => 'https://restapi.e-conomic.com/product-groups/1/sales-accounts',
            'products' => 'https://restapi.e-conomic.com/product-groups/1/products',
            'accrual' => [
                'accountNumber' => 1000,
                'accountType' => 'status',
                'name' => 'Periodisering',
                'balance' => 1234.56,
                'barred' => false,
                'blockDirectEntries' => false,
                'debitCredit' => 'debit',
                'draftBalance' => 0.0,
                'accountingYears' => 'https://restapi.e-conomic.com/accounts/1000/accounting-years',
                'self' => 'https://restapi.e-conomic.com/accounts/1000',
            ],
            'self' => 'https://restapi.e-conomic.com/product-groups/1',
        ]));

    $logs = economicLogs(function () use (&$productGroup) {
        $productGroup = ProductGroup::find(1);
    });

    expect($logs)->toBeEmpty();

    expect($productGroup)
        ->accrual->toBeInstanceOf(Account::class)
        ->accrual->accountNumber->toBe(1000)
        ->accrual->balance->toBe(1234.56)
        ->accrual->accountingYears->toBe('https://restapi.e-conomic.com/accounts/1000/accounting-years');
});
