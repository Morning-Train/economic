<?php

namespace Morningtrain\Economic\Classes;

class EconomicResponse
{
    protected int $statusCode;

    protected array|string $body;

    public function __construct(int $statusCode, array|string $body)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): array|string
    {
        return $this->body;
    }

    public function getProperty(string $property): mixed
    {
        return $this->body[$property] ?? null;
    }

    public function __get(string $name)
    {
        return $this->getProperty($name);
    }
}
