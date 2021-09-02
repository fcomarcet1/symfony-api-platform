<?php
declare(strict_types=1);

namespace App\Service\Group;

use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;

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
     */
    public function remove(string $groupId, string $userId, User $requester): void
    {
    }
}