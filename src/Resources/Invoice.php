<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Resources\Invoice\ProductLine;

class Invoice extends Resource
{


    public static function new(
        Customer|int $customer,
        Layout|int $layout,
        Currency|string $currency,
        PaymentTerm|int $paymentTerms
    )
    {
        return new static([
            'customer' => $customer,
            'layout' => $layout,
            'currency' => $currency,
            'paymentTerms' => $paymentTerms,
        ]);
    }

    public function addLine(ProductLine $line)
    {
    }


}
