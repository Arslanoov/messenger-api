<?php

declare(strict_types=1);

namespace App\User\Factory;

use App\User\UserInterface;

interface IUserFactory
{
    public function simpleRegister(string $username, string $password): UserInterface;
}
