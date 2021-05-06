<?php

declare(strict_types=1);

namespace User\UseCase\Change\About;

use Symfony\Component\Validator\Constraints as Assert;

/*
 * TODO: Remove suppress after PHPCS PHP8 fully support
 * @see https://github.com/squizlabs/PHP_CodeSniffer/issues/3182
 */
final class Command
{
    // @codingStandardsIgnoreStart
    public function __construct(
        /**
         * @var string
         * @Assert\NotBlank()
         * @Assert\Length(min="4", max="16")
         */
        public string $username,
        /**
         * @var string
         * @Assert\Length(max="255")
         */
        public string $newAboutInfo
    )
    {
    }
    // @codingStandardsIgnoreEnd
}
