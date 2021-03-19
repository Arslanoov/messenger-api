<?php

declare(strict_types=1);

namespace App\User\Exception;

use App\Exception\DomainException;
use Throwable;

class AlreadyActivated extends DomainException
{
    public function __construct(string $message = 'Already activated', int $code = 419, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
