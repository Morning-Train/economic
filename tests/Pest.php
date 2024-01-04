<?php

use Morningtrain\Economic\Services\EconomicApiService;
use Morningtrain\Economic\Tests\DummyEconomicDriver;

uses()->beforeEach(function () {
    $driverInstance = new DummyEconomicDriver('secret-token', 'grant-token');
    $this->driver = Mockery::mock($driverInstance)->makePartial();

    EconomicApiService::setDriver($this->driver);
})->in('Unit');

function fixture(string $fixtureName, string $extension = 'json'): bool|string|array
{
    $filePath = __DIR__."/Fixtures/$fixtureName.$extension";

    if (! file_exists($filePath)) {
        throw new Exception("Fixture file not found: $filePath");
    }

    if ($extension === 'json') {
        return json_decode(file_get_contents($filePath), true, 512, JSON_THROW_ON_ERROR);
    }

    return file_get_contents($filePath);
}
