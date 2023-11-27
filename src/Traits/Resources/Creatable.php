<?php

namespace MorningTrain\Economic\Traits\Resources;

use MorningTrain\Economic\Attributes\Resources\Create;
use MorningTrain\Economic\Services\EconomicApiService;
use MorningTrain\Economic\Services\EconomicLoggerService;
use Exception;

trait Creatable
{
    /**
     * @param static|array $args
     * @param array $endpointReferences
     * @return static|null
     */
    public static function createRequest($args, array $endpointReferences = []): ?static
    {
        // TODO: add validation method to check if required properties are set and primary key is not set - throw exception if not

        $response = EconomicApiService::post(static::getEndpoint(Create::class, ...$endpointReferences), $args);

        if($response->getStatusCode() !== 201) {
            EconomicLoggerService::error('Economic API Service returned a non 201 status code when creating a resource', [
                'status_code' => $response->getStatusCode(),
                'response_body' => $response->getBody(),
                'resource' => static::class,
                'args' => $args,
                'endpoint_references' => $endpointReferences,
            ]);

            throw new Exception('Economic API Service returned a non 201 status code when creating a resource');
        }

        return new static($response->getBody());
    }
}
