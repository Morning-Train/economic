<?php

namespace Morningtrain\Economic\Traits\Resources;

use Morningtrain\Economic\Attributes\Resources\Delete;
use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Services\EconomicApiService;

trait Deletable
{
    use EndpointResolvable;

    public function delete(): bool
    {
        // Without a primary key the endpoint resolves to the collection, and for
        // some resources (e.g. DELETE /invoices/drafts) that deletes every item.
        if (self::isEmptyPrimaryKey($this->getPrimaryKey())) {
            return false;
        }

        return self::isSuccessfulDelete(EconomicApiService::delete(static::getEndpoint(Delete::class, $this)));
    }

    public static function deleteByPrimaryKey(int|string $primaryKey): bool
    {
        if (self::isEmptyPrimaryKey($primaryKey)) {
            return false;
        }

        return self::isSuccessfulDelete(EconomicApiService::delete(static::getEndpoint(Delete::class, $primaryKey)));
    }

    private static function isEmptyPrimaryKey(mixed $primaryKey): bool
    {
        return $primaryKey === null || $primaryKey === '';
    }

    /**
     * Most endpoints answer 204 No Content, but some (e.g. DELETE /invoices/drafts/:draftInvoiceNumber) answer 200 with a status message.
     */
    private static function isSuccessfulDelete(EconomicResponse $response): bool
    {
        // TODO: Log error and throw exception on failure
        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }
}
