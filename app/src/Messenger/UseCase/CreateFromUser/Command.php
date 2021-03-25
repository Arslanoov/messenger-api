<?php

declare(strict_types=1);

namespace Messenger\UseCase\CreateFromUser;

use Symfony\Component\Validator\Constraints as Assert;
use User\Model\User;

// TODO: Check BC violation

/*
 * TODO: Remove suppress after PHPCS PHP8 fully support
 * @see https://github.com/squizlabs/PHP_CodeSniffer/issues/3182
 */
final class Command
{
    // @codingStandardsIgnoreStart
    public function __construct(
        /**
         * @var User
         * @Assert\NotBlank()
         */
        public User $user
    )
    {
    }
    // @codingStandardsIgnoreEnd
}
