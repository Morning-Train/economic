<?php

use MorningTrain\Economic\Classes\EconomicResponse;
use MorningTrain\Economic\DTOs\Recipient;
use MorningTrain\Economic\Resources\Invoice\DraftInvoice;
use MorningTrain\Economic\Resources\VatZone;

it('creates draft invoice', function () {
    $this->driver->expects()->post()
        ->withArgs(function (string $url, array $body) {
            return $url === 'https://restapi.e-conomic.com/invoices/drafts'
                && $body === [
                    'customer' => [
                        'customerNumber' => 1,
                    ],
                    'layout' => [
                        'layoutNumber' => 1,
                    ],
                    'currency' => [
                        'isoNumber' => 'DKK',
                    ],
                    'paymentTerms' => [
                        'paymentTermsNumber' => 1,
                    ],
                    'date' => '2021-01-01',
                    'recipient' => [
                        'name' => 'John Doe',
                        'vatZone' => [
                            'vatZoneNumber' => 1,
                        ],
                    ],
                ];
        })
        ->once()
        ->andReturn(new EconomicResponse(201, []));

    DraftInvoice::new(
        1,
        1,
        'DKK',
        1,
        DateTime::createFromFormat('Y-m-d', '2021-01-01'),
        new Recipient(
            'John Doe',
            new VatZone(1),
        )
    )
        ->create();
    //->book();
});

it('books draft invoice', function () {
    $this->driver->expects()->post()
        ->withArgs(function (string $url, array $body) {
            return $url === 'https://restapi.e-conomic.com/invoices/drafts';
        })
        ->once()
        ->andReturn(new EconomicResponse(201, [
            'draftInvoiceNumber' => 1,
        ]));

    $this->driver->expects()->post()
        ->withArgs(function (string $url, array $body) {
            return $url === 'https://restapi.e-conomic.com/invoices/booked'
                && $body === [
                    'draftInvoice' => [
                        'draftInvoiceNumber' => 1,
                    ],
                ];
        })
        ->once()
        ->andReturn(new EconomicResponse(201, []));

    DraftInvoice::new(
        1,
        1,
        'DKK',
        1,
        DateTime::createFromFormat('Y-m-d', '2021-01-01'),
        new Recipient(
            'John Doe',
            new VatZone(1),
        )
    )
        ->create()
        ->book();
});
