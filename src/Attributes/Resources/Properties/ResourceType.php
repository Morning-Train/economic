<?php

namespace Morningtrain\Economic\Attributes\Resources\Properties;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ResourceType
{
    public function __construct(public string $typeClass) {}

    public function getTypeClass(): string
    {
        return $this->typeClass;
    }

    public function __toString(): string
    {
        return $this->getTypeClass();
    }
}
