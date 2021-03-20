<?php

declare(strict_types=1);

namespace Exception;

use Domain\Exception\DomainException;
use Throwable;

class UnexpectedUuidType extends DomainException
{
    public function __construct(string $message = 'Unexpected uuid type', int $code = 419, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
