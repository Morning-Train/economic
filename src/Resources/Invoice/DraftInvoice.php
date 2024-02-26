<?php

namespace Morningtrain\Economic\Resources\Invoice;

use DateTime;
use Illuminate\Support\Collection;
use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Attributes\Resources\Update;
use Morningtrain\Economic\DTOs\Invoice\Note;
use Morningtrain\Economic\DTOs\Invoice\Project;
use Morningtrain\Economic\DTOs\Invoice\Recipient;
use Morningtrain\Economic\DTOs\Invoice\Reference;
use Morningtrain\Economic\Resources\Currency;
use Morningtrain\Economic\Resources\Customer;
use Morningtrain\Economic\Resources\Layout;
use Morningtrain\Economic\Resources\PaymentTerm;
use Morningtrain\Economic\Traits\Resources\Updatable;

#[GetCollection('invoices/drafts')]
#[GetSingle('invoices/drafts/:draftInvoiceNumber')]
#[Create('invoices/drafts')]
#[Update('invoices/drafts/:draftInvoiceNumber', [':draftInvoiceNumber' => 'draftInvoiceNumber'])]
class DraftInvoice extends Invoice
{
    use Updatable {
        Updatable::save as protected saveRequest;
    }

    public ?float $costPriceInBaseCurrency;

    #[PrimaryKey]
    public ?int $draftInvoiceNumber = null;

    public static function create(
        Currency|string $currency,
        Customer|int $customer,
        DateTime $date,
        Layout|int $layout,
        PaymentTerm|int $paymentTerms,
        Recipient $recipient,
        array|Collection $lines = [],
        // ?Delivery $delivery = null, // TODO: Implement
        ?DateTime $dueDate = null,
        ?float $exchangeRate = null,
        ?Note $notes = null,
        Project|int|null $project = null,
        ?Reference $references = null
    ): ?static {
        return static::createRequest(compact(
            'currency',
            'customer',
            'date',
            'layout',
            'paymentTerms',
            'recipient',
            'lines',
            'dueDate',
            'exchangeRate',
            'notes',
            'references',
        ));
    }

    public static function new(
        Currency|string $currency,
        Customer|int $customer,
        DateTime $date,
        Layout|int $layout,
        PaymentTerm|int $paymentTerms,
        Recipient $recipient,
        array|Collection $lines = [],
        // ?Delivery $delivery = null, // TODO: Implement
        ?DateTime $dueDate = null,
        ?float $exchangeRate = null,
        ?Note $notes = null,
        Project|int|null $project = null,
        ?Reference $references = null
    ): ?static {
        return new static(array_filter(compact(
            'currency',
            'customer',
            'date',
            'layout',
            'paymentTerms',
            'recipient',
            'lines',
            'dueDate',
            'exchangeRate',
            'notes',
            'references',
            'project'
        )));
    }

    public function save(): ?static
    {
        if (empty($this->draftInvoiceNumber)) {
            $new = static::create(
                currency: $this->currency,
                customer: $this->customer,
                date: $this->date,
                layout: $this->layout,
                paymentTerms: $this->paymentTerms,
                recipient: $this->recipient,
                lines: $this->lines,
                dueDate: $this->dueDate,
                exchangeRate: $this->exchangeRate,
                notes: $this->notes,
                project: $this->project,
                references: $this->references
            );

            $this->populate($new->toArray());

            return $this;
        }

        return $this->saveRequest();
    }

    public function book(): ?BookedInvoice
    {
        return BookedInvoice::createFromDraft($this);
    }
}
