<?php

use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\DTOs\Invoice\ProductLine;
use Morningtrain\Economic\Resources\Product;
use Morningtrain\Economic\Resources\ProductGroup;
use Morningtrain\Economic\Resources\Unit;

it('allows setting product from string', function () {
    $productLine = ProductLine::new(
        product: 'test',
        quantity: 1,
        unitNetPrice: 100,
    );

    expect($productLine->product)
        ->toBeInstanceOf(Product::class)
        ->productNumber->toBe('test');
});
