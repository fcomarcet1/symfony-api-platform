<?php

declare(strict_types=1);

namespace App\Api\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class JsonExceptionResponseTransformerListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        // Capture exception
        $exception = $event->getThrowable();

        // Check type of exception
        if ($exception instanceof HttpExceptionInterface) {
            $data = [
                'class' => \get_class($exception),
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
            ];

            $event->setResponse($this->prepareResponse($data, $data['code']));
        }
    }

    // Prepare new JsonResponse with data
    private function prepareResponse(array $data, int $statusCode): JsonResponse
    {
        $response = new JsonResponse($data, $statusCode);
        // Config Headers
        $response->headers->set('Server-Time', \time());
        $response->headers->set('X-Error-Code', $statusCode);

        return $response;
    }
}
