<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\Delete;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Attributes\Resources\Update;
use Morningtrain\Economic\Enums\PaymentTermsType;
use Morningtrain\Economic\Traits\Resources\Creatable;
use Morningtrain\Economic\Traits\Resources\Deletable;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;
use Morningtrain\Economic\Traits\Resources\Updatable;

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
        ?string $name = null,
        ?string $description = null,
        ?int $daysOfCredit = null,
        ?PaymentTermsType $paymentTermsType = null,
        ?Account $contraAccountForPrepaidAmount = null,
        ?float $percentageForPrepaidAmount = null,
        ?float $percentageForRemainderAmount = null,
        ?Customer $creditCardCompany = null,
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
