<?php

declare(strict_types=1);

namespace App\Exception;

use Throwable;

final class StartDialogWithYourself extends AccessForbidden
{
    public function __construct(
        string $message = 'You can\'t start dialog with yourself.',
        int $code = 403,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
