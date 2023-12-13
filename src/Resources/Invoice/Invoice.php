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
