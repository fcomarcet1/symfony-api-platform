<?php

declare(strict_types=1);

namespace Mailer\Serializer\Messenger;

use Mailer\Messenger\Message\RequestResetPasswordMessage;
use Mailer\Messenger\Message\UserRegisteredMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;

class EventSerializer extends Serializer
{
    /**
     * This function mapping App->Mailer domain.
     */
    private function translateType(string $type): string
    {
        // le indicamos el origen y su equivalencia en nuestro dominio Mailer,
        // cada vez que llegue un mensaje este tipo lo va a transformar
        $map = [
            'App\Messenger\Message\UserRegisteredMessage' => UserRegisteredMessage::class,
            'App\Messenger\Message\RequestResetPasswordMessage' => RequestResetPasswordMessage::class,
        ];

        if (\array_key_exists($type, $map)) {
            return $map[$type];
        }

        return $type;
    }

    public function decode(array $encodedEnvelop): Envelope
    {
        // get from header --> type
        $translatedType = $this->translateType($encodedEnvelop['headers']['type']);
        $encodedEnvelop['headers']['type'] = $translatedType;

        // decimos a la clase padre de serializer que continue el proceso
        return parent::decode($encodedEnvelop);
    }
}
