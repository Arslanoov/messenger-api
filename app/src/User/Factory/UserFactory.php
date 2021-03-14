<?php

declare(strict_types=1);

namespace App\User\Factory;

use App\User\Id;
use App\User\Service\Hasher;
use App\User\Status;
use App\User\User;
use App\User\Username;

class UserFactory
{
    private Hasher $hasher;

    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function simpleRegister(string $username, string $password): User
    {
        return new User(Id::generate(), new Username($username), $this->hasher->hash($password), Status::draft());
    }
}
