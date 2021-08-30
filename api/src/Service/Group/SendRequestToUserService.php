<?php
declare(strict_types=1);

namespace App\Service\Group;

use App\Entity\GroupRequest;
use App\Exception\Group\NotOwnerOfGroupException;
use App\Exception\Group\UserAlreadyMemberOfGroupException;
use App\Messenger\Message\GroupRequestMessage;
use App\Messenger\RoutingKey;
use App\Repository\GroupRepository;
use App\Repository\GroupRequestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
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
    ) {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->groupRequestRepository = $groupRequestRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @param string $groupId
     * @param string $email
     * @param string $requestedId user who sent message for invite another user
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function send(string $groupId, string $email, string $requestedId): void
    {
        // Get group, Requester(Sender), Received
        $group     = $this->groupRepository->findOneByIdOrFail($groupId);
        $requester = $this->userRepository->findOneByIdOrFail($requestedId);
        $receiver  = $this->userRepository->findOneByEmailOrFail($email);

        // Check if requester the owner of the group
        if (!$group->isOwnedBy($requester)){
            throw new NotOwnerOfGroupException();
        }

        // Check if the receiver is already part of the group to which we invite
        if ($group->containsUser($receiver)){
            throw new UserAlreadyMemberOfGroupException();
        }

        // Create new GroupRequest for get token to send
        $groupRequest = new GroupRequest($group, $receiver);

        // save in DB ¿catch exceptions?
        $this->groupRequestRepository->save($groupRequest);

        // Send message to rabbitmq
        $this->messageBus->dispatch(
            new GroupRequestMessage(
                $groupId,
                $receiver->getId(),
                $groupRequest->getToken(),
                $requester->getName(),
                $group->getName(),
                $receiver->getEmail()
            ),
            [new AmqpStamp(RoutingKey::GROUP_QUEUE)]
        );

    }
}