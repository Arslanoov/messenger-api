<?php

declare(strict_types=1);

namespace Messenger\UseCase\Dialog\Remove;

use Domain\EntityRemoverInterface;
use Domain\FlusherInterface;
use Messenger\Model\Author\AuthorRepositoryInterface;
use Messenger\Model\Author\Id as AuthorId;
use Messenger\Model\Dialog\DialogRepositoryInterface;
use Messenger\Model\Dialog\Id as DialogId;

final class Handler
{
    private AuthorRepositoryInterface $authors;
    private DialogRepositoryInterface $dialogs;
    private EntityRemoverInterface $remover;
    private FlusherInterface $flusher;

    public function __construct(
        AuthorRepositoryInterface $authors,
        DialogRepositoryInterface $dialogs,
        EntityRemoverInterface $remover,
        FlusherInterface $flusher
    ) {
        $this->authors = $authors;
        $this->dialogs = $dialogs;
        $this->remover = $remover;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $author = $this->authors->getById(new AuthorId($command->authorId));
        $dialog = $this->dialogs->getById(new DialogId($command->dialogId));

        if ($dialog->hasMember($author)) {
            $this->remover->remove($dialog);
            $this->flusher->flush();
        }
    }
}
