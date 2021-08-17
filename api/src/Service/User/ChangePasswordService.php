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
    public function changePassword(string $userId, string $oldPassword, string $newPassword): User
    {

        $user = $this->userRepository->findOneByIdOrFail($userId);

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