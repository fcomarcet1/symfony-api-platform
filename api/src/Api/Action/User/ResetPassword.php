<?php

declare(strict_types=1);

namespace App\Api\Action\User;

use App\Entity\User;
use App\Service\Request\RequestService;
use App\Service\User\ResetPasswordService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class ResetPassword
{
    private ResetPasswordService $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function __invoke(Request $request): User
    {
        return $this->resetPasswordService->reset(
            RequestService::getField($request, 'userId'),
            RequestService::getField($request, 'resetPasswordToken'),
            RequestService::getField($request, 'password')
        );
    }
}