<?php

namespace Morningtrain\Economic\Resources\Customer;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;

/**
 * A delivery location on a Customer
 * https://restdocs.e-conomic.com/#get-customers-customernumber-delivery-locations
 */
class DeliveryLocation extends Resource
{
    #[PrimaryKey]
    public ?int $deliveryLocationNumber;
}
