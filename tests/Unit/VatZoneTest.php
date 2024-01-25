<?php

use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Resources\VatZone;

it('gets all vat zones', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/vat-zones',
        [
            'pageSize' => 20,
            'skipPages' => 0,
        ]
    )
        ->andReturn(new EconomicResponse(200, [
            'collection' => [
                [
                    'name' => 'DK',
                    'vatZoneNumber' => 1,
                    'enabledForCustomer' => true,
                    'enabledForSupplier' => true,
                    'self' => 'https://restapi.e-conomic.com/vat-zones/1',
                ],
                [
                    'name' => 'EU',
                    'vatZoneNumber' => 2,
                    'enabledForCustomer' => true,
                    'enabledForSupplier' => true,
                    'self' => 'https://restapi.e-conomic.com/vat-zones/2',
                ],
            ],
            'pagination' => [
                'maxPageSize' => 20,
                'skipPages' => 0,
                'results' => 2,
            ],
        ]));

    $vatZones = VatZone::all();

    expect($vatZones)->toBeInstanceOf(EconomicCollection::class);

    expect($vatZones->first())
        ->toBeInstanceOf(VatZone::class)
        ->name->toBe('DK')
        ->vatZoneNumber->toBe(1)
        ->enabledForCustomer->toBe(true)
        ->enabledForSupplier->toBe(true)
        ->self->toBe('https://restapi.e-conomic.com/vat-zones/1');

    expect($vatZones->all())->toHaveCount(2);
});

it('gets a specific vatZone', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/vat-zones/1',
        []
    )
        ->andReturn(new EconomicResponse(200, [
            'name' => 'DK',
            'vatZoneNumber' => 1,
            'enabledForCustomer' => true,
            'enabledForSupplier' => true,
            'self' => 'https://restapi.e-conomic.com/vat-zones/1',
        ]));

    $vatZone = VatZone::find(1);

    expect($vatZone)->toBeInstanceOf(VatZone::class)
        ->name->toBe('DK')
        ->vatZoneNumber->toBe(1)
        ->enabledForCustomer->toBe(true)
        ->enabledForSupplier->toBe(true)
        ->self->toBe('https://restapi.e-conomic.com/vat-zones/1');
});
