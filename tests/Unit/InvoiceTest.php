<?php

use MorningTrain\Economic\Classes\EconomicResponse;
use MorningTrain\Economic\DTOs\Recipient;
use MorningTrain\Economic\Resources\Invoice\DraftInvoice;
use MorningTrain\Economic\Resources\Invoice\ProductLine;
use MorningTrain\Economic\Resources\Product;
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
                    'currency' => 'DKK',
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
                    'lines' => [
                        [
                            'product' => [
                                'productNumber' => 1,
                            ],
                            'quantity' => 1,
                            'unitNetPrice' => 500,
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
        ->addLine(ProductLine::new(
            product: new Product(1),
            quantity: 1,
            unitNetPrice: 500
        ))
        ->create();
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

it('can add lines', function () {
    $invoice = DraftInvoice::new(
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
        ->addLine(ProductLine::new(
            product: new Product(1),
            quantity: 1,
            unitNetPrice: 500
        ))
        ->addLine(ProductLine::new(
            product: new Product(2),
            quantity: 5,
            unitNetPrice: 100,
            description: 'Some description',
        ));

    expect($invoice->lines)
        ->toBeArray()
        ->toHaveCount(2);

    expect($invoice->lines[0])
        ->toBeInstanceOf(ProductLine::class)
        ->description->toBeNull()
        ->discountPercentage->toBeNull()
        ->marginInBaseCurrency->toBeNull()
        ->marginPercentage->toBeNull()
        ->product->toBeInstanceOf(Product::class)
        ->quantity->toBe(1.0);

    expect($invoice->lines[1])
        ->toBeInstanceOf(ProductLine::class)
        ->description->toBe('Some description')
        ->discountPercentage->toBeNull()
        ->marginInBaseCurrency->toBeNull()
        ->marginPercentage->toBeNull()
        ->product->toBeInstanceOf(Product::class)
        ->quantity->toBe(5.0);
});
