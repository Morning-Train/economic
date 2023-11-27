<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('vat-accounts')]
#[GetSingle('vat-accounts/:id')]
class VatAccount extends Resource
{
    use GetCollectionable, GetSingleable;

    public Account $account;

    public bool $barred;

    public Account $contraAccount;

    public string $name;

    public float $ratePercentage;

    #[PrimaryKey]
    public string $vatCode;

    public VatType $vatType;
}
