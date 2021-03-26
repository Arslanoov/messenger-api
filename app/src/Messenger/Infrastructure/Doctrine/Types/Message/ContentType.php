<?php

declare(strict_types=1);

namespace Messenger\Infrastructure\Doctrine\Types\Message;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Messenger\Model\Message\Content;

final class ContentType extends StringType
{
    public const NAME = 'messenger_message_content';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof Content ? $value->getValue() : (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Content | null
    {
        return !empty($value) ? new Content((string) $value) : null;
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
