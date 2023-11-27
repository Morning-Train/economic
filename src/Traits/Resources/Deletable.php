<?php

namespace MorningTrain\Economic\Traits\Resources;

use MorningTrain\Economic\Attributes\Resources\Delete;
use MorningTrain\Economic\Services\EconomicApiService;

trait Deletable
{
    use EndpointResolvable;

    public function delete(): bool
    {
        $response = EconomicApiService::delete(static::getEndpoint(Delete::class, $this->getPrimaryKey()));

        if($response->getStatusCode() !== 204) {
            // TODO: Log error and throw exception

            return false;
        }

        return true;
    }
}
