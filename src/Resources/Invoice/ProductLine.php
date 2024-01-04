<?php

namespace Morningtrain\Economic\Resources\Invoice;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Resources\Product;

class ProductLine extends Resource
{
    public ?string $description;

    public ?float $discountPercentage;

    public ?float $marginInBaseCurrency;

    public ?float $marginPercentage;

    public ?Product $product;

    public ?float $quantity;

    public ?int $sortKey;

    public ?float $unitCostPrice;

    public ?float $unitNetPrice;

    public static function new(
        Product|int $product,
        float $quantity,
        float $unitNetPrice,
        ?string $description = null,
        ?float $discountPercentage = null,
        ?float $marginInBaseCurrency = null,
        ?float $marginPercentage = null,
        ?int $sortKey = null,
        ?float $unitCostPrice = null,
    ): static {
        return new static([
            'product' => $product,
            'quantity' => $quantity,
            'unitNetPrice' => $unitNetPrice,
            'description' => $description,
            'discountPercentage' => $discountPercentage,
            'marginInBaseCurrency' => $marginInBaseCurrency,
            'marginPercentage' => $marginPercentage,
            'sortKey' => $sortKey,
            'unitCostPrice' => $unitCostPrice,
        ]);
    }
}
