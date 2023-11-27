<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('payment-types')]
#[GetSingle('payment-types/:paymentTypeNumber')]
class PaymentType extends Resource {
    use GetCollectionable, GetSingleable;

    public string $name;

    public int $paymentTypeNumber;
}
