<?php

declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;
use Throwable;

class InvalidAvatar extends InvalidArgumentException
{
    public function __construct(string $message = 'Invalid avatar', int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
