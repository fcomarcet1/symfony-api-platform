<?php
declare(strict_types=1);

namespace App\Service\Movement;

use App\Entity\Movement;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UploadFileService
{

    public function __construct()
    {
    }

    public function uploadFile(Request $request, User $user, string $id): Movement
    {
    }
}