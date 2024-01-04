<?php

namespace Morningtrain\Economic\DTOs;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Resources\VatZone;

class Recipient extends Resource
{
    public string $name;

    public VatZone $vatZone;

    public ?string $address = null;

    public ?string $zip = null;

    public ?string $city = null;

    public ?string $country = null;

    public ?string $ean = null;

    public ?string $publicEntryNumber = null;

    public ?Attention $attention = null;

    public static function new(
        string $name,
        VatZone $vatZone,
        ?string $address = null,
        ?string $zip = null,
        ?string $city = null,
        ?string $country = null,
        ?string $ean = null,
        ?string $publicEntryNumber = null,
        ?Attention $attention = null,
    ): static {
        return new static([
            'name' => $name,
            'vatZone' => $vatZone,
            'address' => $address,
            'zip' => $zip,
            'city' => $city,
            'country' => $country,
            'ean' => $ean,
            'publicEntryNumber' => $publicEntryNumber,
            'attention' => $attention,
        ]);
    }
}
