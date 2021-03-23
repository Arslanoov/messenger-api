<?php

declare(strict_types=1);

namespace User\Infrastructure\Service;

use App\Exception\WrongCredentials;
use User\Service\PasswordValidatorInterface;

use function password_verify;

final class PasswordValidator implements PasswordValidatorInterface
{
    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
