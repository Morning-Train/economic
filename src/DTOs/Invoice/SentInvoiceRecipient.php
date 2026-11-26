<?php

namespace Morningtrain\Economic\DTOs\Invoice;

use Morningtrain\Economic\Abstracts\Resource;

/**
 * Who a sent invoice was sent to. Each property is only set if it applies to the channel it was sent through.
 */
class SentInvoiceRecipient extends Resource
{
    public ?string $name = null;

    public ?string $mobilePhone = null;

    public ?string $ean = null;
}
