<?php

namespace MorningTrain\Economic\DTOs;

use MorningTrain\Economic\Resources\VatZone;

class Recipient
{
    public function __construct(
        public readonly string $name,
        public readonly VatZone $vatZone,
        public ?string $address = null,
        public ?string $zip = null,
        public ?string $city = null,
        public ?string $country = null,
        public ?string $ean = null,
        public ?string $publicEntryNumber = null,
        //public ?Attention $attention = null,
    ) {
    }
}
