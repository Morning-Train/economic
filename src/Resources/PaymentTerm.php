<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\Create;
use MorningTrain\Economic\Attributes\Resources\Delete;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use MorningTrain\Economic\Attributes\Resources\Update;
use MorningTrain\Economic\Traits\Resources\Creatable;
use MorningTrain\Economic\Traits\Resources\Deletable;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;
use MorningTrain\Economic\Traits\Resources\Updatable;

#[GetCollection('payment-terms')]
#[GetSingle('payment-terms/:paymentTermsNumber')]
#[Create('payment-terms')]
#[Update('payment-terms/:paymentTermsNumber')]
#[Delete('payment-terms/:paymentTermsNumber')]
class PaymentTerm extends Resource
{
    use Creatable, Deletable, GetCollectionable, GetSingleable, Updatable;

    public const PAYMENT_TERMS_TYPE_NET = 'net';

    public const PAYMENT_TERMS_TYPE_INVOICE_MONTH = 'invoiceMonth';

    public const PAYMENT_TERMS_TYPE_PAID_IN_CASH = 'paidInCash';

    public const PAYMENT_TERMS_TYPE_PREPAID = 'prepaid';

    public const PAYMENT_TERMS_TYPE_DUE_DATE = 'dueDate';

    public const PAYMENT_TERMS_TYPE_FACTORING = 'factoring';

    public const PAYMENT_TERMS_TYPE_INVOICE_WEEK_STARTING_SUNDAY = 'invoiceWeekStartingSunday';

    public const PAYMENT_TERMS_TYPE_INVOICE_WEEK_STARTING_MONDAY = 'invoiceWeekStartingMonday';

    public const PAYMENT_TERMS_TYPE_CREDITCARD = 'creditcard';

    public Account $contraAccountForPrepaidAmount;

    public Customer $creditCardCompany;

    public int $daysOfCredit;

    public string $description;

    public string $name;

    #[PrimaryKey]
    public int $paymentTermNumber;

    public string $paymentTermsType;

    public float $percentageForPrepaidAmount;

    public float $percentageForRemainderAmount;
}
