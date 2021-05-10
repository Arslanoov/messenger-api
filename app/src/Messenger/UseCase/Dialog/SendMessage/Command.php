<?php

declare(strict_types=1);

namespace Messenger\UseCase\Dialog\SendMessage;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    public function __construct(
        /**
         * @var string
         * @Assert\NotBlank()
         */
        public string $id
    )
    {
    }
}
