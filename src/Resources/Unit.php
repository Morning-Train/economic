<?php

namespace Morningtrain\Economic\Resources;

use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\Delete;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Attributes\Resources\Properties\Required;
use Morningtrain\Economic\Attributes\Resources\Properties\ResourceType;
use Morningtrain\Economic\Attributes\Resources\Update;
use Morningtrain\Economic\Classes\EconomicCollection;
use Morningtrain\Economic\Classes\EconomicQueryBuilder;
use Morningtrain\Economic\Traits\Resources\Creatable;
use Morningtrain\Economic\Traits\Resources\Deletable;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;
use Morningtrain\Economic\Traits\Resources\Updatable;

#[GetCollection('units')]
#[GetSingle('units/:unitNumber')]
#[Create('units')]
#[Update('units/:unitNumber', [':unitNumber' => 'unitNumber'])]
#[Delete('units/:unitNumber', [':unitNumber' => 'unitNumber'])]
class Unit extends Resource
{
    use Creatable, Deletable, GetCollectionable, GetSingleable, Updatable;

    #[Required]
    public string $name;

    #[PrimaryKey]
    public int $unitNumber;

    /**
     * Products that references the unit
     *
     * @var EconomicCollection<Product>
     */
    #[ResourceType(Product::class)]
    public EconomicCollection $products;

    /**
     * Create new Unit
     * https://restdocs.e-conomic.com/#post-units
     */
    public static function create(string $name): static
    {
        return static::createRequest([
            'name' => $name,
        ]);
    }

    /**
     * Get Products that references the unit - https://restdocs.e-conomic.com/#get-units-unitnumber-products
     *
     * @return EconomicCollection<Product>
     *
     * @throws \Exception
     */
    public static function getProductsForUnit(int $unitNumber): EconomicCollection
    {
        return (new EconomicQueryBuilder(Product::class))->setEndpoint(static::getEndpointBySlug('products', $unitNumber))->all();
    }
}
