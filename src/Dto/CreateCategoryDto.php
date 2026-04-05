<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCategoryDto
{
    #[Assert\NotBlank(message: 'Name is required')]
    #[Assert\Length(min: 2, max: 100)]
    public string $name;
}
