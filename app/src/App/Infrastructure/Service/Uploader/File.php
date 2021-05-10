<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Uploader;

final class File
{
    public function __construct(
        public string $path,
        public string $name,
        public int $size
    ) {
    }
}
