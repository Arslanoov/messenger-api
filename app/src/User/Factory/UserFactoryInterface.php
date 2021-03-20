<?php

declare(strict_types=1);

namespace User\Factory;

use User\Model\User;

interface UserFactoryInterface
{
    public function simpleRegister(string $username, string $password): User;
}
