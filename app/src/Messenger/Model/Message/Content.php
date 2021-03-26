<?php

declare(strict_types=1);

namespace Messenger\Model\Message;

use Domain\Validation\DomainLogicAssertion;

final class Content
{
    private string $value;

    public function __construct(string $value)
    {
        DomainLogicAssertion::maxLength($value, 1024, 'Too long message');
        DomainLogicAssertion::notBlank($value, 'Message can\'t be empty');
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
