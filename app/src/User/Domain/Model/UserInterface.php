<?php

declare(strict_types=1);

namespace Domain\Model\User;

interface UserInterface
{
    public function activate(): void;
}
