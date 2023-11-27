<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\Create;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\Filterable;
use MorningTrain\Economic\Attributes\Resources\Properties\Required;
use MorningTrain\Economic\Attributes\Resources\Properties\Sortable;
use MorningTrain\Economic\Attributes\Resources\Properties\ResourceType;
use MorningTrain\Economic\Classes\EconomicCollection;
use MorningTrain\Economic\Resources\AccountingYear\Entry;
use Datetime;
use MorningTrain\Economic\Resources\AccountingYear\Period;
use MorningTrain\Economic\Resources\AccountingYear\Total;
use MorningTrain\Economic\Resources\AccountingYear\Voucher;
use MorningTrain\Economic\Traits\Resources\Creatable;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('accounting-years')]
#[GetSingle('accounting-years/:accountingYear')]
#[Create('accounting-years')]
class AccountingYear extends Resource
{
    /**
     * @use GetCollectionable<AccountingYear>
     */
    use GetCollectionable, GetSingleable, Creatable;

    public bool $closed;

    /**
     * @var EconomicCollection<Entry>
     */
    #[ResourceType(Entry::class)]
    public EconomicCollection $entries;

    #[Filterable]
    #[Sortable]
    #[Required]
    public DateTime $fromDate;

    /**
     * @var EconomicCollection<Period>
     */
    #[ResourceType(Period::class)]
    public EconomicCollection $periods;

    #[Filterable]
    #[Sortable]
    #[Required]
    public DateTime $toDate;

    /**
     * @var EconomicCollection<Total>
     */
    #[ResourceType(Total::class)]
    public EconomicCollection $totals;

    /**
     * @var EconomicCollection<Voucher>
     */
    #[ResourceType(Voucher::class)]
    public EconomicCollection $vouchers;

    #[Filterable]
    #[Sortable]
    public string $year;

    public static function create(DateTime $fromDate, DateTime $toDate): static
    {
        return static::createRequest([
            'fromDate' => $fromDate->format('Y-m-d'),
            'toDate' => $toDate->format('Y-m-d'),
        ]);
    }
}
