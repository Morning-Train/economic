<?php

namespace Morningtrain\Economic\DTOs\Invoice;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Classes\EconomicResponse;
use Morningtrain\Economic\Services\EconomicApiService;

class Pdf extends Resource
{
    public ?string $download;

    public function getPdfContent(): EconomicResponse
    {
        return EconomicApiService::get($this->download)->getBody();
    }
}
