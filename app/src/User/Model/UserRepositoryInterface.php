<?php

declare(strict_types=1);

namespace User\Model;

use User\Exception\NotFound;

interface UserRepositoryInterface
{
    /**
     * @throws NotFound
     * @param Username $username
     * @return User
     */
    public function getByUsername(Username $username): User;

    public function hasByUsername(Username $username): bool;
}
