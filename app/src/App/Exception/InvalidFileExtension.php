<?php

declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;
use Throwable;

final class InvalidFileExtension extends InvalidArgumentException
{
    // TODO: Added white list ext's
    public function __construct(string $message = 'Invalid file extension', int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
