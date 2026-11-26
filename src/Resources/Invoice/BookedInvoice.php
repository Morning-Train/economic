<?php

namespace Morningtrain\Economic\Resources\Invoice;

use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Enums\SendBy;

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

    /**
     * Book a draft invoice.
     *
     * @param  SendBy|null  $sendBy  - Let e-conomic send the invoice to the recipient once it is booked.
     *                               When null, "sendBy" is left out of the request and the invoice is not sent.
     */
    public static function createFromDraft(int|DraftInvoice $draft, ?string $idempotencyKey = null, ?SendBy $sendBy = null)
    {
        $body = [
            'draftInvoice' => [
                'draftInvoiceNumber' => is_int($draft) ? $draft : $draft->draftInvoiceNumber,
            ],
        ];

        if ($sendBy !== null) {
            $body['sendBy'] = $sendBy->value;
        }

        return static::createRequest($body, [], $idempotencyKey);
    }
}
