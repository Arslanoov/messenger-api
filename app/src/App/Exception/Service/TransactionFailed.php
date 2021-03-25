<?php

declare(strict_types=1);

namespace App\Exception\Service;

use Exception;
use Throwable;

final class TransactionFailed extends Exception
{
    public function __construct(string $message = 'Transaction failed', int $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
