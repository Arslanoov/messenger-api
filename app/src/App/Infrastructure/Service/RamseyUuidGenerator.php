<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Service\UuidGenerator;
use Ramsey\Uuid\Uuid;

final class RamseyUuidGenerator implements UuidGenerator
{
    public function uuid4(): string
    {
        return Uuid::uuid4()->toString();
    }
}
