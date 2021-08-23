<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordService
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
    public function reset(string $userId, string $resetPasswordToken, string $newPassword): User
    {
        // Find user in DB
        $user = $this->userRepository->findOneByIdAndResetPasswordToken($userId, $resetPasswordToken);

        //Set new password & resetPasswordToken = null
        $user->setPassword($this->encoderService->generateEncodedPassword($user, $newPassword));
        $user->setResetPasswordToken(null);

        // Save in DB
        $this->userRepository->save($user);

        return $user;

    }




}