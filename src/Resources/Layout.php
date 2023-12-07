<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('layouts')]
#[GetSingle('/layouts/:layoutNumber', ':layoutNumber')]
class Layout extends Resource
{
    use GetCollectionable;
    use GetSingleable;

    public bool $deleted;

    #[PrimaryKey]
    public int $layoutNumber;

    public string $name;
}
