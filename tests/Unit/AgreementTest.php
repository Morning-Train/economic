<?php

use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Resources\Agreement;

it('gets the agreement the tokens belong to', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/self',
        []
    )
        ->andReturn(new EconomicResponse(200, [
            'agreementNumber' => 123456,
            'agreementType' => [
                'agreementTypeNumber' => 1,
                'name' => 'Standard',
            ],
            'userName' => 'API User',
            'signupDate' => '2020-01-15',
            'company' => [
                'name' => 'Test Company ApS',
                'addressLine1' => 'Testvej 1',
                'zip' => '1234',
                'city' => 'Testby',
                'country' => 'Denmark',
                'companyIdentificationNumber' => '12345678',
            ],
            'settings' => [
                'baseCurrency' => 'DKK',
                'internationalLedger' => 'false',
            ],
            'canSendElectronicInvoice' => true,
            'modules' => [
                [
                    'moduleNumber' => 1,
                    'name' => 'Projekt',
                    'self' => 'https://restapi.e-conomic.com/modules/1',
                ],
            ],
            'self' => 'https://restapi.e-conomic.com/self',
        ]));

    $agreement = Agreement::get();

    expect($agreement)
        ->toBeInstanceOf(Agreement::class)
        ->agreementNumber->toBe(123456)
        ->userName->toBe('API User')
        ->canSendElectronicInvoice->toBeTrue()
        ->self->toBe('https://restapi.e-conomic.com/self');

    expect($agreement->company['name'])->toBe('Test Company ApS');
    expect($agreement->signupDate)->toBeInstanceOf(DateTime::class);
    expect($agreement->signupDate->format('Y-m-d'))->toBe('2020-01-15');
});

it('returns null when the self endpoint does not respond with 200', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/self',
        []
    )
        ->andReturn(new EconomicResponse(401, [
            'message' => 'Unauthorized',
        ]));

    expect(Agreement::get())->toBeNull();
});
