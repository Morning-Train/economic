<?php

use MorningTrain\Economic\Classes\EconomicResponse;
use MorningTrain\Economic\Resources\Customer;

it('Filters by name', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
        'pageSize' => 1,
        'skipPages' => 0,
        'filter' => 'name$eq:Morningtrain',
    ])
        ->once()
        ->andReturn(new EconomicResponse(200, []));

    Customer::where('name', 'Morningtrain')->first();
});

it('Handles multiple where filters', function () {
    $this->driver->expects()->get('https://restapi.e-conomic.com/customers', [
        'pageSize' => 1,
        'skipPages' => 0,
        'filter' => 'email$eq:ms@morningtrain.dk$and:name$eq:Morningtrain',
    ])
        ->once()
        ->andReturn(new EconomicResponse(200, []));

    Customer::where('email', 'ms@morningtrain.dk')
        ->where('name', 'Morningtrain')
        ->first();
});

it('Handles OR filters', function () {
    $this->driver->shouldReceive('get')
        ->once()
        ->with('https://restapi.e-conomic.com/customers', [
            'pageSize' => 1,
            'skipPages' => 0,
            'filter' => 'email$eq:ms@morningtrain.dk$or:(name$eq:Morningtrain)',
        ])
        ->andReturn(new EconomicResponse(200, []));

    Customer::where('email', 'ms@morningtrain.dk')
        ->orWhere('name', 'Morningtrain')
        ->first();
});
