<?php

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    private const MESSAGE = 'User with email %s not found';

    public static function fromEmail (string $email)
    {
        throw new self(\sprintf(self::MESSAGE, $email));
    }
}