<?php

namespace App\Service;

use App\Contract\CategoryServiceInterface;
use App\Dto\CreateCategoryDto;
use App\Dto\UpdateCategoryDto;
use App\Entity\Category;
use App\Exception\ConflictException;
use App\Exception\NotFoundException;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

// S - Single Responsibility: only handles category business logic
// D - Dependency Inversion: implements the interface, depends on abstractions (EM, Repository)
class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryRepository $repository,
        private ValidationService $validator,   // injected, not duplicated
    ) {}

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findOne(int $id): Category
    {
        $category = $this->repository->find($id);
        if (!$category) throw new NotFoundException('Category', $id);
        return $category;
    }

    public function create(CreateCategoryDto $dto): Category
    {
        $this->validator->validate($dto);

        $category = new Category();
        $category->setName($dto->name);

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    public function update(int $id, UpdateCategoryDto $dto): Category
    {
        $this->validator->validate($dto);

        $category = $this->findOne($id);
        if ($dto->name !== null) $category->setName($dto->name);

        $this->em->flush();

        return $category;
    }

    public function delete(int $id): void
    {
        $category = $this->findOne($id);

        if (!$category->getProducts()->isEmpty()) {
            throw new ConflictException(
                "Cannot delete category '{$category->getName()}' because it has linked products."
            );
        }

        $this->em->remove($category);
        $this->em->flush();
    }
}
