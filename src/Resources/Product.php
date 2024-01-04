<?php

namespace Morningtrain\Economic\Resources;

use DateTime;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\Filterable;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
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

    #[Filterable]
    #[Sortable]
    public ?float $recommendedPrice;

    #[Filterable]
    #[Sortable]
    public ?float $salesPrice;

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
        ?float $recommendedPrice = null,
        ?float $salesPrice = null,
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
            'recommendedPrice' => $recommendedPrice,
            'salesPrice' => $salesPrice,
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
