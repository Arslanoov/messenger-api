<?php

declare(strict_types=1);

namespace User\UseCase\SignUp\Request;

/*
 * TODO: Remove suppress after PHPCS PHP8 fully support
 * @see https://github.com/squizlabs/PHP_CodeSniffer/issues/3182
 */
class Command
{
    // @codingStandardsIgnoreStart
    public function __construct(
        public string $username,
        public string $password
    )
    {
    }
    // @codingStandardsIgnoreEnd
}
