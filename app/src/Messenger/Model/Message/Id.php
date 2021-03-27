<?php

declare(strict_types=1);

namespace Messenger\Model\Message;

use Assert\AssertionFailedException;
use Domain\Exception\DomainAssertionException;
use Domain\Validation\DomainLogicAssertion;
use Exception\UnexpectedUuidType;
use Ramsey\Uuid\Uuid;

final class Id
{
    private const UUID4 = 'uuid4';

    private string $value;

    /**
     * Id constructor.
     * @param string $value
     * @throws AssertionFailedException
     * @throws DomainAssertionException
     */
    public function __construct(string $value)
    {
        DomainLogicAssertion::notBlank($value, 'Message id required');
        DomainLogicAssertion::uuid($value, 'Message id must be uuid');
        $this->value = $value;
    }

    /**
     * @param string $type
     * @return Id
     * @throws UnexpectedUuidType
     * @throws AssertionFailedException
     */
    public static function generate(string $type = self::UUID4): self
    {
        if ($type === self::UUID4) {
            return new self(Uuid::uuid4()->toString());
        }

        throw new UnexpectedUuidType();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    // TODO: Add test
    public function isEqualTo(Id $id): bool
    {
        return $this->getValue() === $id->getValue();
    }
}
