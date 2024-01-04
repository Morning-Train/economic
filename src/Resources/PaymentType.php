<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('payment-types')]
#[GetSingle('payment-types/:paymentTypeNumber')]
class PaymentType extends Resource
{
    use GetCollectionable, GetSingleable;

    public string $name;

    public int $paymentTypeNumber;
}
