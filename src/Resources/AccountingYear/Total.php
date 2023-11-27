<?php

namespace MorningTrain\Economic\Resources\AccountingYear;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Classes\EconomicQueryBuilder;
use MorningTrain\Economic\Resources\Account;
use DateTime;
use MorningTrain\Economic\Traits\Resources\EndpointResolvable;
use Exception;

#[GetCollection('accounting-years/:accountingYear/totals')]
#[GetSingle('accounting-years/:accountingYear/periods/:accountingYearPeriod/totals')]
class Total extends Resource
{
    use EndpointResolvable;

    public Account $account;

    public DateTime $fromDate;

    public DateTime $toDate;

    public float $totalInBaseCurrency;

    /**
     * Get Totals for a specific Accounting Year
     * @param int|string $accountingYear
     * @return EconomicQueryBuilder<Total>
     * @throws Exception
     */
    public static function fromAccountingYear(int|string $accountingYear): EconomicQueryBuilder
    {
        return (new EconomicQueryBuilder(static::class))->setEndpoint(static::getEndpoint(GetCollection::class, $accountingYear));
    }

    public static function fromAccountingYearPeriod(int|string $accountingYear, int $periodNumber): EconomicQueryBuilder
    {
        return (new EconomicQueryBuilder(static::class))->setEndpoint(static::getEndpoint(GetSingle::class, $accountingYear, $periodNumber));
    }
}
