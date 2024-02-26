<?php

namespace Morningtrain\Economic\Abstracts;

use DateTime;
use Exception;
use Illuminate\Support\Collection;
use JsonSerializable;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Attributes\Resources\Properties\ResourceType;
use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicCollectionIterator;
use Morningtrain\Economic\Interfaces\ApiFormatter;
use Morningtrain\Economic\Services\EconomicApiService;
use Morningtrain\Economic\Services\EconomicLoggerService;
use ReflectionAttribute;
use ReflectionClass;
use Throwable;

abstract class Resource implements JsonSerializable
{
    public string $self;

    public function __construct(array|string|int|float|null $properties = null)
    {
        if (is_array($properties)) {
            $this->populate($properties);
        } elseif (! empty($properties)) {
            $this->setPrimaryKey($properties);
        }
    }

    protected function populate(array $properties): void
    {
        foreach ($properties as $property => $value) {
            if (! property_exists($this, $property)) {
                EconomicLoggerService::warning('Property '.$property.' does not exist on '.static::class);

                continue;
            }

            $this->{$property} = static::resolvePropertyValue($property, $value);
        }
    }

    protected static function resolvePropertyValue(string $property, mixed $value): mixed
    {
        $reflection = new ReflectionClass(static::class);

        $propertyReflection = $reflection->getProperty($property);

        $reflectionTypeName = $propertyReflection->getType()->getName();

        // If is already the expected type
        if ($value instanceof $reflectionTypeName) {
            return $value;
        }

        // If is EconomicCollection
        if (is_a($reflectionTypeName, EconomicCollection::class, true)) {
            $attribute = $propertyReflection->getAttributes(ResourceType::class);

            if (! empty($attribute[0]) && ! empty($value)) {
                $resourceType = $attribute[0]->newInstance();

                return new EconomicCollection(new EconomicCollectionIterator($value, $resourceType->getTypeClass()));
            }
        }

        // If is Collection
        if (is_a($reflectionTypeName, Collection::class, true)) {
            $attribute = $propertyReflection->getAttributes(ResourceType::class);

            if (! empty($attribute[0]) && ! empty($value) && is_array($value)) {
                $resourceType = $attribute[0]->newInstance();

                $collection = new Collection();

                foreach ($value as $item) {
                    $collection->add(new ($resourceType->getTypeClass())($item));
                }

                return $collection;
            }
        }

        if (enum_exists($reflectionTypeName) && ! empty($value)) {
            return $reflectionTypeName::from($value);
        }

        // If is a class
        if (class_exists($reflectionTypeName) && ! empty($value)) {
            return new ($propertyReflection->getType()->getName())($value);
        }

        return $value;
    }

    public function getPrimaryKeyPropertyName(): ?string
    {
        $reflection = new ReflectionClass(static::class);

        foreach ($reflection->getProperties() as $property) {
            if (! empty($property->getAttributes(PrimaryKey::class))) {
                return $property->getName();
            }
        }

        return null;
    }

    public function getPrimaryKey(): mixed
    {
        $primaryKeyPropertyName = $this->getPrimaryKeyPropertyName();

        if (! empty($primaryKeyPropertyName) && isset($this->{$primaryKeyPropertyName})) {
            return $this->{$primaryKeyPropertyName};
        }

        return null;
    }

    public function setPrimaryKey(int|string|float $value): static
    {
        $primaryKeyPropertyName = $this->getPrimaryKeyPropertyName();

        if (! empty($primaryKeyPropertyName)) {
            $this->{$primaryKeyPropertyName} = $value;
        }

        return $this;
    }

    protected static function resolveArgs(array $args, bool $forApiRequest = false)
    {
        foreach ($args as $key => &$value) {
            if ($value === null) {
                if ($forApiRequest) {
                    unset($args[$key]); // We don't want to send null values to the API
                }

                continue; // We don't want to format null values
            }

            // Try to convert to resource
            try {
                $reflection = new ReflectionClass(static::class);

                $propertyReflection = $reflection->getProperty($key);

                $reflectionTypeName = $propertyReflection->getType()->getName();

                // If is a class
                if (
                    is_a($reflectionTypeName, Resource::class, true) &&
                    ! is_a($value, Resource::class)
                ) {
                    $value = new ($propertyReflection->getType()->getName())($value);
                }
            } catch (Exception $e) {
                // Do nothing, since we can't format the value
            }

            // If is for API request then we have some special format cases
            if ($forApiRequest) {
                // Format the value if it has a special case defined
                try {
                    $reflection = new ReflectionClass(static::class);

                    $propertyReflection = $reflection->getProperty($key);

                    $attribute = $propertyReflection->getAttributes(ApiFormatter::class, ReflectionAttribute::IS_INSTANCEOF);

                    if (! empty($attribute[0]) && ! empty($value)) {
                        $apiFormatter = $attribute[0]->newInstance();

                        $value = $apiFormatter->format($value);
                    }
                } catch (Exception $e) {
                    // Do nothing, since we can't format the value
                }
            }

            // If is EconomicCollection then only get the self property
            if (is_a($value, EconomicCollection::class)) {
                $value = $value->getSelf();
            }

            // If is Collection
            if (is_a($value, Collection::class)) {
                $value = $value->toArray();
            }

            // If is DateTime then format
            if (is_a($value, DateTime::class)) {
                $value = $value->format(DateTime::ATOM);
            }

            // If is array (NOTE: need to be before converting to Resource to ensure we follow the Resource formatting)
            if (is_array($value)) {
                $value = static::resolveArgs($value, $forApiRequest);
            }

            // If is Resource then get the array representation
            if (is_a($value, Resource::class)) {
                $value = $value->toArray($forApiRequest);
            }
        }

        return $args;
    }

    public function toArray(bool $forApiRequest = false): array
    {
        $vars = get_object_vars($this);

        if (empty($vars['self']) && ! empty($this->getPrimaryKey())) {
            try {
                $vars['self'] = EconomicApiService::createURL($this->getEndpoint(GetSingle::class, $this->getPrimaryKey()));
            } catch (Throwable $e) {
                // Do nothing, since we can't get the self
            }
        }

        return static::resolveArgs($vars, $forApiRequest);
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
