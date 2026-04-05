<?php

namespace App\Controller;

use App\Contract\ProductServiceInterface;
use App\Dto\CreateProductDto;
use App\Dto\UpdateProductDto;
use App\Transformer\ProductTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

// D - Dependency Inversion: depends on ProductServiceInterface, not the concrete service
// S - Single Responsibility: only handles HTTP input/output, delegates logic to service
#[Route('/products')]
class ProductController extends AbstractController
{
    public function __construct(
        private ProductServiceInterface $productService,
        private ProductTransformer $transformer,
    ) {}

    // GET /products?categoryId=1&search=keyboard&page=1&limit=10
    #[Route('', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $result = $this->productService->findAll(
            categoryId: $request->query->getInt('categoryId') ?: null,
            search:     $request->query->getString('search') ?: null,
            page:       $request->query->getInt('page', 1),
            limit:      $request->query->getInt('limit', 10),
        );

        $result['data'] = $this->transformer->transformCollection($result['data']);
        return $this->json($result);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json(
            $this->transformer->transform($this->productService->findOne($id))
        );
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $dto = new CreateProductDto();
        $dto->name        = $body['name'] ?? '';
        $dto->description = $body['description'] ?? null;
        $dto->price       = $body['price'] ?? 0;
        $dto->categoryId  = $body['categoryId'] ?? 0;

        return $this->json($this->transformer->transform($this->productService->create($dto)), 201);
    }

    #[Route('/{id}', methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $dto = new UpdateProductDto();
        $dto->name        = $body['name'] ?? null;
        $dto->description = $body['description'] ?? null;
        $dto->price       = $body['price'] ?? null;
        $dto->categoryId  = $body['categoryId'] ?? null;

        return $this->json($this->transformer->transform($this->productService->update($id, $dto)));
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->productService->delete($id);
        return $this->json(null, 204);
    }
}
