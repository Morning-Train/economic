<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;

class Account extends Resource
{
    public AccountingYear $accountingYear;

    public int $accountNumber;

    public array $accountSummed;

    public string $accountType;

    public bool $barred;

    public bool $blockDirectEntries;

    public Account $contraAccount;

    public string $debitCredit;

    public float $draftBalance;

    public string $name;

    public Account $totalFromAccount;

    //public VatAccount $vatAccount;
}
