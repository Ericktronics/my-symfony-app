<?php

namespace App\Exception;

use RuntimeException;

// NestJS equivalent: throw new ConflictException('message')
class ConflictException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
