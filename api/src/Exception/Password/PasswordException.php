<?php

declare(strict_types=1);

namespace App\Exception\Password;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PasswordException extends BadRequestHttpException
{
    public static function invalidLength(): self
    {
        throw new self('Password must be at least 6 characters');
    }

    public static function oldPasswordDoesNotMatch(): self
    {
        throw new self('Old password does not match');
    }

    public static function invalidPassword (): self
    {
        throw new self('at least one number,
                        one lowercase letter, 
                        one uppercase letter, 
                        no spaces, 
                        and at least one character that is not a letter or number.
                        And it should be between 8-16 characters');
    }


}
