<?php

namespace Morningtrain\Economic\Resources\AccountingYear;

use DateTime;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\Filterable;
use Morningtrain\Economic\Attributes\Resources\Properties\ResourceType;
use Morningtrain\Economic\Attributes\Resources\Properties\Sortable;
use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Resources\AccountingYear;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

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
