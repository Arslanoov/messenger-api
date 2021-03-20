<?php

declare(strict_types=1);

namespace User\Model\Event;

/*
 * TODO: Remove suppress after PHPCS PHP8 fully support
 * @see https://github.com/squizlabs/PHP_CodeSniffer/issues/3182
 */
final class UserSignedUp
{
    // @codingStandardsIgnoreStart
    public function __construct(
        public string $username
    )
    {
    }
    // @codingStandardsIgnoreEnd
}
