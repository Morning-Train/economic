<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('currencies')]
#[GetSingle('currencies/:code')]
class Currency extends Resource
{
    use GetCollectionable, GetSingleable;

    public ?string $code = null;

    #[PrimaryKey]
    public ?string $isoNumber = null;

    public ?string $name = null;
}
