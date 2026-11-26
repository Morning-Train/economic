<?php

namespace Morningtrain\Economic\Resources\Invoice;

use Exception;
use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Enums\SendBy;
use Morningtrain\Economic\Services\EconomicApiService;
use Morningtrain\Economic\Services\EconomicLoggerService;

#[GetCollection('invoices/booked')]
#[GetSingle('invoices/booked/:bookedInvoiceNumber')]
#[Create('invoices/booked')]
class BookedInvoice extends Invoice
{
    public ?int $bookedInvoiceNumber = null;

    public ?int $orderNumber = null;

    public ?float $remainder = null;

    public ?float $remainderInBaseCurrency = null;

    /** Link to the sent state of this invoice */
    public ?string $sent = null;

    /**
     * Book a draft invoice.
     *
     * @param  SendBy|null  $sendBy  - Let e-conomic send the invoice to the recipient once it is booked.
     *                               When null, "sendBy" is left out of the request and the invoice is not sent.
     */
    public static function createFromDraft(int|DraftInvoice $draft, ?string $idempotencyKey = null, ?SendBy $sendBy = null)
    {
        $body = [
            'draftInvoice' => [
                'draftInvoiceNumber' => is_int($draft) ? $draft : $draft->draftInvoiceNumber,
            ],
        ];

        if ($sendBy !== null) {
            $body['sendBy'] = $sendBy->value;
        }

        return static::createRequest($body, [], $idempotencyKey);
    }

    /**
     * Get how this invoice was sent, by following its own "sent" link, or null if it was not sent.
     *
     * The link is https://restapi.e-conomic.com/invoices/booked/:bookedInvoiceNumber/sent. e-conomic answers it
     * with a collection of sent invoices in the same form as GET /invoices/sent, holding only the ones for this
     * invoice: an empty collection (200) when the invoice was not sent, and one item when it was. It also answers
     * an empty collection for a booked invoice number that does not exist. This returns the first item.
     *
     * Only a link to e-conomic's own API (https://restapi.e-conomic.com/) is followed, and only its path and
     * query are passed on, so the request goes through the driver to e-conomic like any other.
     * Returns null when the invoice has no such link.
     *
     * @throws Exception when e-conomic answers with anything other than 200 or 404
     */
    public function sentInvoice(): ?SentInvoice
    {
        $link = static::parseApiLink($this->sent);

        if ($link === null) {
            return null;
        }

        [$path, $queryArgs] = $link;

        $response = EconomicApiService::get($path, $queryArgs);

        if ($response->getStatusCode() === 404) {
            return null;
        }

        if ($response->getStatusCode() !== 200) {
            EconomicLoggerService::error('Economic API Service returned a non 200 status code when getting the sent state of a booked invoice', [
                'status_code' => $response->getStatusCode(),
                'response_body' => $response->getBody(),
                'resource' => static::class,
                'booked_invoice_number' => $this->bookedInvoiceNumber,
            ]);

            throw new Exception('Economic API Service returned a non 200 status code when getting the sent state of a booked invoice');
        }

        $collection = $response->getProperty('collection');

        if (! is_array($collection) || empty($collection[0])) {
            return null;
        }

        return new SentInvoice($collection[0]);
    }

    /**
     * Split a link to e-conomic's API into its path and query arguments.
     * Returns null for an empty link and for a link to anywhere else.
     *
     * @return array{0: string, 1: array<string, mixed>}|null
     */
    protected static function parseApiLink(?string $link): ?array
    {
        if ($link === null || $link === '') {
            return null;
        }

        $parts = parse_url($link);

        if (
            $parts === false
            || strtolower($parts['scheme'] ?? '') !== 'https'
            || strtolower($parts['host'] ?? '') !== 'restapi.e-conomic.com'
            || isset($parts['port'])
            || isset($parts['user'])
            || isset($parts['pass'])
            || empty($parts['path'])
            || $parts['path'] === '/'
        ) {
            EconomicLoggerService::warning('Not following a link that does not point to e-conomic\'s API', [
                'link' => $link,
                'resource' => static::class,
            ]);

            return null;
        }

        parse_str($parts['query'] ?? '', $queryArgs);

        return [$parts['path'], $queryArgs];
    }
}
