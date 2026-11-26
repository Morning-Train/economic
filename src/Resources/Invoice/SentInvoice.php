<?php

namespace Morningtrain\Economic\Resources\Invoice;

use DateTime;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\DTOs\Invoice\SentInvoiceRecipient;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

/**
 * An invoice e-conomic has sent via e-invoicing (GET /invoices/sent).
 *
 * To see whether one booked invoice was sent, use BookedInvoice::sentInvoice() instead of reading all of these.
 *
 * @see https://restdocs.e-conomic.com/#get-invoices-sent
 */
#[GetCollection('invoices/sent')]
#[GetSingle('invoices/sent/:id')]
class SentInvoice extends Resource
{
    use GetCollectionable;
    use GetSingleable;

    #[PrimaryKey]
    public ?int $id = null;

    /** The booked invoice that was sent */
    public ?BookedInvoice $invoice = null;

    /** The status of the sent invoice, such as "received" */
    public ?string $status = null;

    /**
     * The e-invoicing channel the invoice was sent through: "mobilePay" or "ean".
     * This is the property e-conomic returns, although its schema names it "sendBy".
     */
    public ?string $sentBy = null;

    /**
     * The e-invoicing channel as e-conomic's schema names it ("mobilePay" or "ean").
     * e-conomic returns "sentBy" instead, so this is null unless e-conomic starts following its schema.
     */
    public ?string $sendBy = null;

    /** The date the invoice was sent */
    public ?DateTime $creationDate = null;

    /** The e-conomic user that booked and sent the invoice */
    public ?string $createdBy = null;

    public ?SentInvoiceRecipient $recipient = null;
}
