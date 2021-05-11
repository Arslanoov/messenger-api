<?php

declare(strict_types=1);

namespace Domain\Exception;

use Throwable;

final class DomainAssertionException extends DomainException
{
    public function __construct(string $message = '', int $code = 419, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
