<?php

declare(strict_types=1);

namespace App\User;

class Username
{
    private string $value;

    public function __construct(string $value)
    {
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
