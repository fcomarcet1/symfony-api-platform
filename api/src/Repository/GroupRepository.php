<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Group;
use App\Exception\Group\GroupNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class GroupRepository extends BaseRepository
{

    protected static function entityClass(): string
    {
        return Group::class;
    }

    public function findOneByIdOrFail(string $id): Group
    {
        $group = $this->objectRepository->find($id);
        if ($group === null) {
            throw GroupNotFoundException::fromId($id);
        }

        return $group;
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(Group $group): void
    {
        $this->saveEntity($group);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(Group $group): void
    {
        $this->removeEntity($group);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function refresh(Group $group): void
    {
        $this->refreshEntity($group);
    }
}