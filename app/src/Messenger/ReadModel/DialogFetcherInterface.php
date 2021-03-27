<?php

declare(strict_types=1);

namespace Messenger\ReadModel;

interface DialogFetcherInterface
{
    public function getDialogs(string $authorId, int $page = 1): array;
}
