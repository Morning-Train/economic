<?php

namespace MorningTrain\Economic\Traits\Resources;

use MorningTrain\Economic\Abstracts\Endpoint;
use Stringable;
use Exception;
use ReflectionClass;

trait EndpointResolvable
{
    /**
     * @param string $endpointAttributeClass - The attribute class to resolve
     * @param ...$references - The references to pass to the endpoint. Can be strings, integers or a single array with references
     * @return string
     * @throws Exception
     */
    public static function getEndpoint(string $endpointAttributeClass, ...$references): string
    {
        $instance = static::getEndpointInstance($endpointAttributeClass);

        if(!method_exists($instance, 'getEndpoint')) {
            throw new Exception('Endpoint attribute does not implement Endpoint');
        }

        return $instance->getEndpoint(...$references);
    }

    /**
     * @param string $slug - The attribute slug to resolve
     * @param ...$references - The references to pass to the endpoint. Can be strings, integers or a single array with references
     * @return string
     * @throws Exception
     */
    public static function getEndpointBySlug(string $slug, ...$references): string
    {
        $instance = static::getEndpointBySlug($slug);

        if(!method_exists($instance, 'getEndpoint')) {
            throw new Exception('Endpoint attribute does not implement Endpoint');
        }

        return $instance->getEndpoint(...$references);
    }

    public static function getEndpointInstance(string $endpointAttributeClass): Endpoint
    {
        $reflection = new ReflectionClass(static::class);

        $endpointAttribute = $reflection->getAttributes($endpointAttributeClass);

        if(empty($endpointAttribute[0])) {
            throw new Exception('Endpoint attribute not found');
        }

        return $endpointAttribute[0]->newInstance();
    }

    protected static function getEndpointInstanceBySlug(string $slug): Endpoint
    {
        $reflection = new ReflectionClass(static::class);

        $endpointAttribute = $reflection->getAttributes();

        if(empty($endpointAttribute)) {
            throw new Exception('Endpoint attribute not found');
        }

        foreach($endpointAttribute as $attribute) {
            $instance = $attribute->newInstance();

            if(method_exists($instance, 'is') && $instance->is($slug)) {
                return $instance;
            }
        }

        throw new Exception('Endpoint attribute not found');
    }
}
