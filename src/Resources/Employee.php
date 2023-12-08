<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('employees')]
#[GetSingle('employees/:employeeId')]
class Employee extends Resource
{
    use GetCollectionable, GetSingleable;

    #[PrimaryKey]
    public int $employeeNumber;

    public string $name;
}
