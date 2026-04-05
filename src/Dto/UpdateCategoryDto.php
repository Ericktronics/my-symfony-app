<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCategoryDto
{
    #[Assert\Length(min: 2, max: 100)]
    public ?string $name = null;
}
