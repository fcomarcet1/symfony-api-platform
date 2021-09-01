<?php
declare(strict_types=1);

namespace App\Service\Group;

use App\Repository\GroupRepository;
use App\Repository\GroupRequestRepository;
use App\Repository\UserRepository;

class AcceptRequestService
{

    private GroupRequestRepository $groupRequestRepository;
    private GroupRepository $groupRepository;
    private UserRepository $userRepository;

    public function __construct(
        GroupRequestRepository $groupRequestRepository,
        GroupRepository $groupRepository,
        UserRepository $userRepository
    ) {
        $this->groupRequestRepository = $groupRequestRepository;
        $this->groupRepository        = $groupRepository;
        $this->userRepository         = $userRepository;
    }

    public function accept(string $groupId, string $userId, string $token): void
    {
    }
}