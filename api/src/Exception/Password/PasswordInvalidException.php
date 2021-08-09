<?php

namespace App\Exception\Password;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PasswordInvalidException extends BadRequestHttpException
{
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