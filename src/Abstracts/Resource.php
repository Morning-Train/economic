<?php

namespace Morningtrain\Economic\Abstracts;

use Exception;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Attributes\Resources\Properties\ResourceType;
use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicCollectionIterator;
use Morningtrain\Economic\Services\EconomicLoggerService;
use ReflectionClass;
use ReflectionMethod;

abstract class Resource
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

    public function toArray(): array
    {
        $reflection = new ReflectionClass(static::class);

        $properties = [];

        foreach ($reflection->getProperties() as $property) {
            // If not public then skip
            if (! $property->isPublic()) {
                continue;
            }

            // Set self if not set
            if (
                $property->getName() == 'self' &&
                empty($this->{$property->getName()}) &&
                ! empty($this->getPrimaryKey())
            ) {
                try {
                    $self = $this->getEndpoint(GetSingle::class, $this->getPrimaryKey());

                    $this->self = $self;
                } catch (Exception $e) {
                    // Do nothing, since we can't get the self
                }
            }

            // If property is not set then skip
            if (! isset($this->{$property->getName()})) {
                continue;
            }

            $value = $this->{$property->getName()};

            // If is EconomicCollection then only get the self property
            if ($property->getType() == EconomicCollection::class) {
                $value = $value->getSelf();
            }

            // If is Resource then get the array representation
            if (is_a($value, Resource::class)) {
                $value = $value->toArray();
            }

            $properties[$property->getName()] = $value;
        }

        return $properties;
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    protected static function filterEmpty(array $values): array
    {
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $values[$key] = static::filterEmpty($value);
            }

            if ($values[$key] === null) {
                unset($values[$key]);
            }
        }

        return $values;
    }

    protected static function getMethodArgs(string $function, array $arguments): array
    {
        $reflection = new ReflectionMethod(...explode('::', $function));

        $assoc = [];

        foreach ($reflection->getParameters() as $i => $parameter) {
            $assoc[$parameter->getName()] = $arguments[$i] ?? null;
        }

        return $assoc;
    }

    protected static function resolveArguments(array $arguments): array
    {
        $creationParameters = [];

        foreach ($arguments as $name => $value) {
            $creationParameters[$name] = static::resolvePropertyValue($name, $value);
        }

        return $creationParameters;
    }
}
