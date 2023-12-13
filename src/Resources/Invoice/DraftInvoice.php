<?php

namespace MorningTrain\Economic\Resources\Invoice;

use DateTime;
use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\Create;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\DTOs\Recipient;
use MorningTrain\Economic\Resources\Currency;
use MorningTrain\Economic\Resources\Customer;
use MorningTrain\Economic\Resources\Layout;
use MorningTrain\Economic\Resources\PaymentTerm;
use MorningTrain\Economic\Traits\Resources\Creatable;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;
use MorningTrain\Economic\Traits\Resources\HasLines;

#[GetCollection('invoices/drafts')]
#[GetSingle('invoices/drafts/:draftInvoiceNumber', ':draftInvoiceNumber')]
#[Create('invoices/drafts')]
class DraftInvoice extends Invoice
{
    public ?int $draftInvoiceNumber = null;

    public function create()
    {
        return static::createRequest([
            'customer' => $this->customer,
            'layout' => $this->layout,
            'currency' => $this->currency->isoNumber,
            'paymentTerms' => $this->paymentTerms,
            'date' => $this->date->format('Y-m-d'),
            'recipient' => $this->recipient,
            'lines' => $this->lines ?? null,
        ]);
    }

    public function book(): ?BookedInvoice
    {
        return BookedInvoice::createFromDraft($this);
    }
}
