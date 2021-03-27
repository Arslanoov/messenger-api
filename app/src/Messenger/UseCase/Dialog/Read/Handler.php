<?php

declare(strict_types=1);

namespace Messenger\UseCase\Dialog\Read;

use Domain\FlusherInterface;
use Messenger\Model\Author\AuthorRepositoryInterface;
use Messenger\Model\Author\Id as AuthorId;
use Messenger\Model\Dialog\DialogRepositoryInterface;
use Messenger\Model\Dialog\Id as DialogId;

final class Handler
{
    private AuthorRepositoryInterface $authors;
    private DialogRepositoryInterface $dialogs;
    private FlusherInterface $flusher;

    public function __construct(
        AuthorRepositoryInterface $authors,
        DialogRepositoryInterface $dialogs,
        FlusherInterface $flusher
    ) {
        $this->authors = $authors;
        $this->dialogs = $dialogs;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $author = $this->authors->getById(new AuthorId($command->authorId));
        $dialog = $this->dialogs->getById(new DialogId($command->dialogId));

        if ($dialog->isNotReadByMember($author)) {
            $dialog->readAllMessages();
            $this->flusher->flush();
        }
    }
}
