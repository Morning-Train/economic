<?php

namespace Morningtrain\Economic\Tests;

use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Interfaces\EconomicDriver;

class DummyEconomicDriver implements EconomicDriver
{
    protected string $appSecretToken;

    protected string $agreementGrantToken;

    public function __construct(string $appSecretToken, string $agreementGrantToken)
    {
        $this->appSecretToken = $appSecretToken;
        $this->agreementGrantToken = $agreementGrantToken;
    }

    public function get(string $url, array $queryArgs = []): EconomicResponse
    {
        // TODO: Implement get() method.
        ray('GET', func_get_args());
    }

    public function post(string $url, array $body = [], ?string $idempotencyKey = null): EconomicResponse
    {
        // TODO: Implement post() method.
        ray('POST', func_get_args());
    }

    public function put(string $url, array $body = [], ?string $idempotencyKey = null): EconomicResponse
    {
        // TODO: Implement put() method.
        ray('PUT', func_get_args());
    }

    public function delete(string $url): EconomicResponse
    {
        // TODO: Implement delete() method.
        ray('DELETE', func_get_args());
    }

    public function patch(string $url): EconomicResponse
    {
        // TODO: Implement patch() method.
        ray('PATCH', func_get_args());
    }
}
