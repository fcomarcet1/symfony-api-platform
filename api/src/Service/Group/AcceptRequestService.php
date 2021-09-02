<?php
declare(strict_types=1);

namespace App\Service\Group;

use App\Entity\GroupRequest;
use App\Repository\GroupRepository;
use App\Repository\GroupRequestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

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

    /**
     * @throws \Throwable
     */
    public function accept(string $groupId, string $userId, string $token): void
    {
        $this->groupRequestRepository->getEntityManager()->transactional(
            function (EntityManagerInterface $em) use ($groupId, $userId, $token) {
                // ########## Do operations ##############

                // Check if invite to a group is pending
                $groupRequest = $this->groupRequestRepository->findOnePendingByGroupIdUserIdAndTokenOrFail(
                    $groupId,
                    $userId,
                    $token
                );
                // set data
                $groupRequest->setStatus(GroupRequest::ACCEPTED);
                $groupRequest->setAcceptedAt(new \DateTime());

                // persist data
                $em->persist($groupRequest);

                // get user and group
                $user  = $this->userRepository->findOneByIdOrFail($userId);
                $group = $this->groupRepository->findOneByIdOrFail($groupId);

                // add user to a group and save group
                $group->addUser($user);
                $user->addGroup($group);
                $em->persist($group);
            }
        );

        /*
         * If DeleteGroupTest fails because entityManager is close, extract all we can
         * do exceptions from transaction(only persist data inside)
        $groupRequest = $this->groupRequestRepository->findOnePendingByGroupIdUserIdAndTokenOrFail(
            $groupId,
            $userId,
            $token
        );
        $groupRequest->setStatus(GroupRequest::ACCEPTED);
        $groupRequest->setAcceptedAt(new \DateTime());

        $user = $this->userRepository->findOneByIdOrFail($userId);
        $group = $this->groupRepository->findOneByIdOrFail($groupId);
        $group->addUser($user);
        $user->addGroup($group);

        $this->groupRequestRepository->getEntityManager()->transactional(
            function (EntityManagerInterface $em) use ($groupRequest, $group) {
                $em->persist($groupRequest);
                $em->persist($group);
            }
        );*/
    }
}