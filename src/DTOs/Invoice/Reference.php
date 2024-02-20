<?php

namespace Morningtrain\Economic\DTOs\Invoice;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Resources\Customer\Contact;
use Morningtrain\Economic\Resources\Employee;

class Reference extends Resource
{
    public ?Contact $customerContact;

    public ?string $other;

    public ?Employee $salesPerson;

    public ?Employee $vendorReference;

    public static function new(
        Contact|int|null $customerContact = null,
        ?string $other = null,
        Employee|int|null $salesPerson = null,
        Employee|int|null $vendorReference = null,
    ): static {
        return new static([
            'customerContact' => $customerContact,
            'other' => $other,
            'salesPerson' => $salesPerson,
            'vendorReference' => $vendorReference,
        ]);
    }
}
