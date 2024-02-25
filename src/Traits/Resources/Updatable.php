<?php

namespace Morningtrain\Economic\Traits\Resources;

use Exception;
use Morningtrain\Economic\Attributes\Resources\Update;
use Morningtrain\Economic\Services\EconomicApiService;
use Morningtrain\Economic\Services\EconomicLoggerService;

trait Updatable
{
    use EndpointResolvable;

    public function save(): static
    {
        // TODO: add validation method to check if required properties are set and primary key is set - throw exception if not

        $response = EconomicApiService::put(static::getEndpoint(Update::class, $this), $this->toArray(true));

        if ($response->getStatusCode() !== 200) {
            EconomicLoggerService::error('Economic API Service returned a non 200 status code when updating a resource', [
                'status_code' => $response->getStatusCode(),
                'response_body' => $response->getBody(),
                'resource' => static::class,
                'args' => $this->toArray(true),
            ]);

            throw new Exception('Economic API Service returned a non 200 status code when updating a resource');
        }

        $this->populate($response->getBody());

        return $this;
    }
}
