<?php
declare(strict_types=1);

namespace App\Service\Movement;

use App\Entity\Movement;
use App\Entity\User;
use App\Exception\Movement\MovementDoesNotBelongToGroupException;
use App\Exception\Movement\MovementDoesNotBelongToUserException;
use App\Repository\MovementRepository;
use App\Service\File\FileService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use League\Flysystem\AdapterInterface;
use League\Flysystem\FileExistsException;
use Symfony\Component\HttpFoundation\Request;

class UploadFileService
{

    private FileService $fileService;
    private MovementRepository $movementRepository;

    public function __construct(FileService $fileService, MovementRepository $movementRepository)
    {
        $this->fileService        = $fileService;
        $this->movementRepository = $movementRepository;
    }

    /**
     * @throws FileExistsException
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function uploadFile(Request $request, User $user, string $id): Movement
    {
        // Get movement
        $movement = $this->movementRepository->findOneByIdOrFail($id);

        // Check if movement has a group and if user is member
        if (null !== $group = $movement->getGroup()) {
            if (!$user->isMemberOfGroup($group)) {
                throw new MovementDoesNotBelongToGroupException();
            }
        }

        // Check if user is owner of movement
        if (!$movement->isOwnedBy($user)) {
            throw new MovementDoesNotBelongToUserException();
        }

        // Validate file
        $file = $this->fileService->getAndValidateFile($request, FileService::MOVEMENT_INPUT_NAME);

        // delete file with the same path if exists
        $this->fileService->deleteFile($movement->getFilePath());

        // Upload file to Digital Ocean Cloud.
        $fileName = $this->fileService->uploadFile(
            $file,
            FileService::MOVEMENT_INPUT_NAME,
            AdapterInterface::VISIBILITY_PRIVATE
        );

        // Set filePath and save in DB
        $movement->setFilePath($fileName);
        $this->movementRepository->save($movement);

        return $movement;

    }
}