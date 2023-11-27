<?php

namespace MorningTrain\Economic\Attributes\Resources;

use Attribute;
use MorningTrain\Economic\Abstracts\Endpoint;

#[Attribute(Attribute::TARGET_CLASS)] class Create extends Endpoint
{
    public function __construct(protected string $endpoint)
    {
    }
}
