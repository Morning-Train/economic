<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

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
