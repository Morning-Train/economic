<?php

namespace Morningtrain\Economic\Classes;

use Exception;
use Morningtrain\Economic\Abstracts\Endpoint;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Services\EconomicApiService;

/**
 * @template R of Resource
 */
class EconomicRelatedResource
{
    public function __construct(protected Endpoint $endpointCollection, protected Endpoint $endpointSingle, protected string $resourceClass, protected array $references)
    {
    }

    /**
     * @return R|null
     *
     * @throws Exception
     */
    public function find(string|int $reference): ?Resource
    {
        $references = array_merge($this->references, [$reference]);

        $response = EconomicApiService::get($this->endpointSingle->getEndpoint(array_values($references)));

        if ($response->getStatusCode() !== 200) {
            // Todo: Log error and throw exception

            return null;
        }

        return new $this->resourceClass($response->getBody());
    }

    /**
     * @return EconomicQueryBuilder<R>
     */
    public function where(int|string $propertyName, string $operatorOrValue, mixed $value = null): EconomicQueryBuilder
    {
        return (new EconomicQueryBuilder($this->resourceClass))->setEndpoint($this->endpointCollection->getEndpoint($this->references))->where($propertyName, $operatorOrValue, $value);

    }

    public function first(): Resource
    {
        return (new EconomicQueryBuilder($this->resourceClass))->setEndpoint($this->endpointCollection->getEndpoint($this->references))->first();
    }

    /**
     * @return EconomicCollection<R>
     */
    public function all(): EconomicCollection
    {
        return (new EconomicQueryBuilder($this->resourceClass))->setEndpoint($this->endpointCollection->getEndpoint($this->references))->all();
    }
}
