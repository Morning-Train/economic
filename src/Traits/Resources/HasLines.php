<?php

namespace MorningTrain\Economic\Traits\Resources;

use MorningTrain\Economic\Resources\Invoice\ProductLine;

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
