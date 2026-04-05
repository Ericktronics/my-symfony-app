<?php

namespace App\Contract;

use App\Dto\CreateProductDto;
use App\Dto\UpdateProductDto;
use App\Entity\Product;

interface ProductServiceInterface
{
    public function findAll(?int $categoryId, ?string $search, int $page, int $limit): array;
    public function findOne(int $id): Product;
    public function create(CreateProductDto $dto): Product;
    public function update(int $id, UpdateProductDto $dto): Product;
    public function delete(int $id): void;
}
