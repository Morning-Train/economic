<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('currencies')]
#[GetSingle('currencies/:code')]
class Currency extends Resource
{
    use GetCollectionable, GetSingleable;

    public string $code;

    #[PrimaryKey]
    public string $isoNumber;

    public string $name;
}
