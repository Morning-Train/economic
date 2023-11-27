<?php

namespace MorningTrain\Economic\Attributes\Resources;

use Attribute;
use MorningTrain\Economic\Abstracts\Endpoint;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)] class GetCollection extends Endpoint
{
}
