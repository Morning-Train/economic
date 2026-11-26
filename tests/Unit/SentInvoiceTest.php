<?php

use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\DTOs\Invoice\SentInvoiceRecipient;
use Morningtrain\Economic\Resources\Invoice\BookedInvoice;
use Morningtrain\Economic\Resources\Invoice\SentInvoice;

/**
 * A sent invoice in the form e-conomic returns it (note "sentBy", where the schema says "sendBy").
 */
function sentInvoiceData(int $id, int $bookedInvoiceNumber = 300): array
{
    return [
        'id' => $id,
        'invoice' => [
            'bookedInvoiceNumber' => $bookedInvoiceNumber,
            'self' => "https://restapi.e-conomic.com/invoices/booked/$bookedInvoiceNumber",
        ],
        'status' => 'received',
        'sentBy' => 'ean',
        'creationDate' => '2026-11-20',
        'createdBy' => 'API',
        'recipient' => [
            'ean' => '5790000000000',
        ],
        'self' => "https://restapi.e-conomic.com/invoices/sent/$id",
    ];
}

/**
 * A collection as e-conomic answers both GET /invoices/sent and a booked invoice's "sent" link.
 */
function sentInvoiceCollection(array $items, string $self, int $results, int $skipPages = 0): array
{
    return [
        'collection' => $items,
        'pagination' => [
            'skipPages' => $skipPages,
            'pageSize' => 20,
            'maxPageSizeAllowed' => 1000,
            'results' => $results,
            'resultsWithoutFilter' => $results,
        ],
        'self' => $self,
    ];
}

it('reads all sent invoices page by page', function () {
    $firstPage = array_map(fn (int $id) => sentInvoiceData($id), range(1, 20));

    $this->driver->expects()->get('https://restapi.e-conomic.com/invoices/sent', [
        'skipPages' => 0,
        'pageSize' => 20,
    ])
        ->once()
        ->andReturn(new EconomicResponse(200, sentInvoiceCollection($firstPage, 'https://restapi.e-conomic.com/invoices/sent', 21)));

    $this->driver->expects()->get('https://restapi.e-conomic.com/invoices/sent', [
        'skipPages' => 1,
        'pageSize' => 20,
    ])
        ->once()
        ->andReturn(new EconomicResponse(200, sentInvoiceCollection([sentInvoiceData(21)], 'https://restapi.e-conomic.com/invoices/sent', 21, 1)));

    $sentInvoices = SentInvoice::all();

    expect($sentInvoices)->toBeInstanceOf(EconomicCollection::class);

    $ids = [];

    foreach ($sentInvoices as $sentInvoice) {
        expect($sentInvoice)->toBeInstanceOf(SentInvoice::class);

        $ids[] = $sentInvoice->id;
    }

    expect($ids)->toBe(range(1, 21));
});

it('finds a single sent invoice with its properties', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/invoices/sent/5', [])
        ->once()
        ->andReturn(new EconomicResponse(200, sentInvoiceData(5)));

    $logs = economicLogs(function () use (&$sentInvoice) {
        $sentInvoice = SentInvoice::find(5);
    });

    expect($logs)->toBeEmpty();

    expect($sentInvoice)
        ->toBeInstanceOf(SentInvoice::class)
        ->id->toBe(5)
        ->getPrimaryKey()->toBe(5)
        ->status->toBe('received')
        ->sentBy->toBe('ean')
        ->sendBy->toBeNull()
        ->createdBy->toBe('API')
        ->self->toBe('https://restapi.e-conomic.com/invoices/sent/5');

    expect($sentInvoice->creationDate)->toBeInstanceOf(DateTime::class);
    expect($sentInvoice->creationDate->format('Y-m-d'))->toBe('2026-11-20');

    expect($sentInvoice->invoice)
        ->toBeInstanceOf(BookedInvoice::class)
        ->bookedInvoiceNumber->toBe(300)
        ->self->toBe('https://restapi.e-conomic.com/invoices/booked/300');

    expect($sentInvoice->recipient)
        ->toBeInstanceOf(SentInvoiceRecipient::class)
        ->ean->toBe('5790000000000')
        ->name->toBeNull()
        ->mobilePhone->toBeNull();
});

it('also takes sendBy as e-conomic\'s schema names it', function () {
    $data = sentInvoiceData(5);
    unset($data['sentBy']);
    $data['sendBy'] = 'mobilePay';

    $sentInvoice = new SentInvoice($data);

    expect($sentInvoice)
        ->sendBy->toBe('mobilePay')
        ->sentBy->toBeNull();
});

it('returns null when a sent invoice does not exist', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/invoices/sent/999', [])
        ->once()
        ->andReturn(new EconomicResponse(404, ['message' => 'Sent invoice 999 was not found!']));

    expect(SentInvoice::find(999))->toBeNull();
});

it('follows a booked invoice\'s sent link to the invoice it was sent as', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/invoices/booked/300/sent', [])
        ->once()
        ->andReturn(new EconomicResponse(200, sentInvoiceCollection(
            [sentInvoiceData(5)],
            'https://restapi.e-conomic.com/invoices/booked/300/sent',
            1
        )));

    $bookedInvoice = new BookedInvoice(fixture('Invoices/draft/book-response'));

    expect($bookedInvoice->sent)->toBe('https://restapi.e-conomic.com/invoices/booked/300/sent');

    expect($bookedInvoice->sentInvoice())
        ->toBeInstanceOf(SentInvoice::class)
        ->id->toBe(5)
        ->sentBy->toBe('ean')
        ->invoice->bookedInvoiceNumber->toBe(300);
});

it('passes the sent link\'s query on as arguments', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/invoices/booked/300/sent', ['demo' => 'true'])
        ->once()
        ->andReturn(new EconomicResponse(200, sentInvoiceCollection(
            [sentInvoiceData(5)],
            'https://restapi.e-conomic.com/invoices/booked/300/sent',
            1
        )));

    $bookedInvoice = new BookedInvoice([
        'bookedInvoiceNumber' => 300,
        'sent' => 'https://restapi.e-conomic.com/invoices/booked/300/sent?demo=true',
    ]);

    expect($bookedInvoice->sentInvoice())->toBeInstanceOf(SentInvoice::class);
});

it('returns null for a booked invoice that was not sent', function () {
    // e-conomic answers an empty collection with 200, as the demo agreement does for its unsent invoices.
    $this->driver->expects()->get('https://restapi.e-conomic.com/invoices/booked/300/sent', [])
        ->once()
        ->andReturn(new EconomicResponse(200, sentInvoiceCollection(
            [],
            'https://restapi.e-conomic.com/invoices/booked/300/sent',
            0
        )));

    $bookedInvoice = new BookedInvoice([
        'bookedInvoiceNumber' => 300,
        'sent' => 'https://restapi.e-conomic.com/invoices/booked/300/sent',
    ]);

    expect($bookedInvoice->sentInvoice())->toBeNull();
});

it('returns null when the sent link answers 404', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/invoices/booked/300/sent', [])
        ->once()
        ->andReturn(new EconomicResponse(404, []));

    $bookedInvoice = new BookedInvoice([
        'bookedInvoiceNumber' => 300,
        'sent' => 'https://restapi.e-conomic.com/invoices/booked/300/sent',
    ]);

    expect($bookedInvoice->sentInvoice())->toBeNull();
});

it('throws when the sent link answers an error', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/invoices/booked/300/sent', [])
        ->once()
        ->andReturn(new EconomicResponse(500, []));

    $bookedInvoice = new BookedInvoice([
        'bookedInvoiceNumber' => 300,
        'sent' => 'https://restapi.e-conomic.com/invoices/booked/300/sent',
    ]);

    $bookedInvoice->sentInvoice();
})->throws(Exception::class);

it('returns null without calling e-conomic for a booked invoice without a sent link', function (?string $sent) {
    $this->driver->shouldNotReceive('get');

    $bookedInvoice = new BookedInvoice(['bookedInvoiceNumber' => 300, 'sent' => $sent]);

    expect($bookedInvoice->sentInvoice())->toBeNull();
})->with([
    'null' => [null],
    'empty' => [''],
]);

it('never follows a sent link to anywhere but e-conomic\'s API', function (string $sent) {
    $this->driver->shouldNotReceive('get');

    $bookedInvoice = new BookedInvoice(['bookedInvoiceNumber' => 300, 'sent' => $sent]);

    $logs = economicLogs(function () use ($bookedInvoice, &$sentInvoice) {
        $sentInvoice = $bookedInvoice->sentInvoice();
    });

    expect($sentInvoice)->toBeNull();
    expect($logs)->toHaveCount(1);
})->with([
    'another host' => ['https://example.com/invoices/booked/300/sent'],
    'a host that starts like e-conomic\'s' => ['https://restapi.e-conomic.com.example.com/invoices/booked/300/sent'],
    'credentials before another host' => ['https://restapi.e-conomic.com@example.com/invoices/booked/300/sent'],
    'plain http' => ['http://restapi.e-conomic.com/invoices/booked/300/sent'],
    'another port' => ['https://restapi.e-conomic.com:8443/invoices/booked/300/sent'],
    'protocol relative' => ['//example.com/invoices/booked/300/sent'],
    'a relative path' => ['invoices/booked/300/sent'],
    'only the base URL' => ['https://restapi.e-conomic.com/'],
]);
