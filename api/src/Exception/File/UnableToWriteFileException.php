<?php
declare(strict_types=1);

namespace App\Exception\File;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UnableToWriteFileException extends FileException
{
    private const MESSAGE = 'Unable to write %s file';

    public static function fromFileName(string $filename): self
    {
        throw new self(\sprintf(self::MESSAGE, $filename));
    }

}