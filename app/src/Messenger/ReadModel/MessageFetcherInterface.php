<?php

declare(strict_types=1);

namespace Messenger\ReadModel;

interface MessageFetcherInterface
{
    public function getMessages(string $dialogId, int $page = 1): array;
}
