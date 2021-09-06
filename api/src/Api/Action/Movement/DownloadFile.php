<?php
declare(strict_types=1);

namespace App\Api\Action\Movement;

use App\Entity\User;
use App\Service\Movement\DownloadFileService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DownloadFile
{

    private DownloadFileService $downloadFileService;

    public function __construct(DownloadFileService $downloadFileService)
    {
        $this->downloadFileService = $downloadFileService;
    }

    public function __invoke(Request $request, User $user): Response
    {


        $response = new Response();
        return $response;
    }
}