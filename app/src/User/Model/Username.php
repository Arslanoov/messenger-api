<?php

declare(strict_types=1);

namespace User\Model;

use Assert\AssertionFailedException;
use Domain\Exception\DomainAssertionException;
use Domain\Validation\DomainLogicAssertion;

class Username
{
    private string $value;

    /**
     * Username constructor.
     * @param string $value
     * @throws AssertionFailedException
     * @throws DomainAssertionException
     */
    public function __construct(string $value)
    {
        DomainLogicAssertion::notBlank($value, 'User name required');
        DomainLogicAssertion::betweenLength(
            $value,
            4,
            16,
            'User name must be between 4 and 16 chars length'
        );
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(Username $username): bool
    {
        return $this->getValue() === $username->getValue();
    }
}
