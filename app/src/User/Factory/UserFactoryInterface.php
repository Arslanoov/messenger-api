<?php

declare(strict_types=1);

namespace App\User\Factory;

use Domain\Model\User\UserInterface;

interface UserFactoryInterface
{
    public function simpleRegister(string $username, string $password): UserInterface;
}
