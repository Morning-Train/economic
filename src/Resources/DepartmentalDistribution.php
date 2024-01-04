<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;

class DepartmentalDistribution extends Resource
{
    public int $departmentalDistributionNumber;

    public array $distributions;

    public string $distributionType;

    public string $name;
}
