<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;

class Account extends Resource
{
    public AccountingYear $accountingYear;

    public int $accountNumber;

    public array $accountsSummed;

    public string $accountType;

    /** Link to the accounting years of this account */
    public ?string $accountingYears;

    public ?float $balance;

    public bool $barred;

    public bool $blockDirectEntries;

    public Account $contraAccount;

    public string $debitCredit;

    public float $draftBalance;

    public string $name;

    public Account $totalFromAccount;

    public ?VatAccount $vatAccount;
}
