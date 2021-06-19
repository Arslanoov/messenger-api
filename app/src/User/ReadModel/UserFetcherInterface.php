<?php

declare(strict_types=1);

namespace User\ReadModel;

use User\Infrastructure\ReadModel\User\AuthView;
use User\Model\Id;

interface UserFetcherInterface
{
    public function findForAuthByUsername(string $username): ?AuthView;

    public function searchByUsername(string $username): array;

    public function searchOneByUuid(Id $uuid): ?array;

    public function findAll(int $page = 1): array;
}
