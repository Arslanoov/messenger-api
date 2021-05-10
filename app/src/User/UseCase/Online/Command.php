<?php

declare(strict_types=1);

namespace User\UseCase\Online;

use Symfony\Component\Validator\Constraints as Assert;

/*
 * TODO: Remove suppress
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
        public string $username
    )
    {
    }
    // @codingStandardsIgnoreEnd
}
