<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\Create;
use MorningTrain\Economic\Attributes\Resources\Delete;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use MorningTrain\Economic\Attributes\Resources\Update;
use MorningTrain\Economic\Enums\PaymentTermsType;
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

    public ?Account $contraAccountForPrepaidAmount;

    public ?Customer $creditCardCompany;

    public ?int $daysOfCredit;

    public ?string $description;

    public ?string $name;

    #[PrimaryKey]
    public int $paymentTermsNumber;

    public ?PaymentTermsType $paymentTermsType;

    public ?float $percentageForPrepaidAmount;

    public ?float $percentageForRemainderAmount;

    public static function new(
        int $paymentTermsNumber,
        string $name = null,
        string $description = null,
        int $daysOfCredit = null,
        PaymentTermsType $paymentTermsType = null,
        Account $contraAccountForPrepaidAmount = null,
        float $percentageForPrepaidAmount = null,
        float $percentageForRemainderAmount = null,
        Customer $creditCardCompany = null,
    ): static {
        return new static([
            'paymentTermsNumber' => $paymentTermsNumber,
            'name' => $name,
            'description' => $description,
            'daysOfCredit' => $daysOfCredit,
            'paymentTermsType' => $paymentTermsType,
            'contraAccountForPrepaidAmount' => $contraAccountForPrepaidAmount,
            'percentageForPrepaidAmount' => $percentageForPrepaidAmount,
            'percentageForRemainderAmount' => $percentageForRemainderAmount,
            'creditCardCompany' => $creditCardCompany,
        ]);
    }
}
