<?php

namespace Morningtrain\Economic\Traits\Resources;

use Morningtrain\Economic\Resources\Invoice\ProductLine;

trait HasLines
{
    /**
     * @var array ProductLine[]
     */
    public array $lines = [];

    public function addLine(ProductLine $line): static
    {
        $this->lines[] = $line;

        return $this;
    }
}
