<?php

declare(strict_types=1);

namespace User\Model\Event;

final class UserSignedUp
{
    public function __construct(
        public string $username
    )
    {
    }
}
