<?php

namespace App\Service;

use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// S - Single Responsibility: this class has one job — validate DTOs and throw on failure
// NestJS equivalent: the ValidationPipe extracted as a standalone service
class ValidationService
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate(object $dto): void
    {
        $violations = $this->validator->validate($dto);
        if (count($violations) === 0) return;

        $errors = [];
        foreach ($violations as $v) {
            $errors[$v->getPropertyPath()] = $v->getMessage();
        }

        throw new ValidationException($errors);
    }
}
