<?php

declare(strict_types=1);

namespace User\Infrastructure\Factory;

use User\Factory\UserFactoryInterface;
use User\Model\Id;
use User\Model\Status;
use User\Model\User;
use User\Model\Username;
use User\Service\HasherInterface;

class UserFactory implements UserFactoryInterface
{
    private HasherInterface $hasher;

    public function __construct(HasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function simpleRegister(string $username, string $password): User
    {
        return new User(Id::generate(), new Username($username), $this->hasher->hash($password), Status::draft());
    }
}
