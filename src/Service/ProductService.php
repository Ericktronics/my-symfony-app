<?php

namespace App\Service;

use App\Contract\CategoryServiceInterface;
use App\Contract\ProductServiceInterface;
use App\Dto\CreateProductDto;
use App\Dto\UpdateProductDto;
use App\Entity\Product;
use App\Exception\NotFoundException;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

// S - Single Responsibility: only handles product business logic
// D - Dependency Inversion: depends on CategoryServiceInterface, not the concrete class
class ProductService implements ProductServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $repository,
        private CategoryServiceInterface $categoryService, // interface, not concrete
        private ValidationService $validator,
    ) {}

    public function findAll(?int $categoryId, ?string $search, int $page = 1, int $limit = 10): array
    {
        return $this->repository->findFiltered($categoryId, $search, $page, $limit);
    }

    public function findOne(int $id): Product
    {
        $product = $this->repository->find($id);
        if (!$product) throw new NotFoundException('Product', $id);
        return $product;
    }

    public function create(CreateProductDto $dto): Product
    {
        $this->validator->validate($dto);

        $category = $this->categoryService->findOne($dto->categoryId);

        $product = new Product();
        $product->setName($dto->name);
        $product->setDescription($dto->description);
        $product->setPrice($dto->price);
        $product->setCategory($category);

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function update(int $id, UpdateProductDto $dto): Product
    {
        $this->validator->validate($dto);

        $product = $this->findOne($id);

        if ($dto->name !== null)        $product->setName($dto->name);
        if ($dto->description !== null) $product->setDescription($dto->description);
        if ($dto->price !== null)       $product->setPrice($dto->price);
        if ($dto->categoryId !== null)  $product->setCategory($this->categoryService->findOne($dto->categoryId));

        $this->em->flush();

        return $product;
    }

    public function delete(int $id): void
    {
        $product = $this->findOne($id);
        $this->em->remove($product);
        $this->em->flush();
    }
}
