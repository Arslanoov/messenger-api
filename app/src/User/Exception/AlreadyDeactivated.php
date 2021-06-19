<?php

declare(strict_types=1);

namespace User\Exception;

use Domain\Exception\DomainException;
use Throwable;

final class AlreadyDeactivated extends DomainException
{
    public function __construct(string $message = 'Already deactivated', int $code = 419, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
