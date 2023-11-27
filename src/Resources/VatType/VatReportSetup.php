<?php

namespace MorningTrain\Economic\Resources\VatType;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Classes\EconomicQueryBuilder;
use MorningTrain\Economic\Traits\Resources\EndpointResolvable;

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
