<?php

declare(strict_types=1);

namespace User\Model;

interface UserInterface
{
    public function activate(): void;
}
