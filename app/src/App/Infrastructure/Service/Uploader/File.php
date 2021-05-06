<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Uploader;

/*
 * TODO: Remove suppress after PHPCS PHP8 fully support
 * @see https://github.com/squizlabs/PHP_CodeSniffer/issues/3182
 */
final class File
{
    // @codingStandardsIgnoreStart
    public function __construct(
        public string $path,
        public string $name,
        public int $size
    ) {
    }
    // @codingStandardsIgnoreEnd
}
