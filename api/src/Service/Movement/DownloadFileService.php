<?php
declare(strict_types=1);

namespace App\Service\Movement;

use App\Entity\User;
use App\Exception\Movement\MovementDoesNotBelongToGroupException;
use App\Exception\Movement\MovementDoesNotBelongToUserException;
use App\Repository\MovementRepository;
use App\Service\File\FileService;

class DownloadFileService
{

    private MovementRepository $movementRepository;
    private FileService $fileService;

    public function __construct(FileService $fileService, MovementRepository $movementRepository)
    {
        $this->movementRepository = $movementRepository;
        $this->fileService        = $fileService;
    }

    public function downloadFile(User $user, string $filepath): ?string
    {
        // Get movement to download
        $movement = $this->movementRepository->findOneByFilePathOrFail($filepath);

        // Check if exists group and user is member of this group
        if (null === $group = $movement->getGroup()) {
            if (!$user->isMemberOfGroup($group)) {
                throw new MovementDoesNotBelongToGroupException();
            }
        }

        // Check if user is owner of movement
        if (!$movement->isOwnedBy($user)) {
            throw new MovementDoesNotBelongToUserException();
        }

        return $this->fileService->downloadFile($filepath);
    }
}