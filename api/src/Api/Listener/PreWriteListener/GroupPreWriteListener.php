<?php
declare(strict_types=1);

namespace App\Api\Listener\PreWriteListener;

use App\Entity\Group;
use App\Entity\User;
use App\Exception\Group\CannotCreateGroupForAnotherUserException;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class GroupPreWriteListener implements PreWriteListener
{
    /**
     * This const is the NAME for create new group !!!!!not URL .
     * Check your routes -> php bin/console debug:router
     * api_groups_post_collection
     */
    private const GROUP_POST = 'api_groups_post_collection';

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * Interface PreWriteListener Contract
     */
    public function onKernelView(ViewEvent $event): void
    {

        // Get user logged from token
        /** @var User|null $user */
        $user = $this->tokenStorage->getToken()
            ? $this->tokenStorage->getToken()->getUser()
            : null;

        // Get request
        $request = $event->getRequest();

        /**
         * Check if request contains name of create group route
         * In $event->getControllerResult() we can get Entity group created
         */
        if (self::GROUP_POST === $request->get('_route')) {
            /** @var Group $group */
            $group = $event->getControllerResult();

            // Check if user is owner for this group.
            if (!$group->isOwnedBy($user)) {
                throw new CannotCreateGroupForAnotherUserException();
            }
        }

    }
}