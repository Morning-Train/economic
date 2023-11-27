<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('vat-zones')]
#[GetSingle('vat-zones/:vatZoneNumber')]
class VatZone extends Resource
{
    use GetCollectionable, GetSingleable;

    /**
     * If true, the VAT zone can be used for customers
     * @var bool
     */
    public bool $enabledForCustomer;

    /**
     * If true, the VAT zone can be used for suppliers
     * @var bool
     */
    public bool $enabledForSupplier;

    public string $name;

    #[PrimaryKey]
    public int $vatZoneNumber;
}
