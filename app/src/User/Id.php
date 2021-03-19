<?php

declare(strict_types=1);

namespace App\User;

use App\Exception\UnexpectedUuidType;
use Ramsey\Uuid\Uuid;

class Id
{
    private const UUID4 = "uuid4";

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws UnexpectedUuidType
     * @param string $type
     * @return self
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
}
