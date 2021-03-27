<?php

declare(strict_types=1);

namespace Messenger\ReadModel;

interface DialogFetcherRepository
{
    public function getDialogs(string $authorId, int $page = 1): array;
}
