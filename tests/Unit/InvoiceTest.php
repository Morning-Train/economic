<?php

use Illuminate\Support\Collection;
use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\DTOs\Invoice\Note;
use Morningtrain\Economic\DTOs\Invoice\Pdf;
use Morningtrain\Economic\DTOs\Invoice\ProductLine;
use Morningtrain\Economic\DTOs\Invoice\Recipient;
use Morningtrain\Economic\DTOs\Invoice\Reference;
use Morningtrain\Economic\Resources\Currency;
use Morningtrain\Economic\Resources\Customer;
use Morningtrain\Economic\Resources\Invoice\BookedInvoice;
use Morningtrain\Economic\Resources\Invoice\DraftInvoice;
use Morningtrain\Economic\Resources\Layout;
use Morningtrain\Economic\Resources\PaymentTerm;
use Morningtrain\Economic\Resources\Product;
use Morningtrain\Economic\Resources\VatZone;

it('gets a drafted invoice', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/invoices/drafts/422',
        []
    )->andReturn(new EconomicResponse(200, fixture('Invoices/draft/get-single')));

    $invoice = DraftInvoice::find(422);

    expect($invoice)
        ->toBeInstanceOf(DraftInvoice::class)
        ->draftInvoiceNumber->toBe(422)
        ->externalId->toBe('123456')
        ->date->toBeInstanceOf(DateTime::class)
        ->currency->toBeInstanceOf(Currency::class)
        ->exchangeRate->toBeFloat()
        ->netAmount->toBeFloat()
        ->grossAmount->toBeFloat()
        ->grossAmountInBaseCurrency->toBeFloat()
        ->marginInBaseCurrency->toBeFloat()
        ->marginPercentage->toBeFloat()
        ->vatAmount->toBeFloat()
        ->roundingAmount->toBeFloat()
        ->costPriceInBaseCurrency->toBeFloat()
        ->dueDate->toBeInstanceOf(DateTime::class)
        ->paymentTerms->toBeInstanceOf(PaymentTerm::class)
        ->customer->toBeInstanceOf(Customer::class)
        ->recipient->toBeInstanceOf(Recipient::class)
        ->notes->toBeInstanceOf(Note::class)
        ->references->toBeInstanceOf(Reference::class)
        ->layout->toBeInstanceOf(Layout::class)
        ->paymentTerms->toBeInstanceOf(PaymentTerm::class)
        ->lines->toBeInstanceOf(Collection::class)
        ->lines->first()->toBeInstanceOf(ProductLine::class);
});

it('gets all drafted invoices', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/invoices/drafts',
        [
            'pageSize' => 20,
            'skipPages' => 0,
        ]
    )->andReturn(new EconomicResponse(200, fixture('Invoices/draft/get-collection')));

    $invoices = DraftInvoice::all();

    expect($invoices)->toBeInstanceOf(EconomicCollection::class);
    expect($invoices->first())->toBeInstanceOf(DraftInvoice::class);
    expect($invoices->all())->toHaveCount(2);
});

it('creates a draft invoice using create', function () {
    $this->driver->expects()->post()->with(
        'https://restapi.e-conomic.com/invoices/drafts',
        fixture('Invoices/draft/create-request'),
        null
    )->andReturn(new EconomicResponse(201, fixture('Invoices/draft/create-response')));

    $invoice = DraftInvoice::create(
        'DKK',
        1,
        new DateTime('2024-02-13T12:20:18+00:00'),
        14,
        1,
        Recipient::new(
            'John Doe',
            new VatZone(1),
        ),
        [
            ProductLine::new(
                description: 'T-shirt - Size L',
                product: new Product([
                    'productNumber' => 1,
                ]),
                quantity: 1,
                unitNetPrice: 500
            ),
        ],
        notes: Note::new(
            heading: 'Heading',
            textLine1: 'Text line 1',
            textLine2: 'Text line 2'
        )
    );

    expect($invoice)
        ->toBeInstanceOf(DraftInvoice::class)
        ->draftInvoiceNumber->toBe(424);
});

it('creates a draft invoice using new and save', function () {
    $this->driver->expects()->post()->with(
        'https://restapi.e-conomic.com/invoices/drafts',
        fixture('Invoices/draft/create-request'),
        null
    )->andReturn(new EconomicResponse(201, fixture('Invoices/draft/create-response')));

    $invoice = DraftInvoice::new(
        'DKK',
        1,
        new DateTime('2024-02-13T12:20:18+00:00'),
        14,
        1,
        Recipient::new(
            'John Doe',
            new VatZone(1),
        ),
        notes: Note::new(
            heading: 'Heading',
            textLine1: 'Text line 1',
            textLine2: 'Text line 2'
        )
    );

    $invoice->addLine(
        ProductLine::new(
            description: 'T-shirt - Size L',
            product: new Product([
                'productNumber' => 1,
            ]),
            quantity: 1,
            unitNetPrice: 500
        )
    );

    $invoice->save();

    expect($invoice)
        ->toBeInstanceOf(DraftInvoice::class)
        ->draftInvoiceNumber->toBe(424);
});

it('creates a draft invoice with full customer and currency object', function () {
    $this->driver->expects()->post()->with(
        'https://restapi.e-conomic.com/invoices/drafts',
        fixture('Invoices/draft/create-request'),
        null
    )->andReturn(new EconomicResponse(201, fixture('Invoices/draft/create-response')));

    $invoice = DraftInvoice::new(
        new Currency([
            'code' => 'DKK',
            'isoNumber' => 'DKK',
            'name' => 'Danish Krone',
        ]),
        Customer::new(
            name: 'John Doe',
            customerNumber: 1,
            currency: 'DKK',
            email: 'ms@morningtrain.dk',
            address: 'Test Street 1',
            vatZone: 1,
            customerGroup: 1,
            paymentTerms: 1,
        ),
        new DateTime('2024-02-13T12:20:18+00:00'),
        14,
        1,
        Recipient::new(
            'John Doe',
            new VatZone(1),
        ),
        notes: Note::new(
            heading: 'Heading',
            textLine1: 'Text line 1',
            textLine2: 'Text line 2'
        )
    );

    $invoice->addLine(
        ProductLine::new(
            description: 'T-shirt - Size L',
            product: new Product([
                'productNumber' => 1,
            ]),
            quantity: 1,
            unitNetPrice: 500
        )
    );

    $invoice->save();

    expect($invoice)
        ->toBeInstanceOf(DraftInvoice::class)
        ->draftInvoiceNumber->toBe(424);
});

it('books draft invoice', function () {
    $this->driver->expects()->post(
        'https://restapi.e-conomic.com/invoices/booked',
        fixture('Invoices/draft/book-request'),
        null
    )
        ->once()
        ->andReturn(new EconomicResponse(
            201,
            fixture('Invoices/draft/book-response')
        ));

    $invoice = new DraftInvoice(424);

    $bookedInvoice = $invoice->book();

    expect($bookedInvoice)
        ->toBeInstanceOf(BookedInvoice::class)
        ->pdf->toBeInstanceOf(Pdf::class)
        ->bookedInvoiceNumber->toBe(300);
});

it('can add lines', function () {
    $invoice = DraftInvoice::new('DKK',
        1,
        new DateTime('2024-02-13T12:20:18+00:00'),
        14,
        1,
        Recipient::new(
            'John Doe',
            new VatZone(1),
        )
    );

    $invoice->addLine(ProductLine::new(
        product: new Product(1),
        quantity: 1,
        unitNetPrice: 500
    ))->addLines([
        [
            'product' => new Product(2),
            'quantity' => 2,
            'unitNetPrice' => 100,
            'description' => 'Some description',
        ],
        ProductLine::new(
            product: new Product(3),
            quantity: 3,
            unitNetPrice: 200,
            description: 'Some description',
        ),
    ]);

    expect($invoice->lines)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(3);

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
        ->quantity->toBe(2.0);
});

it('can add notes', function () {
    $invoice = DraftInvoice::new(
        'DKK',
        1,
        new DateTime('2024-02-13T12:20:18+00:00'),
        14,
        1,
        Recipient::new(
            'John Doe',
            new VatZone(1),
        ),
        notes: Note::new(
            heading: 'Heading',
            textLine1: 'Text line 1',
            textLine2: 'Text line 2'
        )
    );

    expect($invoice->notes)
        ->toBeInstanceOf(Note::class)
        ->heading->toBe('Heading')
        ->textLine1->toBe('Text line 1')
        ->textLine2->toBe('Text line 2');
});

it('can set idempotency key when creating a draft', function () {
    $this->driver->expects()->post()->with(
        'https://restapi.e-conomic.com/invoices/drafts',
        fixture('Invoices/draft/create-request'),
        'test-idempotency-key'
    )->andReturn(new EconomicResponse(201, fixture('Invoices/draft/create-response')));

    $invoice = DraftInvoice::new(
        new Currency([
            'code' => 'DKK',
            'isoNumber' => 'DKK',
            'name' => 'Danish Krone',
        ]),
        Customer::new(
            name: 'John Doe',
            customerNumber: 1,
            currency: 'DKK',
            email: 'ms@morningtrain.dk',
            address: 'Test Street 1',
            vatZone: 1,
            customerGroup: 1,
            paymentTerms: 1,
        ),
        new DateTime('2024-02-13T12:20:18+00:00'),
        14,
        1,
        Recipient::new(
            'John Doe',
            new VatZone(1),
        ),
        notes: Note::new(
            heading: 'Heading',
            textLine1: 'Text line 1',
            textLine2: 'Text line 2'
        )
    );

    $invoice->addLine(
        ProductLine::new(
            description: 'T-shirt - Size L',
            product: new Product([
                'productNumber' => 1,
            ]),
            quantity: 1,
            unitNetPrice: 500
        )
    );

    $invoice->save('test-idempotency-key');
});

it('books draft invoice with idempotency key', function () {
    $this->driver->expects()->post(
        'https://restapi.e-conomic.com/invoices/booked',
        fixture('Invoices/draft/book-request'),
        'test-idempotency-key'
    )
        ->once()
        ->andReturn(new EconomicResponse(
            201,
            fixture('Invoices/draft/book-response')
        ));

    $invoice = new DraftInvoice(424);

    $bookedInvoice = $invoice->book('test-idempotency-key');
});
