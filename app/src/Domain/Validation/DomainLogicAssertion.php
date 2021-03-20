<?php

declare(strict_types=1);

namespace Domain\Validation;

use Assert\Assertion as BaseAssertion;
use Domain\Exception\DomainAssertionException;

class DomainLogicAssertion extends BaseAssertion
{
    protected static $exceptionClass = DomainAssertionException::class;
}
