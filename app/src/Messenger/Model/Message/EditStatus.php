<?php

declare(strict_types=1);

namespace Messenger\Model\Message;

use Domain\Validation\DomainLogicAssertion;

final class EditStatus
{
    private const LIST = [
        self::NOT_EDITED,
        self::EDITED
    ];

    private const NOT_EDITED = "Not Edited";
    private const EDITED = "Edited";

    private string $value;

    public function __construct(string $value)
    {
        DomainLogicAssertion::inArray($value, self::LIST, 'Incorrect message status');
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEdited(): bool
    {
        return $this->value === self::EDITED;
    }

    public static function notEdited(): self
    {
        return new self(self::NOT_EDITED);
    }

    public static function edited(): self
    {
        return new self(self::EDITED);
    }
}
