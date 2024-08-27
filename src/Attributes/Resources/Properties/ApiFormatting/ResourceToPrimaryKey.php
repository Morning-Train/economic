<?php

namespace Morningtrain\Economic\Attributes\Resources\Properties\ApiFormatting;

use Attribute;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Interfaces\ApiFormatter;

#[Attribute(Attribute::TARGET_PROPERTY)] class ResourceToPrimaryKey implements ApiFormatter
{
    public function __construct() {}

    public function format($value): mixed
    {
        if (! is_a($value, Resource::class)) {
            throw new \InvalidArgumentException('ResourceToArray can only be used on properties of type Resource');
        }

        return $value->getPrimaryKey();
    }
}
