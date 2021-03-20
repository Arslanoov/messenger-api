<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Response;

use App\Http\Response\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

final class SymfonyResponseFactory implements ResponseFactory
{
    public function json(array $data, int $code = 200): JsonResponse
    {
        return new JsonResponse($data, $code);
    }
}
