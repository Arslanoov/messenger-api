<?php

declare(strict_types=1);

namespace App\Http\Response;

interface ResponseFactory
{
    /**
     * @param array<string, string | int | array> $data
     * @return mixed
     */
    public function json(array $data): mixed;
}
