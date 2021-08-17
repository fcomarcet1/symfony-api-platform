<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\Password\PasswordInvalidException;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordService
{

    private UserRepository $userRepository;
    private EncoderService $encoderService;

    public function __construct(UserRepository $userRepository, EncoderService $encoderService)
    {
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function changePassword(Request $request, User $user): User
    {
        // Get oldPassword && newPassword from request
        $oldPassword = RequestService::getField($request, 'oldPassword');
        $newPassword = RequestService::getField($request, 'newPassword');

        // Check if oldPassword is valid
        if (!$this->encoderService->isValidPassword($user, $oldPassword)){
            throw PasswordInvalidException::oldPasswordDoesNotMatch();
        }

        // Set newPassword && Encode newPassword
        $user->setPassword($this->encoderService->generateEncodedPassword($user, $newPassword));

        // save user in db
        $this->userRepository->save($user);

        return $user;
        
    }
}