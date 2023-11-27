<?php

namespace MorningTrain\Economic\Resources;

use DateTime;
use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Services\EconomicApiService;
use MorningTrain\Economic\Traits\Resources\EndpointResolvable;

#[GetSingle('')]
class Root extends Resource
{
    use EndpointResolvable;

    public string $apiName;

    public string $version;

    public DateTime $serverTime;

    public string $dateTimeZone;

    public array $company;

    public array $gettingStarted;

    public array $resources;

    public static function get(): ?static
    {
        $response = EconomicApiService::get(static::getEndpoint(GetSingle::class));

        if ($response->getStatusCode() !== 200) {
            // Todo: Log error and throw exception

            return null;
        }

        return new static($response->getBody());
    }
}
