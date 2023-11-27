<?php

namespace MorningTrain\Economic\Traits\Resources;

use Exception;
use MorningTrain\Economic\Attributes\Resources\Update;
use MorningTrain\Economic\Services\EconomicApiService;
use MorningTrain\Economic\Services\EconomicLoggerService;

trait Updatable
{
    use EndpointResolvable;

    public function save()
    {
        // TODO: add validation method to check if required properties are set and primary key is set - throw exception if not

        $response = EconomicApiService::put(static::getEndpoint(Update::class, $this->getPrimaryKey()), $this->toArray());

        if ($response->getStatusCode() !== 200) {
            EconomicLoggerService::error('Economic API Service returned a non 200 status code when updating a resource', [
                'status_code' => $response->getStatusCode(),
                'response_body' => $response->getBody(),
                'resource' => static::class,
                'args' => $this->toArray(),
            ]);

            throw new Exception('Economic API Service returned a non 200 status code when updating a resource');
        }

        return true;
    }
}
