<?php

namespace Morningtrain\Economic\Classes;

use Closure;
use Morningtrain\Economic\Abstracts\Endpoint;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Services\EconomicApiService;

/**
 * @template ResourceClass
 */
class EconomicQueryBuilder
{
    protected array $queryArgs = [];

    protected ?EconomicQueryFilterBuilder $filter;

    protected string|Endpoint $endpoint;

    public function __construct(protected string $resourceClass)
    {
    }

    public function setEndpoint(string|Endpoint $endpoint): static
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    protected function getEndpoint(): string
    {
        if (! isset($this->endpoint)) {
            return $this->resourceClass::getEndpoint(GetCollection::class);
        }

        if (is_a($this->endpoint, Endpoint::class)) {
            return $this->endpoint->getEndpoint();
        }

        return $this->endpoint;
    }

    public function where(int|string|Closure $propertyName, ?string $operatorOrValue = null, mixed $value = null): static
    {
        if (! isset($this->filter)) {
            $this->filter = new EconomicQueryFilterBuilder(EconomicQueryFilterBuilder::FILTER_RELATION_AND);
        }

        $this->filter->where($propertyName, $operatorOrValue, $value);

        return $this;
    }

    public function orWhere(int|string|Closure $propertyName, ?string $operatorOrValue = null, mixed $value = null): static
    {
        if (! isset($this->filter)) {
            $this->filter = new EconomicQueryFilterBuilder(EconomicQueryFilterBuilder::FILTER_RELATION_OR);
        }

        $this->filter->orWhere($propertyName, $operatorOrValue, $value);

        return $this;
    }

    public function first()
    {
        $args = array_merge($this->queryArgs, [
            'pageSize' => 1,
            'skipPages' => 0,
        ]);

        if (! empty($this->filter)) {
            $args['filter'] = $this->filter->buildString();
        }

        $response = EconomicApiService::get($this->getEndpoint(), $args);

        if ($response->getStatusCode() !== 200) {
            // Todo: Log error and throw exception

            return null;
        }

        if (empty($response->getProperty('collection'))) {
            return null;
        }

        return new $this->resourceClass(array_values($response->getProperty('collection'))[0]);
    }

    /**
     * @return ResourceClass|mixed|null
     */
    public function firstOr(callable $callback): mixed
    {
        if (! is_null($resource = $this->first())) {
            return $resource;
        }

        return $callback();
    }

    /**
     * @return EconomicCollection<ResourceClass>
     */
    public function all(): EconomicCollection
    {
        $self = $this->getEndpoint();

        $args = $this->queryArgs;

        if (! empty($this->filter)) {
            $args['filter'] = $this->filter->buildString();
        }

        if (! empty($args)) {
            $self .= '?'.http_build_query($args);
        }

        return new EconomicCollection(new EconomicCollectionIterator($self, $this->resourceClass));
    }
}
