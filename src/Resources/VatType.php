<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Resources\VatType\VatReportSetup;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

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
     *
     * @throws \Exception
     */
    public function getVatReportSetups(): EconomicCollection
    {
        return VatReportSetup::fromVatType($this->getPrimaryKey())->all();
    }
}
