<?php

namespace Morningtrain\Economic\Resources\VatType;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Classes\EconomicQueryBuilder;
use Morningtrain\Economic\Traits\Resources\EndpointResolvable;

#[GetCollection('vat-types/:vatTypeId/vat-report-setups')]
class VatReportSetup extends Resource
{
    use EndpointResolvable;

    public string $vatReportSetupNumber;

    public string $name;

    public static function fromVatType(int $vatTypeId): EconomicQueryBuilder
    {
        return (new EconomicQueryBuilder(static::class))->setEndpoint(static::getEndpoint(GetCollection::class, $vatTypeId));
    }
}
