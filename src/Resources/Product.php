<?php

namespace Morningtrain\Economic\Resources;

use DateTime;
use Morningtrain\Economic\Abstracts\Resource;
use Morningtrain\Economic\Attributes\Resources\Create;
use Morningtrain\Economic\Attributes\Resources\Delete;
use Morningtrain\Economic\Attributes\Resources\GetCollection;
use Morningtrain\Economic\Attributes\Resources\GetSingle;
use Morningtrain\Economic\Attributes\Resources\Properties\Filterable;
use Morningtrain\Economic\Attributes\Resources\Properties\PrimaryKey;
use Morningtrain\Economic\Attributes\Resources\Properties\Sortable;
use Morningtrain\Economic\Attributes\Resources\Update;
use Morningtrain\Economic\Traits\Resources\Creatable;
use Morningtrain\Economic\Traits\Resources\Deletable;
use Morningtrain\Economic\Traits\Resources\GetCollectionable;
use Morningtrain\Economic\Traits\Resources\GetSingleable;

#[GetCollection('products')] // https://restdocs.e-conomic.com/#get-products
#[GetSingle('products/:productNumber')]
#[Create('products')]
#[Update('products/:productNumber', [':productNumber' => 'productNumber'])]
#[Delete('products/:productNumber', [':productNumber' => 'productNumber'])]
class Product extends Resource
{
    /**
     * @use GetCollectionable<Product>
     */
    use Creatable, Deletable, GetCollectionable, GetSingleable;

    #[Filterable]
    #[Sortable]
    public ?string $barCode;

    #[Filterable]
    #[Sortable]
    public ?bool $barred;

    #[Filterable]
    #[Sortable]
    public ?float $costPrice;

    //public ?DepartmentalDistribution $departmentalDistribution; // TODO: implement

    public ?string $description;

    //public ?object $inventory; // TODO: implement

    public ?array $invoices;

    public ?DateTime $lastUpdated;

    public ?string $name;

    public ?ProductGroup $productGroup;

    #[PrimaryKey]
    public ?string $productNumber;

    #[Filterable]
    #[Sortable]
    public ?float $recommendedPrice;

    #[Filterable]
    #[Sortable]
    public ?float $salesPrice;

    public ?Unit $unit;

    public static function create(
        string $name,
        ProductGroup|int $productGroup,
        string $productNumber,
        ?string $barCode = null,
        ?bool $barred = null,
        ?float $costPrice = null,
        ?DepartmentalDistribution $departmentalDistribution = null,
        ?string $description = null,
        ?object $inventory = null,
        ?float $recommendedPrice = null,
        ?float $salesPrice = null,
        Unit|int|null $unit = null,
    ): static {
        if (is_int($productGroup)) {
            $productGroup = new ProductGroup(['productGroupNumber' => $productGroup]);
        }

        if (is_int($unit)) {
            $unit = new Unit(['unitNumber' => $unit]);
        }

        return static::createRequest(compact(
            'name',
            'productGroup',
            'productNumber',
            'barCode',
            'barred',
            'costPrice',
            'departmentalDistribution',
            'description',
            'inventory',
            'recommendedPrice',
            'salesPrice',
            'unit',
        ));
    }
}
