<?php
declare(strict_types=1);

namespace App\Service\Group;

use App\Entity\User;
use App\Exception\Group\OwnerCannotBeDeletedException;
use App\Exception\Group\UserNotMemberOfGroupException;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class RemoveUserService
{

    private GroupRepository $groupRepository;
    private UserRepository $userRepository;

    public function __construct(GroupRepository $groupRepository, UserRepository $userRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository  = $userRepository;
    }

    /**
     * $requester -> Usuario que solicita la accion
     *
     * @throws \Throwable
     */
    public function remove(string $groupId, string $userId, User $requester): void
    {
        // Get user and group
        $user  = $this->userRepository->findOneByIdOrFail($userId);
        $group = $this->groupRepository->findOneByIdOrFail($groupId);

        //check if the user I want to delete is the one requesting to delete it
        if ($user->equals($requester)) {
            throw new OwnerCannotBeDeletedException();
        }

        // Check if user is member of this group
        if (!$user->isMemberOfGroup($group)) {
            throw new UserNotMemberOfGroupException();
        }

        // save data in DB with transactional operation
        $this->groupRepository->getEntityManager()->transactional(
            function (EntityManagerInterface $em) use ($group, $user) {
                $group->removeUser($user);
                $user->removeGroup($group);

                $em->persist($group);
            }
        );
    }
}