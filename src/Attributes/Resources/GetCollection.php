<?php

namespace Morningtrain\Economic\Attributes\Resources;

use Attribute;
use Morningtrain\Economic\Abstracts\Endpoint;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)] class GetCollection extends Endpoint
{
    public function __construct(protected string $endpoint)
    {
    }
}
