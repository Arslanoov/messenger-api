<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

final class UserNotFound extends NotFound
{
    public function __construct(
        string $message = 'User not found.',
        int $code = 404,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
