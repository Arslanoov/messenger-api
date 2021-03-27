<?php

declare(strict_types=1);

namespace Exception;

use Exception;
use Throwable;

final class IncorrectPage extends Exception
{
    public function __construct(string $message = 'Incorrect page', int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
