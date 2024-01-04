<?php

namespace Morningtrain\Economic\Resources;

use Datetime;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\Filterable;
use Morningtrain\Economic\Attributes\Resources\Properties\Required;
use Morningtrain\Economic\Attributes\Resources\Properties\ResourceType;
use Morningtrain\Economic\Attributes\Resources\Properties\Sortable;
use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Resources\AccountingYear\Entry;
use Morningtrain\Economic\Resources\AccountingYear\Period;
use Morningtrain\Economic\Resources\AccountingYear\Total;
use Morningtrain\Economic\Resources\AccountingYear\Voucher;
use Morningtrain\Economic\Traits\Resources\Creatable;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('accounting-years')]
#[GetSingle('accounting-years/:accountingYear')]
#[Create('accounting-years')]
class AccountingYear extends Resource
{
    /**
     * @use GetCollectionable<AccountingYear>
     */
    use Creatable, GetCollectionable, GetSingleable;

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
