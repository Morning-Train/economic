<?php

namespace MorningTrain\Economic\Resources;

use MorningTrain\Economic\Abstracts\Resource;
use MorningTrain\Economic\Attributes\Resources\Create;
use MorningTrain\Economic\Attributes\Resources\Delete;
use MorningTrain\Economic\Attributes\Resources\GetCollection;
use MorningTrain\Economic\Attributes\Resources\GetSingle;
use MorningTrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use MorningTrain\Economic\Attributes\Resources\Properties\Required;
use MorningTrain\Economic\Attributes\Resources\Properties\ResourceType;
use MorningTrain\Economic\Attributes\Resources\Update;
use MorningTrain\Economic\Classes\EconomicCollection;
use MorningTrain\Economic\Classes\EconomicQueryBuilder;
use MorningTrain\Economic\Traits\Resources\Creatable;
use MorningTrain\Economic\Traits\Resources\Deletable;
use MorningTrain\Economic\Traits\Resources\GetCollectionable;
use MorningTrain\Economic\Traits\Resources\GetSingleable;
use MorningTrain\Economic\Traits\Resources\Updatable;

#[GetCollection('units')]
#[GetSingle('units/:unitNumber')]
#[Create('units')]
#[Update('units/:unitNumber')]
#[Delete('units/:unitNumber')]
#[GetCollection('units/:unitNumber/products', 'products')]
class Unit extends Resource
{
    use GetCollectionable, GetSingleable, Creatable, Updatable, Deletable;

    #[Required]
    public string $name;

    #[PrimaryKey]
    public int $unitNumber;

    /**
     * Products that references the unit
     * @var EconomicCollection<Product>
     */
    #[ResourceType(Product::class)]
    public EconomicCollection $products;

    /**
     * Create new Unit
     * https://restdocs.e-conomic.com/#post-units
     * @param string $name
     * @return static
     */
    public static function create(string $name): static
    {
        return static::createRequest([
            'name' => $name
        ]);
    }

    /**
     * Get Products that references the unit - https://restdocs.e-conomic.com/#get-units-unitnumber-products
     * @param int $unitNumber
     * @return EconomicCollection<Product>
     * @throws \Exception
     */
    public static function getProductsForUnit(int $unitNumber): EconomicCollection
    {
        return (new EconomicQueryBuilder(Product::class))->setEndpoint(static::getEndpointBySlug('products', $unitNumber))->all();
    }
}
