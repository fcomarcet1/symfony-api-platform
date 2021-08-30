<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\GroupRequest;
use App\Exception\GroupRequest\GroupRequestNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class GroupRequestRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return GroupRequest::class;
    }

    /**
     * Find pending user by groupId, userId, token
     */
    public function findOnePendingByGroupIdUserIdAndTokenOrFail(
        string $groupId,
        string $userId,
        string $token
    ) :GroupRequest
    {


        /**
         * No hay problema en meter un string en el campo group aunque en nuestro modelo
         * ser ade tipo Group la propiedad, doctrine sabe interpretarlo y transformar
         * internamente y obtendremos el mismo resultado.
         */
        $groupRequest = $this->objectRepository->findOneBy([
            'user' => $userId,
            'group' => $groupId,
            'token' => $token,
            'acceptedAt' => GroupRequest::PENDING
        ]);

        if ($groupRequest === null){
            throw GroupRequestNotFoundException::fromGroupIdUserIdAndToken($groupId, $userId, $token);
        }

        return $groupRequest;
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(GroupRequest $groupRequest): void
    {
        $this->saveEntity($groupRequest);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(GroupRequest $groupRequest): void
    {
        $this->removeEntity($groupRequest);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function refresh(GroupRequest $groupRequest): void
    {
        $this->refreshEntity($groupRequest);
    }
}