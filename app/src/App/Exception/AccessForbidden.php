<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class AccessForbidden extends Exception
{
    public function __construct(string $message = "Forbidden", int $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
