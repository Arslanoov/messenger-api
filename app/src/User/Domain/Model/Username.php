<?php

declare(strict_types=1);

namespace Domain\Model\User;

use Assert\Assertion;

class Username
{
    private string $value;

    public function __construct(string $value)
    {
        Assertion::notBlank($value, 'User name required');
        Assertion::betweenLength(
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
