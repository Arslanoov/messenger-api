<?php

declare(strict_types=1);

namespace Exception;

use RuntimeException;
use Throwable;

class HashError extends RuntimeException
{
    public function __construct(string $message = 'Hash error', int $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
