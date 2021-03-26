<?php

declare(strict_types=1);

namespace Messenger\UseCase\Message\Edit;

use Domain\FlusherInterface;
use Messenger\Exception\MessageNotFound;
use Messenger\Model\Author\AuthorRepositoryInterface;
use Messenger\Model\Author\Id as AuthorId;
use Messenger\Model\Message\Content;
use Messenger\Model\Message\Id as MessageId;
use Messenger\Model\Message\MessageRepositoryInterface;

final class Handler
{
    private AuthorRepositoryInterface $authors;
    private MessageRepositoryInterface $messages;
    private FlusherInterface $flusher;

    public function __construct(
        AuthorRepositoryInterface $authors,
        MessageRepositoryInterface $messages,
        FlusherInterface $flusher
    ) {
        $this->authors = $authors;
        $this->messages = $messages;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $author = $this->authors->getById(new AuthorId($command->authorId));
        $message = $this->messages->getById(new MessageId($command->messageId));
        if (!$message->isWroteBy($author)) {
            throw new MessageNotFound();
        }

        $message->edit(new Content($command->newContent));

        $this->flusher->flush();
    }
}
