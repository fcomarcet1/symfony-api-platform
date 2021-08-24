<?php
declare(strict_types=1);

namespace App\Service\User;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\File\FileService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class UploadAvatarService
{

    private UserRepository $userRepository;
    private FileService $fileService;
    private string $mediaPath;

    public function __construct(
        UserRepository $userRepository,
        FileService $fileService,
        string $mediaPath
    ) {
        $this->userRepository = $userRepository;
        $this->fileService    = $fileService;
        $this->mediaPath      = $mediaPath;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function uploadAvatar(Request $request, User $user): User
    {
        // Get file && Validate input field "avatar" in json from request
        $file = $this->fileService->validateFile($request, FileService::AVATAR_INPUT_NAME);

        //Remove the user's current avatar from cloud
        $this->fileService->deleteFile($user->getAvatar());

        // Upload new file && get fileName
        $fileName = $this->fileService->uploadFile($file, FileService::AVATAR_INPUT_NAME);

        // Save fileName in DB
        $user->setAvatar($fileName);
        $this->userRepository->save($user);

        return $user;
    }
}