<?php

namespace Morningtrain\Economic\Tests;

use MorningTrain\Economic\Classes\EconomicResponse;
use MorningTrain\Economic\Interfaces\EconomicDriver;

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
    }

    public function post(string $url, array $body = []): EconomicResponse
    {
        // TODO: Implement post() method.
    }

    public function put(string $url, array $body = []): EconomicResponse
    {
        // TODO: Implement put() method.
    }

    public function delete(string $url): EconomicResponse
    {
        // TODO: Implement delete() method.
    }

    public function patch(string $url): EconomicResponse
    {
        // TODO: Implement patch() method.
    }
}
