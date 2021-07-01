<?php

declare(strict_types=1);

namespace Messenger\UseCase\Message\Read;

use App\Exception\AccessForbidden;
use Domain\FlusherInterface;
use Messenger\Exception\MessageNotFound;
use Messenger\Model\Author\AuthorRepositoryInterface;
use Messenger\Model\Author\Id as AuthorId;
use Messenger\Model\Dialog\DialogRepositoryInterface;
use Messenger\Model\Dialog\Id as DialogId;
use Messenger\Model\Message\Id as MessageId;
use Messenger\Model\Message\MessageRepositoryInterface;

final class Handler
{
    private AuthorRepositoryInterface $authors;
    private DialogRepositoryInterface $dialogs;
    private MessageRepositoryInterface $messages;
    private FlusherInterface $flusher;

    public function __construct(
        AuthorRepositoryInterface $authors,
        DialogRepositoryInterface $dialogs,
        MessageRepositoryInterface $messages,
        FlusherInterface $flusher
    ) {
        $this->authors = $authors;
        $this->dialogs = $dialogs;
        $this->messages = $messages;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $author = $this->authors->getById(new AuthorId($command->authorId));
        $dialog = $this->dialogs->getById(new DialogId($command->dialogId));
        $message = $this->messages->getById(new MessageId($command->messageId));

        if (!$dialog->hasMember($author)) {
            throw new MessageNotFound();
        }
        if (!$dialog->hasMessage($message)) {
            throw new AccessForbidden();
        }

        $message->read();

        $this->flusher->flush();
    }
}
