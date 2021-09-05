<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Movement;
use App\Exception\Movement\MovementNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class MovementRepository extends BaseRepository
{
    
    protected static function entityClass(): string
    {
        return Movement::class;
    }

    public function findOneByIdOrFail(string $id): Movement
    {
        if (null === $movement = $this->objectRepository->find($id)) {
            throw MovementNotFoundException::fromId($id);
        }

        return $movement;
    }

    public function findOneByFilePathOrFail(string $filePath): Movement
    {
        if (null === $movement = $this->objectRepository->findOneBy(['filePath' => $filePath])) {
            throw MovementNotFoundException::fromFilePath($filePath);
        }

        return $movement;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(Movement $movement): void
    {
        $this->saveEntity($movement);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(Movement $movement): void
    {
        $this->removeEntity($movement);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function refresh(Movement $movement): void
    {
        $this->refreshEntity($movement);
    }

}