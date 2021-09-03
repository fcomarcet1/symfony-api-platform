<?php
declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Category;
use App\Entity\Group;
use App\Entity\User;
use App\Exception\Group\GroupNotFoundException;
use App\Repository\GroupRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface
{
    // TokenStorageInterface for get user
    private TokenStorageInterface $tokenStorage;
    private GroupRepository $groupRepository;

    public function __construct(TokenStorageInterface $tokenStorage, GroupRepository $groupRepository)
    {
        $this->tokenStorage    = $tokenStorage;
        $this->groupRepository = $groupRepository;
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
         * Get user from token
         *
         * @var User|null $user
         */
        $user = $this->tokenStorage->getToken()
            ? $this->tokenStorage->getToken()->getUser()
            : null;

        //
        $rootAlias = $qb->getRootAliases()[0];

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

        if (Category::class === $resourceClass) {
            $parameterId = $qb->getParameters()->first()->getValue();


            // Check is a group && logged user is member of this group add where in query
            if ($this->isGroupAndUserIsMember($parameterId, $user)) {
                //Get Categories from a group ^/api/v1/groups/{id}/categories
                // pass group parameter for get all categories
                $qb->andWhere(\sprintf('%s.group = :parameterId', $rootAlias));
                $qb->setParameter('parameterId', $parameterId);
            }
            else {
                // Get categories from a user ^/api/v1/users/{id}/categories
                // Check if user is the owner of group
                $qb->andWhere(\sprintf('%s.%s = :user', $rootAlias, $this->getResources()[$resourceClass]));
                //
                $qb->andWhere(\sprintf('%s.group IS NULL', $rootAlias));
                $qb->setParameter('user', $user);
            }
        }
    }

    // Check if parameterId is a group, and if user logged is member of this group
    private function isGroupAndUserIsMember(string $parameterId, User $user): bool
    {
        try {
            return $user->isMemberOfGroup(
                $this->groupRepository->findOneByIdOrFail($parameterId)
            );
        } catch (GroupNotFoundException $e) {
            return false;
        }
    }

    // Get relation Category-User
    private function getResources(): array
    {
        return [Category::class => 'owner'];
    }
}