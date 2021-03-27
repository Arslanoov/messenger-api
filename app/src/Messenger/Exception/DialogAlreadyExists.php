<?php

declare(strict_types=1);

namespace Messenger\Exception;

use Domain\Exception\DomainException;
use Throwable;

final class DialogAlreadyExists extends DomainException
{
    public function __construct(string $message = 'Dialog already exists', int $code = 419, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
