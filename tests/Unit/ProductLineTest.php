<?php

use Morningtrain\Economic\DTOs\Invoice\ProductLine;
use Morningtrain\Economic\Resources\Product;

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
