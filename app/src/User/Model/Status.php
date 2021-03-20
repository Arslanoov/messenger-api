<?php

declare(strict_types=1);

namespace User\Model;

use Assert\AssertionFailedException;
use Domain\Exception\DomainAssertionException;
use Domain\Validation\DomainLogicAssertion;

class Status
{
    public const LIST = [
        self::ACTIVE,
        self::DRAFT
    ];

    public const ACTIVE = 'Active';
    public const DRAFT = 'Draft';

    private string $value;

    /**
     * Status constructor.
     * @param string $value
     * @throws AssertionFailedException
     * @throws DomainAssertionException
     */
    public function __construct(string $value)
    {
        DomainLogicAssertion::inArray($value, self::LIST, 'Incorrect user status');
        $this->value = $value;
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function draft(): self
    {
        return new self(self::DRAFT);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isDraft(): bool
    {
        return $this->value === self::DRAFT;
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function isEqual(Status $status): bool
    {
        return $this->getValue() === $status->getValue();
    }
}
