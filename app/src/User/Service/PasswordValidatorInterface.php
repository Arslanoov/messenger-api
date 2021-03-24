<?php

declare(strict_types=1);

namespace User\Service;

interface PasswordValidatorInterface
{
    public function validate(string $password, string $hash): bool;
}
