<?php

namespace Morningtrain\Economic\Resources\Invoice;

use DateTime;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\DTOs\Recipient;
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

    public Customer $customer;

    public Layout $layout;

    public Currency $currency;

    public PaymentTerm $paymentTerms;

    public DateTime $date;

    public Recipient $recipient;

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
}
