<?php

namespace Morningtrain\Economic\DTOs;

use Morningtrain\Economic\Abstracts\Resource;

class Note extends Resource
{
    public ?string $heading;

    public ?string $textLine1;

    public ?string $textLine2;

    public static function new(
        ?string $heading = null,
        ?string $textLine1 = null,
        ?string $textLine2 = null,
    ): static {
        return new static([
            'heading' => $heading,
            'textLine1' => $textLine1,
            'textLine2' => $textLine2,
        ]);
    }
}
