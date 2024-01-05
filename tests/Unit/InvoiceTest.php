<?php

use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\DTOs\Recipient;
use Morningtrain\Economic\Enums\PaymentTermsType;
use Morningtrain\Economic\Resources\Invoice\BookedInvoice;
use Morningtrain\Economic\Resources\Invoice\DraftInvoice;
use Morningtrain\Economic\Resources\Invoice\ProductLine;
use Morningtrain\Economic\Resources\PaymentTerm;
use Morningtrain\Economic\Resources\Product;
use Morningtrain\Economic\Resources\VatZone;

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
                        'paymentTermsType' => 'paidInCash',
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
                            'description' => 'T-shirt - Size L',
                            'product' => [
                                'productNumber' => '1',
                            ],
                            'quantity' => 1,
                            'unitNetPrice' => 500,
                        ],
                    ],
                ];
        })
        ->once()
        ->andReturn(new EconomicResponse(201, [
            'draftInvoiceNumber' => 1,
            'recipient' => [
                'name' => 'John Doe',
                'vatZone' => [
                    'vatZoneNumber' => 1,
                ],
            ],
        ]));

    DraftInvoice::new(
        1,
        1,
        'DKK',
        PaymentTerm::new(
            paymentTermsNumber: 1,
            paymentTermsType: PaymentTermsType::PAID_IN_CASH,
        ),
        DateTime::createFromFormat('Y-m-d', '2021-01-01'),
        Recipient::new(
            'John Doe',
            new VatZone(1),
        )
    )
        ->addLine(ProductLine::new(
            description: 'T-shirt - Size L',
            product: Product::new(
                productNumber: 1,
            ),
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

it('returns expected booked invoice data', function () {
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
        ->andReturn(new EconomicResponse(201, [
            'bookedInvoiceNumber' => 1,
        ]));

    $bookedInvoice = BookedInvoice::createFromDraft(1);

    expect($bookedInvoice)
        ->bookedInvoiceNumber->toBe(1);
});

it('handles populating resource with enum value', function () {
    $this->driver->expects()->post()
        ->withAnyArgs()
        ->twice()
        ->andReturn(new EconomicResponse(201, [
            'draftInvoiceNumber' => 1,
            'recipient' => [
                'name' => 'John Doe',
                'vatZone' => [
                    'vatZoneNumber' => 1,
                ],
            ],
            'paymentTerms' => [
                'paymentTermsNumber' => 1,
                'paymentTermsType' => 'net',
            ],
        ]), new EconomicResponse(201, [
            'bookedInvoiceNumber' => 1,
        ]));

    DraftInvoice::new(
        1,
        1,
        'DKK',
        PaymentTerm::new(
            paymentTermsNumber: 1,
        ),
        DateTime::createFromFormat('Y-m-d', '2021-01-01'),
        Recipient::new(
            'John Doe',
            new VatZone(1),
        )
    )
        ->create();
});
