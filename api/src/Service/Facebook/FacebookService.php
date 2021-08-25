<?php
declare(strict_types=1);

namespace App\Service\Facebook;

use App\Entity\User;

class FacebookService
{

    public function __construct()
    {
    }

    // this method return token
    public function authorize(string $accessToken): string
    {
    }

    public function createUser(string $name, string $email): User
    {

    }
}