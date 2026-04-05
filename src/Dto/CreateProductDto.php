<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

// NestJS equivalent: create-product.dto.ts with class-validator decorators
class CreateProductDto
{
    #[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Length(min: 2, max: 255)]
    public string $name;

    #[Assert\Length(max: 1000)]
    public ?string $description = null;

    #[Assert\NotBlank(message: 'Price is required')]
    #[Assert\Positive(message: 'Price must be a positive number')]
    public float $price;

    #[Assert\NotBlank(message: 'categoryId is required')]
    #[Assert\Positive(message: 'categoryId must be a positive integer')]
    public int $categoryId;
}
