<?php

namespace MorningTrain\Economic\Resources\Invoice;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\Create;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Resources\Currency;
use MorningTrain\Economic\Resources\Customer;
use MorningTrain\Economic\Resources\Layout;
use MorningTrain\Economic\Resources\PaymentTerm;
use MorningTrain\Economic\Traits\Resources\Creatable;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('products')]
#[GetSingle('invoices/:product', ':product')]
#[Create('invoices/drafts')]
class Invoice extends Resource
{
    use Creatable, GetCollectionable, GetSingleable;

    public static function new(
        Customer|int $customer,
        Layout|int $layout,
        Currency|string $currency,
        PaymentTerm|int $paymentTerms,
    ) {
        return new static([
            'customer' => $customer,
            'layout' => $layout,
            'currency' => $currency,
            'paymentTerms' => $paymentTerms,
        ]);
    }

    public function addLine(ProductLine $line): static
    {
        $this->lines[] = $line;

        return $this;
    }
}
