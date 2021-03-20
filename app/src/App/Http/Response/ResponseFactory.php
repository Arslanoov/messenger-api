<?php

declare(strict_types=1);

namespace App\Http\Response;

interface ResponseFactory
{
    /**
     * @param array<string, string | int | array> $data
     * @param int $code
     * @return mixed
     */
    public function json(array $data, int $code = 200): mixed;
}
