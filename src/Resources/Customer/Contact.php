<?php

namespace Morningtrain\Economic\Resources\Customer;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\Delete;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Attributes\Resources\Update;
use Morningtrain\Economic\Classes\EconomicRelatedResource;
use Morningtrain\Economic\Resources\Customer;
use Morningtrain\Economic\Services\EconomicApiService;
use Morningtrain\Economic\Traits\Resources\Creatable;
use Morningtrain\Economic\Traits\Resources\Deletable;
use Morningtrain\Economic\Traits\Resources\EndpointResolvable;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;
use Morningtrain\Economic\Traits\Resources\Updatable;

#[GetCollection('customers/:customerNumber/contacts')]
#[GetSingle('customers/:customerNumber/contacts/:contactNumber')]
#[Create('customers/:customerNumber/contacts')]
#[Update('customers/:customerNumber/contacts/:contactNumber', [':customerNumber' => 'customer->customerNumber', ':contactNumber' => 'customerContactNumber'])]
#[Delete('customers/:customerNumber/contacts/:contactNumber', [':customerNumber' => 'customer->customerNumber', ':contactNumber' => 'customerContactNumber'])]
class Contact extends Resource
{
    use Creatable, Updatable, Deletable, GetCollectionable, GetSingleable, EndpointResolvable;

    public Customer $customer;

    #[PrimaryKey]
    public int $customerContactNumber;

    public ?bool $deleted = null;

    public ?string $eInvoiceId = null;

    public ?string $email = null;

    public array $emailNotifications = [];

    public ?string $name = null;

    public ?string $notes = null;

    public ?string $phone = null;

    public ?int $sortKey = null;

    public static function fromCustomer(Customer|int $customer)
    {
        return new EconomicRelatedResource(
            static::getEndpointInstance(GetCollection::class),
            static::getEndpointInstance(GetSingle::class),
            static::class,
            [is_int($customer) ? $customer : $customer->customerNumber]
        );
    }

    public static function create(
        Customer|int $customer,
        string $name,
        ?string $email = null,
        ?string $phone = null,
        ?string $notes = null,
        ?string $eInvoiceId = null,
        ?array $emailNotifications = null
    ) {
        if(!is_a($customer,Customer::class)) {
            $customer = new Customer($customer);
        }

        return static::createRequest(compact(
            'customer',
            'name',
            'email',
            'phone',
            'notes',
            'eInvoiceId',
            'emailNotifications'
        ), [$customer->customerNumber]);
    }

    public static function deleteByPrimaryKey(int|string $customerNumber, int|string $customerContactNumber): bool
    {
        $response = EconomicApiService::delete(static::getEndpoint(Delete::class, $customerNumber, $customerContactNumber));

        if ($response->getStatusCode() !== 204) {
            // TODO: Log error and throw exception

            return false;
        }

        return true;
    }
}
