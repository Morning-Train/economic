<?php

namespace MorningTrain\Economic\Resources\Invoice;

use MorningTrain\Economic\Attributes\Resources\Create;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;

#[GetCollection('invoices/booked')]
#[GetSingle('invoices/booked/:bookedInvoiceNumber', ':bookedInvoiceNumber')]
#[Create('invoices/booked')]
class BookedInvoice extends Invoice
{
    public ?int $bookedInvoiceNumber = null;

    public static function createFromDraft(int|DraftInvoice $draft)
    {
        return static::createRequest([
            'draftInvoice' => [
                'draftInvoiceNumber' => is_int($draft) ? $draft : $draft->draftInvoiceNumber,
            ],
        ]);
    }
}
