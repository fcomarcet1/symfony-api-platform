<?php
declare(strict_types=1);

namespace App\Security\Authorization\Voter;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CategoryVoter extends Voter
{

    // Permissions for logged user he can read your files, update, delete (only the user logged)
    public const CATEGORY_READ = 'CATEGORY_READ';
    public const CATEGORY_UPDATE = 'CATEGORY_UPDATE';
    public const CATEGORY_DELETE = 'CATEGORY_DELETE';
    public const CATEGORY_CREATE = 'CATEGORY_CREATE';


    // En este metodo indicamos attrs a soportar, si el atributo esta en el array -> true
    protected function supports(string $attribute, $subject): bool
    {
        return \in_array($attribute, $this->supportedAttributes(), true);
    }

    /**
     * El parametro $subject sera un Category o null si deseamos recuperar una coleccion.
     *
     * @param Category|null $subject
     *
     * El parametro $subject sera la categoria que queremos obtener, y en el TokenInterface tenemos
     * el token del usuario identificado
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Get logged user
        /** @var User $tokenUser */
        $tokenUser = $token->getUser();

        // Allow creates categories logged users
        if ($attribute === self::CATEGORY_CREATE) {
            return true;
        }

        // Check if subject has a group 
        $group = $subject->getGroup();
        if ($group !== null) {
            // Check if user is member of this group
            if (\in_array($attribute, [self::CATEGORY_READ, self::CATEGORY_UPDATE, self::CATEGORY_DELETE,], true)) {
                return $tokenUser->isMemberOfGroup($group);
            }
        }

        // Case user has no group, Check if user is owner of this category
        if (\in_array($attribute, [self::CATEGORY_READ, self::CATEGORY_UPDATE, self::CATEGORY_DELETE], true)) {
            return $subject->isOwnedBy($tokenUser);
        }

        return false;
    }

    private function supportedAttributes(): array
    {
        return [
            self::CATEGORY_READ,
            self::CATEGORY_UPDATE,
            self::CATEGORY_DELETE,
            self::CATEGORY_CREATE,
        ];
    }
}