<?php

declare(strict_types=1);

namespace App\User\UseCase\SignUp\Request;

class Command
{
    public function __construct(
        public string $username,
        public string $password
    )
    {
    }
}
