<?php

declare(strict_types=1);

namespace App\Service;

interface UuidGenerator
{
    public function uuid4(): string;
}
