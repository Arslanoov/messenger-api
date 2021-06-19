<?php

declare(strict_types=1);

namespace User\Infrastructure\ReadModel\User;

final class AuthView
{
    public string $uuid = "";
    public string $username = "";
    public string $hash = "";
    public string $status = "";
    public string $role = "";
}
