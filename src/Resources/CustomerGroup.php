<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\Create;
use MorningTrain\Economic\Attributes\Resources\Delete;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\ResourceType;
use MorningTrain\Economic\Attributes\Resources\Update;
use MorningTrain\Economic\Classes\EconomicCollection;
use MorningTrain\Economic\Traits\Resources\Creatable;
use MorningTrain\Economic\Traits\Resources\Deletable;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;
use MorningTrain\Economic\Traits\Resources\Updatable;

#[GetCollection('customer-groups')]
#[GetSingle('customer-groups/:customerGroupNumber')]
#[Create('customer-groups')]
#[Update('customer-groups/:customerGroupNumber')]
#[Delete('customer-groups/:customerGroupNumber')]
class CustomerGroup extends Resource
{
    use GetCollectionable, GetSingleable, Creatable, Updatable, Deletable;

    public Account $account;

    public int $customerGroupNumber;

    #[ResourceType(Customer::class)]
    public EconomicCollection $customers;

    public Layout $layout;

    public string $name;
}
