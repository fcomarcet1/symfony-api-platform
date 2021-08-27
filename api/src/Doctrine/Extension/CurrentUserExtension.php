<?php
declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Group;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface
{
    // TokenStorageInterface for get user
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $qb, string $resourceClass): void
    {
        /**
         * Get user from token if exists else null
         *
         * @var User|null $user
         */
        $user = $this->tokenStorage->getToken()
            ? $this->tokenStorage->getToken()->getUser()
            : null;

        /**
         * Case for endpoint ^/users/{id}/groups.
         * We check if the user id is the same id in the token(user logged)
         * With $qb->getParameters()->first()->getValue() we can check id value of querybuilder
         */
        if (Group::class === $resourceClass) {
            if ($user->getId() !== $qb->getParameters()->first()->getValue()) {
                throw new AccessDeniedHttpException('You can\'t retrieve another user groups');
            }
        }

        /**
         * Case for endpoint ^/groups/{id}/users.
         * We check if the user is part of this group
         * With $qb->getParameters()->first()->getValue() we can check id value of querybuilder
         */
        if (User::class === $resourceClass) {
            // Get groups for logged user
            foreach ($user->getGroups() as $group) {
                // Check if group id belongs to any group for logged user
                if ($group->getId() === $qb->getParameters()->first()->getValue()) {
                    return;
                }
            }

            throw new AccessDeniedHttpException('You can\'t retrieve users of another group');
        }
    }
}