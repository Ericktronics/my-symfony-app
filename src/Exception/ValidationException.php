<?php

namespace App\Exception;

use RuntimeException;

// NestJS equivalent: throw new BadRequestException(errors)
class ValidationException extends RuntimeException
{
    public function __construct(private readonly array $errors)
    {
        parent::__construct('Validation failed');
    }

    public function getErrors(): array { return $this->errors; }
}
