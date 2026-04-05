<?php

namespace App\Transformer;

use App\Entity\Category;

// S - Single Responsibility: only concern is converting a Category entity to a serializable array
// NestJS equivalent: a custom ClassSerializerInterceptor or a dedicated presenter class
class CategoryTransformer
{
    public function transform(Category $category): array
    {
        return [
            'id'   => $category->getId(),
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
        ];
    }

    public function transformCollection(array $categories): array
    {
        return array_map(fn($c) => $this->transform($c), $categories);
    }
}
