<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\Delete;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Attributes\Resources\Properties\ResourceType;
use Morningtrain\Economic\Attributes\Resources\Update;
use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Traits\Resources\Creatable;
use Morningtrain\Economic\Traits\Resources\Deletable;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;
use Morningtrain\Economic\Traits\Resources\Updatable;

#[GetCollection('customer-groups')]
#[GetSingle('customer-groups/:customerGroupNumber')]
#[Create('customer-groups')]
#[Update('customer-groups/:customerGroupNumber')]
#[Delete('customer-groups/:customerGroupNumber')]
class CustomerGroup extends Resource
{
    use Creatable, Deletable, GetCollectionable, GetSingleable, Updatable;

    public Account $account;

    #[PrimaryKey]
    public int $customerGroupNumber;

    #[ResourceType(Customer::class)]
    public EconomicCollection $customers;

    public Layout $layout;

    public string $name;
}
