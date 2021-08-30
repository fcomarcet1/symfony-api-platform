<?php
declare(strict_types=1);

namespace App\Service\Group;

use App\Repository\GroupRepository;
use App\Repository\GroupRequestRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\MessageBusInterface;

class SendRequestToUserService
{

    private UserRepository $userRepository;
    private GroupRepository $groupRepository;
    private GroupRequestRepository $groupRequestRepository;
    private MessageBusInterface $messageBus;

    public function __construct(
        UserRepository $userRepository,
        GroupRepository $groupRepository,
        GroupRequestRepository $groupRequestRepository,
        MessageBusInterface $messageBus
    )
    {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->groupRequestRepository = $groupRequestRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @param string $groupId
     * @param string $email
     * @param string $requestedId user who sent message
     */
    public function send(string $groupId, string $email, string $requestedId): void
    {
    }
}