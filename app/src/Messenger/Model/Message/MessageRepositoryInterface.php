<?php

declare(strict_types=1);

namespace Messenger\Model\Message;

use Messenger\Exception\MessageNotFound;

interface MessageRepositoryInterface
{
    /**
     * @param Id $id
     * @return Message
     * @throws MessageNotFound
     */
    public function getById(Id $id): Message;
}
