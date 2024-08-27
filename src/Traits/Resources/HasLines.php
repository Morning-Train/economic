<?php

namespace Morningtrain\Economic\Traits\Resources;

use Illuminate\Support\Collection;
use Morningtrain\Economic\Attributes\Resources\Properties\ResourceType;
use Morningtrain\Economic\DTOs\Invoice\ProductLine;

trait HasLines
{
    /**
     * @var Collection<ProductLine>
     */
    #[ResourceType(ProductLine::class)]
    public ?Collection $lines = null;

    public function addLine(ProductLine $line): static
    {
        if (is_null($this->lines)) {
            $this->lines = new Collection;
        }

        $this->lines->add($line);

        return $this;
    }

    public function addLines(array|Collection $lines): static
    {
        foreach ($lines as $line) {
            if (! is_a($line, ProductLine::class)) {
                $line = new ProductLine($line);
            }

            $this->addLine($line);
        }

        return $this;
    }
}
