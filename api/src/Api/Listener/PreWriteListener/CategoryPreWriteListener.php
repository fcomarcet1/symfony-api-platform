<?php
declare(strict_types=1);

namespace App\Api\Listener\PreWriteListener;

use App\Entity\Category;
use App\Entity\User;
use App\Exception\Category\CannotCreateCategoryForAnotherGroupException;
use App\Exception\Category\CannotCreateCategoryForAnotherUserException;
use App\Exception\Category\UnsupportedCategoryTypeException;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CategoryPreWriteListener implements PreWriteListener
{

    /**
     * This const is the NAME for create new group !!!!!not URL .
     * Check your routes -> php bin/console debug:router
     */
    private const CATEGORY_POST = 'api_categories_post_collection';

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    public function onKernelView(ViewEvent $event): void
    {
        // Get user logged from token
        /** @var User|null $user */
        $tokenUser = $this->tokenStorage->getToken()
            ? $this->tokenStorage->getToken()->getUser()
            : null;

        // Get request
        $request = $event->getRequest();


        // Check if request contains name of create category route
        if (self::CATEGORY_POST === $request->get('_route')) {

            /** @var Category $category */
            $category = $event->getControllerResult();

            // Get type of category
            $type = $category->getType();

            // Check if operation type is Expense(gasto) o income(ingreso)
            if (!\in_array($type, [Category::EXPENSE, Category::INCOME], true)) {
                throw UnsupportedCategoryTypeException::fromType($type);
            }

            // si viene con un grupo comprobamos que el usuario sea miembro
            if (null !== $group = $category->getGroup()) {
                if (!$tokenUser->isMemberOfGroup($group)) {
                    throw new CannotCreateCategoryForAnotherGroupException();
                }
            }
            // Si no tiene grupo comprobamos k sea el usuario logueado
            if (!$category->isOwnedBy($tokenUser)) {
                throw new CannotCreateCategoryForAnotherUserException();
            }
        }


    }
}