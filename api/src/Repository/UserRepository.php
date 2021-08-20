<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return User::class;
    }

    public function findOneByEmailOrFail(string $email): User
    {
        $user = $this->objectRepository->findOneBy(['email' => $email]);
        if ($user === null) {
            throw UserNotFoundException::fromEmail($email);
        }

        return $user;
    }

    public function findOneInactiveByIdAndTokenOrFail(string $id, string $token): User
    {
        $user = $this->objectRepository->findOneBy([
            'id' => $id,
            'token' => $token,
            'active' => false
        ]);
        if ($user === null){
            throw UserNotFoundException::fromUserIdAndToken($id, $token);
        }
        return $user;
    }

    public function findOneByIdAndResetPasswordToken(string $id, string $resetPasswordToken): User
    {
        $user = $this->objectRepository->findOneBy(['id' => $id, 'resetPasswordToken' => $resetPasswordToken]);
        if ($user === null){
            throw UserNotFoundException::fromUserIdAndResetPasswordToken($id, $resetPasswordToken);
        }

        return $user;
    }

    public function findOneByIdOrFail(string $id): User
    {
        $user = $this->objectRepository->find($id);
        if ($user === null) {
            throw UserNotFoundException::fromUserId($id);
        }

        return $user;
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(User $user): void
    {
        $this->saveEntity($user);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(User $user): void
    {
        $this->removeEntity($user);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function refresh(User $user): void
    {
        $this->refreshEntity($user);
    }
}