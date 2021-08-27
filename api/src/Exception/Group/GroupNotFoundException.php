<?php
declare(strict_types=1);

namespace App\Exception\Group;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GroupNotFoundException extends NotFoundHttpException
{

    public static function fromId(string $id): self
    {
        throw new self(\sprintf('Group with id %s not found', $id));
    }
}