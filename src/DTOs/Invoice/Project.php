<?php

namespace Morningtrain\Economic\DTOs\Invoice;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;

class Project extends Resource
{
    #[PrimaryKey()]
    public ?int $projectNumber;

    public static function new(int $projectNumber): static
    {
        return new static([
            'projectNumber' => $projectNumber,
        ]);
    }
}
