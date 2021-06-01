<?php

declare(strict_types=1);

namespace User\Model;

use Assert\AssertionFailedException;
use Domain\Exception\DomainAssertionException;
use Domain\Validation\DomainLogicAssertion;

class Role
{
    public const LIST = [
        self::USER,
        self::ADMIN
    ];

    public const USER = 'User';
    public const ADMIN = 'Admin';

    private string $value;

    /**
     * Role constructor.
     * @param string $value
     * @throws AssertionFailedException
     * @throws DomainAssertionException
     */
    public function __construct(string $value)
    {
        DomainLogicAssertion::inArray($value, self::LIST, 'Incorrect role');
        $this->value = $value;
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isUser(): bool
    {
        return $this->value === self::USER;
    }

    public function isAdmin(): bool
    {
        return $this->value === self::ADMIN;
    }

    public function isEqual(Role $role): bool
    {
        return $this->getValue() === $role->getValue();
    }
}
