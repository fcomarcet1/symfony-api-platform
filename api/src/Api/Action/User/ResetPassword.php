<?php

declare(strict_types=1);

namespace App\Api\Action\User;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class ResetPassword
{
    public function __construct()
    {
    }

    public function __invoke(Request $request): User
    {
    }
}