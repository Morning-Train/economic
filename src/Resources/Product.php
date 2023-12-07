<?php

namespace MorningTrain\Economic\Resources;

use DateTime;
use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\Create;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\Filterable;
use MorningTrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use MorningTrain\Economic\Attributes\Resources\Properties\ResourceType;
use MorningTrain\Economic\Attributes\Resources\Properties\Sortable;
use MorningTrain\Economic\Classes\EconomicCollection;
use MorningTrain\Economic\Resources\AccountingYear\Entry;
use MorningTrain\Economic\Resources\AccountingYear\Period;
use MorningTrain\Economic\Resources\AccountingYear\Total;
use MorningTrain\Economic\Resources\AccountingYear\Voucher;
use MorningTrain\Economic\Traits\Resources\Creatable;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('products')]
#[GetSingle('products/:product', ':product')]
#[Create('products')]
class Product extends Resource
{
    use Creatable, GetCollectionable, GetSingleable;

    public ?string $name;

    #[Filterable]
    #[Sortable]
    public ?string $barCode;

    #[Filterable]
    #[Sortable]
    public ?bool $barred;

    #[Filterable]
    #[Sortable]
    public ?float $costPrice;

    /**
     * @var EconomicCollection<Entry>|null
     */
    #[ResourceType(Entry::class)]
    public ?EconomicCollection $entries;

    #[Filterable]
    #[Sortable]
    public ?DateTime $fromDate;

    /**
     * @var EconomicCollection<Period>|null
     */
    #[ResourceType(Period::class)]
    public ?EconomicCollection $periods;

    #[Filterable]
    #[Sortable]
    public ?DateTime $toDate;

    /**
     * @var EconomicCollection<Total>|null
     */
    #[ResourceType(Total::class)]
    public ?EconomicCollection $totals;

    /**
     * @var EconomicCollection<Voucher>|null
     */
    #[ResourceType(Voucher::class)]
    public ?EconomicCollection $vouchers;

    #[Filterable]
    #[Sortable]
    public ?string $year;

    #[PrimaryKey]
    public string $productNumber;

    public static function create(DateTime $fromDate, DateTime $toDate): static
    {
        return static::createRequest(compact('fromDate', 'toDate'));
    }

    public static function new(
        string $productNumber,
        ?string $name = null,
        ?string $barCode = null,
        ?bool $barred = null,
        ?float $costPrice = null,
        ?EconomicCollection $entries = null,
        DateTime|string|null $fromDate = null,
        ?EconomicCollection $periods = null,
        DateTime|string|null $toDate = null,
        ?EconomicCollection $totals = null,
        ?EconomicCollection $vouchers = null,
        ?string $year = null,
    ): static {
        return new static([
            'productNumber' => $productNumber,
            'name' => $name,
            'barCode' => $barCode,
            'barred' => $barred,
            'costPrice' => $costPrice,
            'entries' => $entries,
            'fromDate' => $fromDate,
            'periods' => $periods,
            'toDate' => $toDate,
            'totals' => $totals,
            'vouchers' => $vouchers,
            'year' => $year,
        ]);
    }
}
