<?php

declare(strict_types=1);

namespace App\User\Service;

interface HasherInterface
{
    public function hash(string $password, string $algo = PASSWORD_ARGON2ID): string;
}
