<?php

namespace Morningtrain\Economic\Resources;

use DateTime;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Services\EconomicApiService;
use Morningtrain\Economic\Traits\Resources\EndpointResolvable;

/**
 * The agreement the current tokens belong to (GET /self).
 *
 * Named Agreement because "Self" is a reserved word in PHP.
 */
#[GetSingle('self')]
class Agreement extends Resource
{
    use EndpointResolvable;

    public int $agreementNumber;

    public array $agreementType;

    public string $userName;

    public DateTime $signupDate;

    public array $user;

    public array $company;

    public array $bankInformation;

    public array $application;

    public array $settings;

    public string $companyAffiliation;

    public bool $canSendElectronicInvoice;

    public array $modules;

    public static function get(): ?static
    {
        $response = EconomicApiService::get(static::getEndpoint(GetSingle::class));

        if ($response->getStatusCode() !== 200) {
            // Todo: Log error and throw exception

            return null;
        }

        return new static($response->getBody());
    }
}
