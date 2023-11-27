<?php

use MorningTrain\Economic\Services\EconomicApiService;
use Morningtrain\Economic\Tests\DummyEconomicDriver;

uses()->beforeEach(function () {
    $driverInstance = new DummyEconomicDriver('secret-token', 'grant-token');
    $this->driver = Mockery::mock($driverInstance)->makePartial();

    EconomicApiService::setDriver($this->driver);
})->in('Unit');
