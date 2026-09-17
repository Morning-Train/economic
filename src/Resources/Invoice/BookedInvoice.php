<?php

namespace Morningtrain\Economic\Resources\Invoice;

use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;

#[GetCollection('invoices/booked')]
#[GetSingle('invoices/booked/:bookedInvoiceNumber')]
#[Create('invoices/booked')]
class BookedInvoice extends Invoice
{
    public ?int $bookedInvoiceNumber = null;

    public ?int $orderNumber = null;

    public ?float $remainder = null;

    public ?float $remainderInBaseCurrency = null;

    /** Link to the sent state of this invoice */
    public ?string $sent = null;

    public static function createFromDraft(int|DraftInvoice $draft, ?string $idempotencyKey = null)
    {
        return static::createRequest([
            'draftInvoice' => [
                'draftInvoiceNumber' => is_int($draft) ? $draft : $draft->draftInvoiceNumber,
            ],
        ], [], $idempotencyKey);
    }
}
