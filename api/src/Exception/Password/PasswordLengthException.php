<?php

declare(strict_types=1);

namespace App\Exception\Password;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PasswordLengthException extends BadRequestHttpException
{
    public static function invalidLength(): self
    {
        throw new self('Password must be at least 6 characters');
    }
}