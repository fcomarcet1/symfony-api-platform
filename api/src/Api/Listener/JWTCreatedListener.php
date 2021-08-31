<?php

declare(strict_types=1);

namespace App\Api\Listener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    // Add UserId && Client IP in token
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();
        $request = $this->requestStack->getCurrentRequest();

        $payload = $event->getData();
        $payload['userId'] = $user->getId();
        //$payload['ip'] = $request->getClientIp();
        unset($payload['roles']);

        $event->setData($payload);

    }

}