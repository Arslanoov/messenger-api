<?php

declare(strict_types=1);

namespace App\Service;

use App\Infrastructure\Service\Uploader\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploader
{
    public function upload(UploadedFile $file, string $prefix = '/', string $name = ''): File;

    /* TODO: Move to *FileRemover* ? */
    public function remove(string $path, string $name): void;
}
