<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

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
