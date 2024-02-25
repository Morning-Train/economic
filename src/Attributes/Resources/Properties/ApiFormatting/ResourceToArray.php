<?php

namespace Morningtrain\Economic\Attributes\Resources\Properties\ApiFormatting;

use Attribute;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Interfaces\ApiFormatter;

#[Attribute(Attribute::TARGET_PROPERTY)] class ResourceToArray implements ApiFormatter
{
    protected array $properties;

    public function __construct(...$properties)
    {
        $this->properties = $properties;
    }

    public function format($value): array
    {
        if(!is_a($value, Resource::class)) {
            throw new \InvalidArgumentException('ResourceToArray can only be used on properties of type Resource');
        }

        $resourceAsArray = $value->toArray();

        $returnArray = [];

        foreach($this->properties as $key) {
            if(isset($resourceAsArray[$key])) {
                $returnArray[$key] = $resourceAsArray[$key];
            }
        }

        return $returnArray;
    }
}
