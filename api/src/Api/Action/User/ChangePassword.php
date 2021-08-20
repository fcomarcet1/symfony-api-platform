<?php
declare(strict_types=1);

namespace App\Api\Action\User;

use App\Entity\User;
use App\Service\Request\RequestService;
use App\Service\User\ChangePasswordService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class ChangePassword
{
    private ChangePasswordService $changePasswordService;

    public function __construct(ChangePasswordService $changePasswordService)
    {
        $this->changePasswordService = $changePasswordService;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function __invoke(Request $request, User $user): User
    {
        return $this->changePasswordService->changePassword(
            $user->getId(),
            RequestService::getField($request, 'oldPassword'),
            RequestService::getField($request, 'newPassword'),
        );
    }
}