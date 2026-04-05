<?php

namespace App\Transformer;

use App\Entity\Product;

// S - Single Responsibility: only concern is converting a Product entity to a serializable array
// D - Dependency Inversion: depends on CategoryTransformer abstraction for nested serialization
class ProductTransformer
{
    public function __construct(private CategoryTransformer $categoryTransformer) {}

    public function transform(Product $product): array
    {
        return [
            'id'          => $product->getId(),
            'name'        => $product->getName(),
            'description' => $product->getDescription(),
            'price'       => (float) $product->getPrice(),
            'category'    => $this->categoryTransformer->transform($product->getCategory()),
        ];
    }

    public function transformCollection(array $products): array
    {
        return array_map(fn($p) => $this->transform($p), $products);
    }
}
