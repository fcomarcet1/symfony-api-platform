<?php

namespace App\Api\Action\User;


use App\Service\Request\RequestService;
use App\Service\User\ResendActivationEmailService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ResendActivationEmail
{
    private ResendActivationEmailService $resendActivationEmail;

    public function __construct(ResendActivationEmailService $resendActivationEmail)
    {
        $this->resendActivationEmail = $resendActivationEmail;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Call service for resend email account validate
        $this->resendActivationEmail->resendEmail(
            RequestService::getField($request, 'email')
        );

        return new JsonResponse(['message' => 'Resend Activation email sent']);
    }
}