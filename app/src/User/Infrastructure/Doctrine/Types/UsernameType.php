<?php

declare(strict_types=1);

namespace User\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use User\Model\Username;

class UsernameType extends StringType
{
    public const NAME = 'user_user_username';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof Username ? $value->getValue() : (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Username | null
    {
        return !empty($value) ? new Username((string) $value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
