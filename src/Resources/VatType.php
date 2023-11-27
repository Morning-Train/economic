<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use MorningTrain\Economic\Classes\EconomicCollection;
use MorningTrain\Economic\Classes\EconomicQueryBuilder;
use MorningTrain\Economic\Resources\VatType\VatReportSetup;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('vat-types')]
#[GetSingle('vat-types/:vatTypeId')]
class VatType extends Resource
{
    use GetCollectionable, GetSingleable;

    public string $name;

    #[PrimaryKey]
    public int $vatTypeNumber;

    /**
     * @return EconomicCollection<VatReportSetup>
     * @throws \Exception
     */
    public function getVatReportSetups(): EconomicCollection
    {
        return VatReportSetup::fromVatType($this->getPrimaryKey())->all();
    }
}
