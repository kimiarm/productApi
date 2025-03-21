<?php

declare(strict_types=1);

namespace App\Domain\DTO;

use App\Domain\Model\Product;

class ProductDTO
{
    public function __construct(
        public string $name,
        public float $price,
        public string $category,
        public AttributeDTO $attributes
    ) {}

    public static function fromModel(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'category' => $product->category,
            'attributes' => AttributeDTO::fromArray($product->attributes)->toArray(),
            'createdAt' => $product->createdAt,
        ];
    }
}