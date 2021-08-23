<?php
declare(strict_types=1);

namespace App\Service\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{

    public function __construct()
    {
    }

    public function uploadFile(UploadedFile $file, string $prefix): string
    {
        return '';
    }

    public function deleteFile(?string $path): void
    {

    }
}