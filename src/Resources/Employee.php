<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('employees')]
#[GetSingle('employees/:employeeId')]
class Employee extends Resource
{
    use GetCollectionable, GetSingleable;

    #[PrimaryKey]
    public int $employeeNumber;

    public string $name;
}
