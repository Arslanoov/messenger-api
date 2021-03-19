<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Factory;

use App\User\Factory\IUserFactory;
use App\User\Service\HasherInterface;
use Domain\Model\User\Id;
use Domain\Model\User\Status;
use Domain\Model\User\User;
use Domain\Model\User\Username;

class UserFactory implements IUserFactory
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
