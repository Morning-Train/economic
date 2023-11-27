<?php

namespace MorningTrain\Economic\Traits\Resources;

use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Classes\EconomicCollection;
use MorningTrain\Economic\Classes\EconomicQueryBuilder;
use MorningTrain\Economic\Services\EconomicApiService;

/**
 * @template ResourceClass
 */
trait GetCollectionable
{
    use EndpointResolvable;

    /**
     * @param int|string $propertyName
     * @param string $operatorOrValue
     * @param mixed|null $value
     * @return EconomicQueryBuilder<ResourceClass>
     */
    public static function where(int|string $propertyName, string $operatorOrValue, mixed $value = null): EconomicQueryBuilder
    {
        return (new EconomicQueryBuilder(static::class))->where($propertyName, $operatorOrValue, $value);
    }

    public static function first(): ?static
    {
        return (new EconomicQueryBuilder(static::class))->first();
    }

    /**
     * @return EconomicCollection<ResourceClass>
     */
    public static function all(): EconomicCollection
    {
        return (new EconomicQueryBuilder(static::class))->all();
    }
}
