<?php

namespace App\Controller;

use App\Contract\CategoryServiceInterface;
use App\Dto\CreateCategoryDto;
use App\Dto\UpdateCategoryDto;
use App\Transformer\CategoryTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

// D - Dependency Inversion: depends on CategoryServiceInterface, not the concrete service
// S - Single Responsibility: only handles HTTP input/output, delegates logic to service
#[Route('/categories')]
class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryServiceInterface $categoryService,
        private CategoryTransformer $transformer,
    ) {}

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json(
            $this->transformer->transformCollection($this->categoryService->findAll())
        );
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json(
            $this->transformer->transform($this->categoryService->findOne($id))
        );
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $dto = new CreateCategoryDto();
        $dto->name = $body['name'] ?? '';

        return $this->json($this->transformer->transform($this->categoryService->create($dto)), 201);
    }

    #[Route('/{id}', methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $dto = new UpdateCategoryDto();
        $dto->name = $body['name'] ?? null;

        return $this->json($this->transformer->transform($this->categoryService->update($id, $dto)));
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->categoryService->delete($id);
        return $this->json(null, 204);
    }
}
