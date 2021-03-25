<?php

declare(strict_types=1);

namespace App\Service;

interface ValidatorInterface
{
    /**
     * @param array<object> $objects
     * @return void
     */
    public function validateObjects(array $objects): void;
}
