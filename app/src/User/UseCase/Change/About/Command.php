<?php

declare(strict_types=1);

namespace User\UseCase\Change\About;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
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
}
