<?php
declare(strict_types=1);

namespace App\Security\Authorization\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{

    // Permissions for logged user he can read your files, update, delete (only the user logged)
    public const USER_READ = 'USER_READ';
    public const USER_UPDATE = 'USER_UPDATE';
    public const USER_DELETE = 'USER_DELETE';


    // En este metodo indicamos attrs a soportar, si el atributo esta en el array -> true
    protected function supports(string $attribute, $subject): bool
    {
        return \in_array($attribute, $this->supportedAttributes(), true);
    }

    /**
     * El parametro $subject sera un User o null si deseamos recuperar una coleccion.
     *
     * @param User|null $subject
     *
     * el parametro $subject sera el usuario que queremos obtener, y en el TokenInterface tenemos
     * el token del usuario identificado
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if (\in_array($attribute, $this->supportedAttributes(), true)) {
            return $subject->equals($token->getUser());
        }

        return false;
    }

    private function supportedAttributes(): array
    {
        return [
            self::USER_READ,
            self::USER_UPDATE,
            self::USER_DELETE,
        ];
    }
}