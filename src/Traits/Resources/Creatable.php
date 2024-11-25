<?php

namespace Morningtrain\Economic\Traits\Resources;

use Exception;
use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Services\EconomicApiService;
use Morningtrain\Economic\Services\EconomicLoggerService;

trait Creatable
{
    /**
     * @param  static|array  $args
     */
    public static function createRequest($args, array $endpointReferences = [], ?string $idempotencyKey = null): ?static
    {
        // TODO: add validation method to check if required properties are set and primary key is not set - throw exception if not

        $args = static::resolveArgs($args, true); // We need to convert objects to an array to avoid issues with the API

        $response = EconomicApiService::post(
            static::getEndpoint(Create::class, ...$endpointReferences),
            $args,
            $idempotencyKey
        );

        if ($response->getStatusCode() !== 201) {
            EconomicLoggerService::error('Economic API Service returned a non 201 status code when creating a resource',
                [
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
