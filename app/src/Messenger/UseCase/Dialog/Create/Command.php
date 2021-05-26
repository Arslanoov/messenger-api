<?php

declare(strict_types=1);

namespace Messenger\UseCase\Dialog\Create;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        /**
         * @var string
         * @Assert\NotBlank()
         */
        public string $dialogId,
        /**
         * @var string
         * @Assert\NotBlank()
         */
        public string $firstAuthorId,
        /**
         * @var string
         * @Assert\NotBlank()
         */
        public string $secondAuthorId
    )
    {
    }
}
