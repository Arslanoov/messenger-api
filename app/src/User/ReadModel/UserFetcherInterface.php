<?php

declare(strict_types=1);

namespace User\ReadModel;

use User\Infrastructure\ReadModel\User\AuthView;

interface UserFetcherInterface
{
    public function findForAuthByUsername(string $username): ?AuthView;

    public function searchByUsername(string $username): array;
}
