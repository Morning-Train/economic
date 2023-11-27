<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;

class DepartmentalDistribution extends Resource
{
    public int $departmentalDistributionNumber;

    public array $distributions;

    public string $distributionType;

    public string $name;
}
