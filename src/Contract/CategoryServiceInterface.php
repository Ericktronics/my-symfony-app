<?php

namespace App\Contract;

use App\Dto\CreateCategoryDto;
use App\Dto\UpdateCategoryDto;
use App\Entity\Category;

// D - Dependency Inversion: high-level modules depend on this abstraction, not the concrete service
// O - Open/Closed: swap implementations without modifying dependents
// I - Interface Segregation: focused contract — only what callers actually need
interface CategoryServiceInterface
{
    public function findAll(): array;
    public function findOne(int $id): Category;
    public function create(CreateCategoryDto $dto): Category;
    public function update(int $id, UpdateCategoryDto $dto): Category;
    public function delete(int $id): void;
}
