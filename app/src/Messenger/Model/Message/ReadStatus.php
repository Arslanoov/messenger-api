<?php

declare(strict_types=1);

namespace Messenger\Model\Message;

use Domain\Validation\DomainLogicAssertion;

final class ReadStatus
{
    private const LIST = [
        self::NOT_READ,
        self::READ
    ];

    private const NOT_READ = "Not Read";
    private const READ = "Read";

    private string $value;

    public function __construct(string $value)
    {
        DomainLogicAssertion::inArray($value, self::LIST, 'Incorrect message read status');
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isRead(): bool
    {
        return $this->value === self::READ;
    }

    public static function notRead(): self
    {
        return new self(self::NOT_READ);
    }

    public static function read(): self
    {
        return new self(self::READ);
    }
}
