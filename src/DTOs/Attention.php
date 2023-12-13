<?php

namespace MorningTrain\Economic\DTOs;

use MorningTrain\Economic\Abstracts\Resource;

class Attention extends Resource
{
    public ?int $customerContactNumber = null;

    public static function new(
        ?int $customerContactNumber = null,
    ): static {
        return new static([
            'customerContactNumber' => $customerContactNumber,
        ]);
    }
}
