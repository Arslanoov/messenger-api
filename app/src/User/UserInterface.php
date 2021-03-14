<?php

declare(strict_types=1);

namespace App\User;

interface UserInterface
{
    public function activate(): void;
}