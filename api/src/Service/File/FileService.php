<?php
declare(strict_types=1);

namespace App\Service\File;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    private FilesystemOperator $defaultStorage;
    private LoggerInterface $logger;
    private string $mediaPath;

    const VISIBILITY_PUBLIC = 'public';
    const VISIBILITY_PRIVATE = 'private';

    // The variable name $defaultStorage matters: it needs to be the camelized version
    // of the name of your storage.
    public function __construct(
        FilesystemOperator $defaultStorage,
        LoggerInterface $logger,
        string $mediaPath
    ) {
        $this->defaultStorage = $defaultStorage;
        $this->logger         = $logger;
        $this->mediaPath      = $mediaPath;
    }

    /**
     * @throws FilesystemException
     */
    public function uploadFile(UploadedFile $file, string $prefix): string
    {
        $fileName = \sprintf('%s/%s.%s', $prefix, \sha1(\uniqid()), $file->guessExtension());


        // Set Visibility
        $this->defaultStorage->visibility(self::VISIBILITY_PUBLIC);

        $this->defaultStorage->writeStream(
            $fileName,
            \fopen($file->getPathname(), 'r')
        );

        return $fileName;
    }

    public function deleteFile(?string $path): void
    {

    }
}