<?php

declare(strict_types=1);

namespace User\Infrastructure\Service;

use Exception\HashError;
use User\Service\HasherInterface;

final class Hasher implements HasherInterface
{
    /**
     * Hasher constructor.
     * @param array<string|int> $options
     */
    public function __construct(private array $options = [
        'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST
    ])
    {
    }

    /**
     * @see https://secure.php.net/manual/en/password.constants.php
     * @throws HashError
     * @param string $password
     * @param string $algo
     * @return string
     */
    public function hash(string $password, string $algo = PASSWORD_ARGON2ID): string
    {
        /** @var string|false|null $hash */
        $hash = password_hash($password, $algo, $this->options);

        if (null === $hash || false === $hash) {
            throw new HashError();
        }

        return $hash;
    }
}
