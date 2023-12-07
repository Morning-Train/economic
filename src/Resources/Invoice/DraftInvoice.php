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
class DraftInvoice extends Resource
{
    use Creatable, GetCollectionable, GetSingleable;
    use HasLines;

    public Customer $customer;

    public Layout $layout;

    public Currency $currency;

    public PaymentTerm $paymentTerms;

    public DateTime $date;

    public Recipient $recipient;

    public ?int $draftInvoiceNumber = null;

    public static function new(
        Customer|int $customer,
        Layout|int $layout,
        Currency|string $currency,
        PaymentTerm|int $paymentTerms,
        DateTime $date,
        Recipient $recipient,
    ): static {
        return new static([
            'customer' => $customer,
            'layout' => $layout,
            'currency' => $currency,
            'paymentTerms' => $paymentTerms,
            'date' => $date,
            'recipient' => $recipient,
        ]);
    }

    public function create()
    {
        ray($this->recipient);

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
