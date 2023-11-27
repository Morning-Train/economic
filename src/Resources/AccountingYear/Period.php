<?php

namespace MorningTrain\Economic\Resources\AccountingYear;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\Filterable;
use MorningTrain\Economic\Attributes\Resources\Properties\ResourceType;
use MorningTrain\Economic\Attributes\Resources\Properties\Sortable;
use MorningTrain\Economic\Classes\EconomicCollection;
use MorningTrain\Economic\Resources\AccountingYear;
use DateTime;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('accounting-years/:accountingYear/periods')]
#[GetSingle('accounting-years/:accountingYear/periods/:accountingYearPeriod', ':accountingYearPeriod')]
class Period extends Resource
{
    use GetCollectionable, GetSingleable;

    public AccountingYear $accountingYear;

    public bool $closed;

    /**
     * @var EconomicCollection<Entry>
     */
    #[ResourceType(Entry::class)]
    public EconomicCollection $entries;

    #[Filterable]
    #[Sortable]
    public DateTime $fromDate;

    #[Filterable]
    #[Sortable]
    public int $periodNumber;

    #[Filterable]
    #[Sortable]
    public DateTime $toDate;

    /**
     * @var EconomicCollection<Total>
     */
    #[ResourceType(Total::class)]
    public EconomicCollection $totals;
}
