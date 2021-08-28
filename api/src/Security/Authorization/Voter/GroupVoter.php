<?php
declare(strict_types=1);

namespace App\Security\Authorization\Voter;

use App\Entity\Group;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GroupVoter extends Voter
{

    // Permissions for logged user he can read your files, update, delete (only the user logged)
    public const GROUP_READ = 'GROUP_READ';
    public const GROUP_UPDATE = 'GROUP_UPDATE';
    public const GROUP_DELETE = 'GROUP_DELETE';
    public const GROUP_CREATE = 'GROUP_CREATE';


    // En este metodo indicamos attrs a soportar, si el atributo esta en el array -> true
    protected function supports(string $attribute, $subject): bool
    {
        return \in_array($attribute, $this->supportedAttributes(), true);
    }

    /**
     * El parametro $subject sera un Group o null si deseamos recuperar una coleccion.
     *
     * @param Group|null $subject
     *
     * El parametro $subject sera el grupo que queremos obtener, y en el TokenInterface tenemos
     * el token del usuario identificado
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Return true because knows user is logged.
        if ($attribute === self::GROUP_CREATE) {
            return true;
        }
        // For read, update & delete check if user is logged and is owner else return false
        if (\in_array($attribute, [self::GROUP_READ, self::GROUP_UPDATE, self::GROUP_DELETE], true)) {
            return $subject->isOwnedBy($token->getUser());
        }
        return false;
    }

    private function supportedAttributes(): array
    {
        return [
            self::GROUP_READ,
            self::GROUP_UPDATE,
            self::GROUP_DELETE,
            self::GROUP_CREATE,
        ];
    }
}