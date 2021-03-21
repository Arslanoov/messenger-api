<?php

declare(strict_types=1);

namespace User\Service;

use App\Exception\WrongCredentials;

interface PasswordValidatorInterface
{
    /**
     * @throws WrongCredentials
     * @param string $password
     * @param string $hash
     */
    public function validate(string $password, string $hash): void;
}
