<?php

declare(strict_types=1);

namespace App\Api\Action\User;

use App\Service\User\RequestResetPasswordService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RequestResetPassword
{
    private RequestResetPasswordService $requestResetPasswordService;

    public function __construct(RequestResetPasswordService $requestResetPasswordService)
    {
        $this->requestResetPasswordService = $requestResetPasswordService;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Call service for reset password
        $this->requestResetPasswordService->sendResetPassword($request);

        return new JsonResponse(['message' => 'Reset Password email sent']);
    }
}