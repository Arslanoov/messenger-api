<?php

declare(strict_types=1);

namespace App\User;

class Status
{
    public const LIST = [
        self::ACTIVE,
        self::DRAFT
    ];

    public const ACTIVE = "Active";
    public const DRAFT = "Draft";

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function draft(): self
    {
        return new self(self::ACTIVE);
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
}
