<?php

namespace MorningTrain\Economic\Interfaces;

use MorningTrain\Economic\Classes\EconomicResponse;

interface EconomicDriver
{
    public function get(string $url, array $queryArgs = []): EconomicResponse;

    public function post(string $url, array $body = []): EconomicResponse;

    public function put(string $url, array $body = []): EconomicResponse;

    public function delete(string $url): EconomicResponse;

    public function patch(string $url): EconomicResponse;
}
