<?php

declare(strict_types=1);

namespace User\Model;

interface UserRepositoryInterface
{
    public function hasByUsername(Username $username): bool;
}
