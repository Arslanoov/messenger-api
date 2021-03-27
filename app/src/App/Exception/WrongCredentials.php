<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

final class WrongCredentials extends AccessForbidden
{
    public function __construct(string $message = "Wrong credentials", int $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
