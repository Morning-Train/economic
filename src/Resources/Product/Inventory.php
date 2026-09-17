<?php

namespace Morningtrain\Economic\Resources\Product;

use DateTime;
use Morningtrain\Economic\Abstracts\Resource;

/**
 * The inventory information of a Product
 * https://restdocs.e-conomic.com/#get-products
 */
class Inventory extends Resource
{
    public ?float $available;

    public ?float $grossWeight;

    public ?float $inStock;

    public ?DateTime $inventoryLastUpdated;

    public ?float $netWeight;

    public ?float $orderedByCustomers;

    public ?float $orderedFromSuppliers;

    public ?float $packageVolume;

    public ?float $recommendedCostPrice;
}
