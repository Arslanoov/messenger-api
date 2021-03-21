<?php

declare(strict_types=1);

namespace User\Infrastructure\ReadModel\User;

final class AuthView
{
    public string $id;
    public string $username;
    public string $password;
    public string $status;
}
