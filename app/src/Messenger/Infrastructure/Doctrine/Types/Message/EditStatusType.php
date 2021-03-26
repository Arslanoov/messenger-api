<?php

declare(strict_types=1);

namespace Messenger\Infrastructure\Doctrine\Types\Message;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Messenger\Model\Message\EditStatus;

final class EditStatusType extends StringType
{
    public const NAME = 'messenger_message_edit_status';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof EditStatus ? $value->getValue() : (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): EditStatus | null
    {
        return !empty($value) ? new EditStatus((string) $value) : null;
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
