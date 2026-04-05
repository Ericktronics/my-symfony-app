<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateProductDto
{
    #[Assert\Length(min: 2, max: 255)]
    public ?string $name = null;

    #[Assert\Length(max: 1000)]
    public ?string $description = null;

    #[Assert\Positive(message: 'Price must be a positive number')]
    public ?float $price = null;

    #[Assert\Positive(message: 'categoryId must be a positive integer')]
    public ?int $categoryId = null;
}
