<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('product-groups')] // https://restdocs.e-conomic.com/#get-product-groups
#[GetSingle('product-groups/:productGroupNumber', ':productGroupNumber')] // https://restdocs.e-conomic.com/#get-product-groups-productgroupnumber
class ProductGroup extends Resource
{
    /**
     * @use GetCollectionable<ProductGroup>
     */
    use GetCollectionable, GetSingleable;

    // public ?object $accrual; // TODO: implement

    public ?bool $inventoryEnabled;

    public ?string $name;

    #[PrimaryKey]
    public ?int $productGroupNumber;

    // public ?string $products; // TODO: implement

    // public ?string $salesAccounts; // TODO: implement

}
