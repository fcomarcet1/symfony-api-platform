<?php
declare(strict_types=1);

namespace App\Service\File;

use League\Flysystem\AdapterInterface;
use League\Flysystem\FileExistsException;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FileService
{
    public const AVATAR_INPUT_NAME = 'avatar';
    public const MOVEMENT_INPUT_NAME = 'file';

    private FilesystemInterface $defaultStorage;
    private LoggerInterface $logger;
    private string $mediaPath;

    // The variable name $defaultStorage matters: it needs to be the camelize version
    // of the name of your storage.
    public function __construct(
        FilesystemInterface $defaultStorage,
        LoggerInterface $logger,
        string $mediaPath
    ) {
        $this->defaultStorage = $defaultStorage;
        $this->logger         = $logger;
        $this->mediaPath      = $mediaPath;
    }


    /**
     * @throws FileExistsException
     */
    public function uploadFile(UploadedFile $file, string $prefix): string
    {
        $fileName = \sprintf('%s/%s.%s', $prefix, \sha1(\uniqid()), $file->guessExtension());

        $this->defaultStorage->writeStream(
            $fileName,
            \fopen($file->getPathname(), 'r'),
            ['visibility' => AdapterInterface::VISIBILITY_PUBLIC]
        );

        /*try {
          $this->defaultStorage->writeStream(
              $fileName,
              \fopen($file->getPathname(), 'r'),
              ['visibility' => AdapterInterface::VISIBILITY_PUBLIC]
          );
      } catch (UnableToWriteFileException $e) {
          throw new UnableToWriteFileException($fileName);
      }*/

        return $fileName;
    }

    // Get file && Validate input field "avatar" in json
    public function validateFile(Request $request, string $inputName): UploadedFile
    {
        //$file = $request->files->get($inputName);
        if (null === $file = $request->files->get($inputName)) {
            throw new BadRequestHttpException(
                \sprintf('Cannot get file with input name %s', $inputName)
            );
        }

        return $file;
    }


    public function deleteFile(?string $path): void
    {
        try {
            if ($path !== null) {
                $this->defaultStorage->delete($path);
            }
        } catch (\Exception $e) {
            $this->logger->warning(\sprintf('File %s not found in the storage', $path));
        }
    }
}