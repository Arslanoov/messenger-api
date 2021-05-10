<?php

declare(strict_types=1);

namespace Messenger\UseCase\Message\Remove;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
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
        public string $messageId
    )
    {
    }
}
