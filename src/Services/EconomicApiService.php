<?php

namespace Morningtrain\Economic\Services;

use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Interfaces\EconomicDriver;

class EconomicApiService
{
    protected static EconomicDriver $driver;

    protected static string $url = 'https://restapi.e-conomic.com';

    public static function setDriver(EconomicDriver $driver): void
    {
        static::$driver = $driver;
    }

    /**
     * @throws \Exception
     */
    public static function getDriver(): EconomicDriver
    {
        if (empty(static::$driver)) {
            EconomicLoggerService::error('No driver has been set for the Economic API Service');

            throw new \Exception('No driver has been set for the Economic API Service');
        }

        return static::$driver;
    }

    public static function createURL(string $endpoint): string
    {
        $endpoint = str_replace(static::$url, '', $endpoint);

        return static::$url.'/'.$endpoint;
    }

    public static function get(string $endpoint, array $queryArgs = []): EconomicResponse
    {
        return static::getDriver()->get(static::createURL($endpoint), $queryArgs);
    }

    public static function post(string $endpoint, array $body = []): EconomicResponse
    {
        return static::getDriver()->post(static::createURL($endpoint), static::castParameters($body));
    }

    public static function put(string $endpoint, array $body = []): EconomicResponse
    {
        return static::getDriver()->put(static::createURL($endpoint), static::castParameters($body));
    }

    public static function delete(string $endpoint): EconomicResponse
    {
        return static::getDriver()->delete(static::createURL($endpoint));
    }

    public static function patch($endpoint, array $body = []): EconomicResponse
    {
        return static::getDriver()->patch(static::createURL($endpoint), static::castParameters($body));
    }

    protected static function castParameters(array $body): array
    {
        foreach ($body as &$value) {
            if (is_a($value, 'DateTime')) {
                $value = $value->format('Y-m-d');
            }
        }

        return $body;
    }
}
