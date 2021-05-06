<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Uploader;

use App\Service\FileUploader;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FlysystemFileUploader implements FileUploader
{
    private FilesystemOperator $storage;

    public function __construct(FilesystemOperator $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param UploadedFile $file
     * @param string $prefix
     * @param string $name
     * @return File
     * @throws FilesystemException
     */
    public function upload(UploadedFile $file, string $prefix = '/', string $name = ''): File
    {
        $fileName = $name . '.' . $file->getClientOriginalExtension();
        if (!$name) {
            $fileName = $name . '.' . $file->getClientOriginalExtension();
        }

        $this->storage->createDirectory($prefix);
        // TODO: Refactor
        $this->storage->delete($prefix . $name . '.jpg');
        $this->storage->delete($prefix . $name . '.jpeg');
        $this->storage->delete($prefix . $name . '.png');
        $stream = fopen($file->getRealPath(), 'rb+');
        $this->storage->writeStream($prefix . '/' . $fileName, $stream);
        fclose($stream);

        return new File($prefix, $name . '.' . $file->getClientOriginalExtension(), $file->getSize());
    }

    /**
     * @param string $path
     * @param string $name
     * @throws FilesystemException
     */
    public function remove(string $path, string $name): void
    {
        $this->storage->delete($path . '/' . $name);
    }
}
