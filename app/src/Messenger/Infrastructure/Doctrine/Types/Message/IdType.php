<?php

declare(strict_types=1);

namespace Messenger\Infrastructure\Doctrine\Types\Message;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Messenger\Model\Message\Id;

final class IdType extends GuidType
{
    public const NAME = 'messenger_message_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof Id ? $value->getValue() : (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Id | null
    {
        return !empty($value) ? new Id((string) $value) : null;
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
