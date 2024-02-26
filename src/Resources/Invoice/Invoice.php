<?php

namespace Morningtrain\Economic\Resources\Invoice;

use DateTime;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Properties\ApiFormatting\ResourceToArray;
use Morningtrain\Economic\Attributes\Resources\Properties\ApiFormatting\ResourceToPrimaryKey;
use Morningtrain\Economic\DTOs\Invoice\Note;
use Morningtrain\Economic\DTOs\Invoice\Recipient;
use Morningtrain\Economic\DTOs\Invoice\Reference;
use Morningtrain\Economic\Resources\Currency;
use Morningtrain\Economic\Resources\Customer;
use Morningtrain\Economic\Resources\Layout;
use Morningtrain\Economic\Resources\PaymentTerm;
use Morningtrain\Economic\Traits\Resources\Creatable;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;
use Morningtrain\Economic\Traits\Resources\HasLines;

class Invoice extends Resource
{
    use Creatable;
    use GetCollectionable;
    use GetSingleable;
    use HasLines;

    #[ResourceToPrimaryKey()]
    public ?Currency $currency = null;

    #[ResourceToArray('customerNumber', 'self')]
    public ?Customer $customer = null;

    public ?DateTime $date = null;

    // public ?array $delivery; // TODO: Implement

    public ?string $externalId = null;

    public ?Layout $layout = null;

    public ?DateTime $dueDate = null;

    public ?float $exchangeRate = null;

    public ?float $grossAmount = null;

    public ?float $grossAmountInBaseCurrency = null;

    public ?DateTime $lastUpdated = null;

    public ?float $marginInBaseCurrency = null;

    public ?float $marginPercentage = null;

    public ?float $netAmount = null;

    public ?float $netAmountInBaseCurrency = null;

    public ?Note $notes = null;

    public ?PaymentTerm $paymentTerms = null;

    public ?array $pdf = null;

    public ?Project $project;

    public ?Recipient $recipient = null;

    public ?Reference $references = null;

    public ?float $roundingAmount = null;

    //public ?Template $templates; // TODO: Implement

    public ?float $vatAmount = null;
}
