<?php

namespace MorningTrain\Economic\Traits\Resources;

use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Services\EconomicApiService;
use MorningTrain\Economic\Services\EconomicLoggerService;
use Exception;

trait GetSingleable
{
    use EndpointResolvable;

    /**
     * @param ...$references - The references to pass to the endpoint. Can be string(s), integer(s) or a single array with named references
     * @return static|null
     * @throws \Exception
     */
    public static function find(...$references): ?static
    {
        $response = EconomicApiService::get(static::getEndpoint(GetSingle::class, $references));

        // If status code is 404, the resource does not exist
        if($response->getStatusCode() === 404) {
            return null;
        }

        if($response->getStatusCode() !== 200) {
            EconomicLoggerService::error('Economic API Service returned a non 200 status code when trying to find a resource', [
                'status_code' => $response->getStatusCode(),
                'response_body' => $response->getBody(),
                'resource' => static::class,
                'references' => $references,
            ]);

            throw new Exception('Economic API Service returned a non 200 status code when trying to find a resource');
        }

        return new static($response->getBody());
    }
}
