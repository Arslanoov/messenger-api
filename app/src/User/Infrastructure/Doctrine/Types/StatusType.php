<?php

declare(strict_types=1);

namespace User\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use User\Model\Status;

class StatusType extends StringType
{
    public const NAME = 'user_user_status';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof Status ? $value->getValue() : (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Status | null
    {
        return !empty($value) ? new Status((string) $value) : null;
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
