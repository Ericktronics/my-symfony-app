<?php

namespace App\EventListener;

use App\Exception\ConflictException;
use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

// NestJS equivalent: @Catch() exception filter registered globally
#[AsEventListener(event: KernelEvents::EXCEPTION)]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            $event->setResponse(new JsonResponse([
                'statusCode' => 422,
                'message'    => 'Validation failed',
                'errors'     => $exception->getErrors(),
            ], 422));
            return;
        }

        if ($exception instanceof ConflictException) {
            $event->setResponse(new JsonResponse([
                'statusCode' => 409,
                'message'    => $exception->getMessage(),
            ], 409));
            return;
        }

        if ($exception instanceof NotFoundException) {
            $event->setResponse(new JsonResponse([
                'statusCode' => 404,
                'message'    => $exception->getMessage(),
            ], 404));
            return;
        }
    }
}
