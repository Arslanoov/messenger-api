<?php

declare(strict_types=1);

namespace App\Http\Response;

interface ResponseFactory
{
    public function json(array $data): mixed;
}
