<?php

namespace Morningtrain\Economic\Resources\AccountingYear;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Resources\Account;

class Entry extends Resource
{
    public Account $account;

    public float $amount;

    public float $amountInBaseCurrency;
}
