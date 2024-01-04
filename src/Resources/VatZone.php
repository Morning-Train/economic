<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('vat-zones')]
#[GetSingle('vat-zones/:vatZoneNumber')]
class VatZone extends Resource
{
    use GetCollectionable, GetSingleable;

    /**
     * If true, the VAT zone can be used for customers
     */
    public bool $enabledForCustomer;

    /**
     * If true, the VAT zone can be used for suppliers
     */
    public bool $enabledForSupplier;

    public string $name;

    #[PrimaryKey]
    public int $vatZoneNumber;
}
