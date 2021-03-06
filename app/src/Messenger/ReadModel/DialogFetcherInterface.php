<?php

declare(strict_types=1);

namespace Messenger\ReadModel;

interface DialogFetcherInterface
{
    public function getDialogs(string $authorId): array;

    public function findDialog(string $dialogId, string $authorId): ?array;

    /* TODO: Change to findLatestMessage */
    public function getLatestMessage(string $authorId): ?array;
}
