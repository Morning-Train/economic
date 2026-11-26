<?php

use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Enums\SendBy;
use Morningtrain\Economic\Resources\Invoice\BookedInvoice;
use Morningtrain\Economic\Resources\Invoice\DraftInvoice;

function expectBooking(object $driver, array $body, ?string $idempotencyKey = null): void
{
    $driver->expects()->post(
        'https://restapi.e-conomic.com/invoices/booked',
        $body,
        $idempotencyKey
    )
        ->once()
        ->andReturn(new EconomicResponse(201, fixture('Invoices/draft/book-response')));
}

it('books without sendBy exactly as before', function (string $call) {
    expectBooking($this->driver, ['draftInvoice' => ['draftInvoiceNumber' => 7]]);

    $bookedInvoice = match ($call) {
        'book' => (new DraftInvoice(7))->book(),
        'createFromDraft with a number' => BookedInvoice::createFromDraft(7),
        'createFromDraft with a draft' => BookedInvoice::createFromDraft(new DraftInvoice(7)),
    };

    expect($bookedInvoice)->toBeInstanceOf(BookedInvoice::class);
})->with([
    'book',
    'createFromDraft with a number',
    'createFromDraft with a draft',
]);

it('books without sendBy but with an idempotency key exactly as before', function () {
    expectBooking($this->driver, ['draftInvoice' => ['draftInvoiceNumber' => 7]], 'key-1');

    (new DraftInvoice(7))->book('key-1');
});

it('asks e-conomic to send the invoice when booking with sendBy', function (SendBy $sendBy, string $value) {
    expectBooking($this->driver, [
        'draftInvoice' => ['draftInvoiceNumber' => 7],
        'sendBy' => $value,
    ], 'key-1');

    expect((new DraftInvoice(7))->book('key-1', $sendBy))->toBeInstanceOf(BookedInvoice::class);
})->with([
    'Email' => [SendBy::Email, 'Email'],
    'EAN' => [SendBy::Ean, 'EAN'],
    'none' => [SendBy::None, 'none'],
]);

it('passes sendBy and the idempotency key on from createFromDraft', function (SendBy $sendBy, string $value) {
    expectBooking($this->driver, [
        'draftInvoice' => ['draftInvoiceNumber' => 7],
        'sendBy' => $value,
    ], 'key-2');

    BookedInvoice::createFromDraft(7, 'key-2', $sendBy);
})->with([
    'Email' => [SendBy::Email, 'Email'],
    'EAN' => [SendBy::Ean, 'EAN'],
]);

it('takes sendBy as a named argument without an idempotency key', function () {
    expectBooking($this->driver, [
        'draftInvoice' => ['draftInvoiceNumber' => 7],
        'sendBy' => 'Email',
    ]);

    (new DraftInvoice(7))->book(sendBy: SendBy::Email);
});

it('uses the values of e-conomic\'s booking schema', function () {
    expect(array_map(fn (SendBy $case) => $case->value, SendBy::cases()))
        ->toBe(['none', 'EAN', 'Email']);
});
