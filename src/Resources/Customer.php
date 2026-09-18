<?php

namespace Morningtrain\Economic\Resources;

use DateTime;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Attributes\Resources\Properties\ResourceType;
use Morningtrain\Economic\Attributes\Resources\Update;
use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\DTOs\Invoice\Attention;
use Morningtrain\Economic\Resources\Customer\Contact;
use Morningtrain\Economic\Traits\Resources\Creatable;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;
use Morningtrain\Economic\Traits\Resources\Updatable;

#[GetCollection('customers')]
#[GetSingle('customers/:customerNumber')]
#[Create('customers')]
#[Update('customers/:customerNumber', [':customerNumber' => 'customerNumber'])]
class Customer extends Resource
{
    /**
     * @use GetCollectionable<Customer>
     */
    use Creatable, GetCollectionable, GetSingleable, Updatable;

    public ?string $address;

    public Attention $attention;

    public float $balance;

    public ?bool $barred;

    public ?string $city;

    /**
     * @var EconomicCollection<Contact>
     */
    #[ResourceType(Contact::class)]
    public EconomicCollection $contacts;

    public ?string $corporateIdentificationNumber;

    public ?string $country;

    public ?float $creditLimit;

    public string $currency;

    public Contact $customerContact;

    public CustomerGroup $customerGroup;

    #[PrimaryKey]
    public int $customerNumber;

    // TODO: implement $defaultDeliveryLocation

    // TODO: implement $deliveryLocations

    public float $dueAmount;

    public ?string $ean;

    public ?bool $eInvoicingDisabledByDefault;

    public ?string $email;

    // TODO: implement $invoices (Invoice)

    public DateTime $lastUpdated;

    public ?Layout $layout;

    public ?string $mobilePhone;

    public string $name;

    public PaymentTerm $paymentTerms;

    public ?string $pNumber;

    public ?string $publicEntryNumber;

    // TODO: implement $salesPerson (Employee)

    public ?string $telephoneAndFaxNumber;

    // TODO: impelement $tempaltes

    public array $totals;

    public ?string $vatNumber;

    public VatZone $vatZone;

    public ?string $website;

    public ?string $zip;

    public ?Employee $salesPerson;

    public static function create(
        string $name,
        CustomerGroup|int $customerGroup,
        string $currency,
        VatZone|int $vatZone,
        PaymentTerm|int $paymentTerms,
        ?string $email = null,
        ?string $address = null,
        ?string $zip = null,
        ?string $city = null,
        ?string $country = null,
        ?string $corporateIdentificationNumber = null,
        ?string $pNumber = null,
        ?string $vatNumber = null,
        ?string $ean = null,
        ?string $publicEntryNumber = null,
        ?string $website = null,
        ?string $mobilePhone = null,
        ?string $telephoneAndFaxNumber = null,
        ?bool $barred = null,
        ?bool $eInvoicingDisabledByDefault = null,
        ?float $creditLimit = null,
        ?int $customerNumber = null,
        Layout|int|null $layout = null,
        Employee|int|null $salesPerson = null,
    ): static {
        return static::createRequest(compact(
            'name',
            'customerGroup',
            'currency',
            'vatZone',
            'paymentTerms',
            'email',
            'address',
            'zip',
            'city',
            'country',
            'corporateIdentificationNumber',
            'pNumber',
            'vatNumber',
            'ean',
            'publicEntryNumber',
            'website',
            'mobilePhone',
            'telephoneAndFaxNumber',
            'barred',
            'eInvoicingDisabledByDefault',
            'creditLimit',
            'customerNumber',
            'layout',
            'salesPerson'
        ));
    }

    public static function new(
        string $name,
        CustomerGroup|int $customerGroup,
        string $currency,
        VatZone|int $vatZone,
        PaymentTerm|int $paymentTerms,
        ?string $email = null,
        ?string $address = null,
        ?string $zip = null,
        ?string $city = null,
        ?string $country = null,
        ?string $corporateIdentificationNumber = null,
        ?string $pNumber = null,
        ?string $vatNumber = null,
        ?string $ean = null,
        ?string $publicEntryNumber = null,
        ?string $website = null,
        ?string $mobilePhone = null,
        ?string $telephoneAndFaxNumber = null,
        ?bool $barred = null,
        ?bool $eInvoicingDisabledByDefault = null,
        ?float $creditLimit = null,
        ?int $customerNumber = null,
        Layout|int|null $layout = null,
        Employee|int|null $salesPerson = null,
    ): static {
        return new static([
            'name' => $name,
            'customerGroup' => $customerGroup,
            'currency' => $currency,
            'vatZone' => $vatZone,
            'paymentTerms' => $paymentTerms,
            'email' => $email,
            'address' => $address,
            'zip' => $zip,
            'city' => $city,
            'country' => $country,
            'corporateIdentificationNumber' => $corporateIdentificationNumber,
            'pNumber' => $pNumber,
            'vatNumber' => $vatNumber,
            'ean' => $ean,
            'publicEntryNumber' => $publicEntryNumber,
            'website' => $website,
            'mobilePhone' => $mobilePhone,
            'telephoneAndFaxNumber' => $telephoneAndFaxNumber,
            'barred' => $barred,
            'eInvoicingDisabledByDefault' => $eInvoicingDisabledByDefault,
            'creditLimit' => $creditLimit,
            'customerNumber' => $customerNumber,
            'layout' => $layout,
            'salesPerson' => $salesPerson,
        ]);
    }
}
