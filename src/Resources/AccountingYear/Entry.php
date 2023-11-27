<?php

namespace MorningTrain\Economic\Resources\AccountingYear;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Resources\Account;

class Entry extends Resource
{
    public Account $account;

    public float $amount;

    public float $amountInBaseCurrency;

}
