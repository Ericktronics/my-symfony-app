<?php

namespace App\Exception;

use RuntimeException;

// NestJS equivalent: throw new NotFoundException('Product not found')
class NotFoundException extends RuntimeException
{
    public function __construct(string $resource, int|string $id)
    {
        parent::__construct("$resource with id '$id' not found");
    }
}
