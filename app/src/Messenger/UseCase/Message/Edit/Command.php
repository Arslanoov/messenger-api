<?php

declare(strict_types=1);

namespace Messenger\UseCase\Message\Edit;

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
         */
        public string $authorId,
        /**
         * @var string
         * @Assert\NotBlank()
         */
        public string $messageId,
        /**
         * @var string
         * @Assert\NotBlank()
         */
        public string $newContent
    )
    {
    }
    // @codingStandardsIgnoreEnd
}
