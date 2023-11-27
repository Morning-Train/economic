<?php

namespace MorningTrain\Economic\Attributes\Resources;

use Attribute;
use MorningTrain\Economic\Abstracts\Endpoint;

#[Attribute(Attribute::TARGET_CLASS)] class Update extends Endpoint
{
}
