<?php

use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Resources\Customer\Contact;

it('gets all contacts for a customer', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/customers/1/contacts',
        [
            'pageSize' => 20,
            'skipPages' => 0,
        ]
    )->andReturn(new EconomicResponse(200, fixture('Customers/Contacts/get-collection')));

    $contact = Contact::fromCustomer(1)->all();

    expect($contact)->toBeInstanceOf(EconomicCollection::class);

    expect($contact->first())->toBeInstanceOf(Contact::class);

    expect($contact)->toHaveCount(5);
});

it('gets a customer contact', function () {
    $this->driver->expects()->get(
        'https://restapi.e-conomic.com/customers/1/contacts/140',
        []
    )->andReturn(new EconomicResponse(200, fixture('Customers/Contacts/get-single')));

    $contact = Contact::fromCustomer(1)->find(140);

    expect($contact)->toBeInstanceOf(Contact::class)
        ->customerContactNumber->toBe(140)
        ->name->toBe('Test 5');
});

it('creates a customer contact', function () {
    $this->driver->expects()->post(
        'https://restapi.e-conomic.com/customers/1/contacts',
        fixture('Customers/Contacts/create-request'),
        null
    )->andReturn(new EconomicResponse(201, fixture('Customers/Contacts/create-response')));

    $contact = Contact::create(
        1,
        'John Doe',
        'ms@morningtrain.dk',
        '12345678',
        'Test'
    );

    expect($contact)->toBeInstanceOf(Contact::class)
        ->customerContactNumber->toBe(140)
        ->name->toBe('John Doe')
        ->email->toBe('ms@morningtrain.dk')
        ->phone->toBe('12345678')
        ->notes->toBe('Test');
});

it('updates a customer contact', function () {
    $this->driver->expects()->put(
        'https://restapi.e-conomic.com/customers/1/contacts/140',
        fixture('Customers/Contacts/update-request'),
        null
    )->andReturn(new EconomicResponse(200, fixture('Customers/Contacts/update-response')));

    $contact = new Contact([
        'customer' => [
            'customerNumber' => 1,
            'self' => 'https://restapi.e-conomic.com/customers/1',
        ],
        'customerContactNumber' => 140,
        'emailNotifications' => [],
        'name' => 'Test 5',
        'sortKey' => 5,
        'self' => 'https://restapi.e-conomic.com/customers/1/contacts/140',
    ]);

    $contact->name = 'Martin';

    $contact->save();

});

it('deletes a customer contact with delete', function () {
    $this->driver->expects()->delete(
        'https://restapi.e-conomic.com/customers/1/contacts/140'
    )->andReturn(new EconomicResponse(204, []));

    $contact = new Contact([
        'customer' => [
            'customerNumber' => 1,
            'self' => 'https://restapi.e-conomic.com/customers/1',
        ],
        'customerContactNumber' => 140,
        'emailNotifications' => [],
        'name' => 'Test 5',
        'sortKey' => 5,
        'self' => 'https://restapi.e-conomic.com/customers/1/contacts/140',
    ]);

    $deleted = $contact->delete();

    expect($deleted)->toBeTrue();
});

it('deletes a customer contact with deleteByPrimaryKey', function () {
    $this->driver->expects()->delete(
        'https://restapi.e-conomic.com/customers/1/contacts/140'
    )->andReturn(new EconomicResponse(204, []));

    $deleted = Contact::deleteByPrimaryKey(1, 140);

    expect($deleted)->toBeTrue();
});
