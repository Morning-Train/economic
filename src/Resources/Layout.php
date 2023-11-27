<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;

#[GetCollection('layouts')]
#[GetSingle('/layouts/:layoutNumber', ':layoutNumber')]
class Layout extends Resource
{
    public bool $deleted;

    public int $layoutNumber;

    public string $name;
}
