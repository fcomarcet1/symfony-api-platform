<?php

declare(strict_types=1);

namespace App\Api\Listener\PreWriteListener;

use App\Entity\Movement;
use App\Entity\User;
use App\Exception\Movement\CannotCreateMovementForAnotherGroupException;
use App\Exception\Movement\CannotCreateMovementToAnotherUserException;
use App\Exception\Movement\CannotUseThisCategoryInMovementException;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MovementPreWriteListener implements PreWriteListener
{
    /**
     * This const is the NAME for create new movement !!!!!not URL .
     * Check your routes -> php bin/console debug:router
     */
    // en este caso necesitamos el metodo put tambien ya que al actualizar
    // necesitamos que la categoria corresponda al usuario o al grupo
    private const MOVEMENT_POST = 'api_movements_post_collection';
    private const MOVEMENT_PUT = 'api_movements_put_item';

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelView(ViewEvent $event): void
    {
        // Get logged user
        /** @var User|null $tokenUser */
        $tokenUser = $this->tokenStorage->getToken()
            ? $this->tokenStorage->getToken()->getUser()
            : null;

        $request = $event->getRequest();

        if (\in_array($request->get('_route'), [self::MOVEMENT_POST, self::MOVEMENT_PUT], true)) {
            // Get movement from event
            /** @var Movement $movement */
            $movement = $event->getControllerResult();

            // comprobar si tiene grupo
            if (null !== $group = $movement->getGroup()) {
                // comprobar si es miembro del grupo
                if (!$tokenUser->isMemberOfGroup($group)) {
                    throw new CannotCreateMovementForAnotherGroupException();
                }
                // comprobar categoria pertenece a las categorias de ese grupo
                if (!$movement->getCategory()->belongsToGroup($group)) {
                    throw new CannotUseThisCategoryInMovementException();
                }
            }

            // si no tiene grupo comprobar si user looged es el propietario
            if (!$movement->isOwnedBy($tokenUser)) {
                throw new CannotCreateMovementToAnotherUserException();
            }

            // comprobar si la categoria del mov es propiedad del user looged
            if (!$movement->getCategory()->isOwnedBy($tokenUser)) {
                throw new CannotUseThisCategoryInMovementException();
            }
        }
    }
}
