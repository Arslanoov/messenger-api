<?php

declare(strict_types=1);

namespace Messenger\Exception;

use Domain\Exception\DomainException;
use Throwable;

final class MessageNotFound extends DomainException
{
    public function __construct(string $message = 'Message not found', int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
