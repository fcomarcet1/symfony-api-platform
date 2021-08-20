<?php

declare(strict_types=1);

namespace App\Service\Password;

use App\Entity\User;
use App\Exception\Password\PasswordLengthException;
use App\Exception\Password\PasswordInvalidException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EncoderService
{
    /**
     * at least one number,one lowercase letter, one uppercase letter, no spaces, and at
     * least one character that is not a letter or number.
     * And it should be between 8-16 characters.
     *
     *  REGEX: --> ^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$
     *
     *   (?=.*\d) Atleast a digit
     *   (?=.*[a-z]) Atleast a lower case letter
     *   (?=.*[A-Z]) Atleast an upper case letter
     *   (?!.* ) no space
     *   (?=.*[^a-zA-Z0-9]) at least a character except a-zA-Z0-9
     *   .{8,16} between 8 to 16 characters
     *
     */

    private const REGEX = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$/m' ;
    private const MINIMUM_LENGTH = 6;
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function generateEncodedPassword(UserInterface $user, string $password)
    {
        //TODO: Test
        /* // check with regex expresion
        if (\preg_match(self::REGEX, $password)){
            throw PasswordInvalidException::invalidPassword();
        }*/

        // check minimum length
        if (\strlen($password) < self::MINIMUM_LENGTH){
            throw PasswordLengthException::invalidLength();
        }
        // call service userPasswordEncode for encode passwd
        return $this->userPasswordEncoder->encodePassword($user, $password);
    }

    public function isValidPassword(User $user, string $oldPassword): bool
    {
        return $this->userPasswordEncoder->isPasswordValid($user, $oldPassword);
    }


}