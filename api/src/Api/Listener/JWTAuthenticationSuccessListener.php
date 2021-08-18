<?php
declare(strict_types=1);

namespace App\Api\Listener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class JWTAuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = [
            'code' => 200,
            'message' => 'authentication successfull',
            'data' => $event->getData(),
        ];
        $event->setData($data);
    }
}
