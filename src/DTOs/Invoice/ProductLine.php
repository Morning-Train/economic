<?php

namespace Morningtrain\Economic\DTOs\Invoice;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Properties\ApiFormatting\ResourceToArray;
use Morningtrain\Economic\Resources\DepartmentalDistribution;
use Morningtrain\Economic\Resources\Product;
use Morningtrain\Economic\Resources\Unit;

class ProductLine extends Resource
{
    // public ?Accrual $accrual; TODO - Implement Accrual

    public ?DepartmentalDistribution $departmentalDistribution;

    public ?string $description;

    public ?float $discountPercentage;

    public ?float $marginInBaseCurrency;

    public ?float $marginPercentage;

    #[ResourceToArray('productNumber', 'self')]
    public ?Product $product;

    public ?float $quantity;

    public ?int $sortKey;

    #[ResourceToArray('unitNumber', 'self')]
    public ?Unit $unit;

    public ?float $unitCostPrice;

    public ?float $unitNetPrice;

    public static function new(
        Product|int|string $product,
        float $quantity,
        float $unitNetPrice,
        ?string $description = null,
        ?float $discountPercentage = null,
        ?float $marginInBaseCurrency = null,
        ?float $marginPercentage = null,
        ?int $sortKey = null,
        ?float $unitCostPrice = null,
        DepartmentalDistribution|int|null $departmentalDistribution = null,
        Unit|int|null $unit = null,
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
            'departmentalDistribution' => $departmentalDistribution,
            'unit' => $unit,
        ]);
    }
}
