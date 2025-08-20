<?php

namespace Morningtrain\Economic\Attributes\Resources\Properties\ApiFormatting;

use Attribute;
use Morningtrain\Economic\Interfaces\ApiFormatter;

#[Attribute(Attribute::TARGET_PROPERTY)] class FloatPrecision implements ApiFormatter
{
    public function __construct(
        protected int $precision
    ) {
    }

    public function format($value): float
    {
        if(!is_numeric($value)) {
            return $value;
        }

        return round((float) $value, $this->precision);
    }
}
