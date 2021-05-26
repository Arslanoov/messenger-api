<?php

declare(strict_types=1);

namespace Messenger\UseCase\Dialog\Create;

use Domain\FlusherInterface;
use Domain\PersisterInterface;
use Messenger\Exception\DialogAlreadyExists;
use Messenger\Model\Author\AuthorRepositoryInterface;
use Messenger\Model\Author\Id as AuthorId;
use Messenger\Model\Dialog\Dialog;
use Messenger\Model\Dialog\DialogRepositoryInterface;
use Messenger\Model\Dialog\Id as DialogId;

final class Handler
{
    private DialogRepositoryInterface $dialogs;
    private AuthorRepositoryInterface $authors;
    private PersisterInterface $persister;
    private FlusherInterface $flusher;

    public function __construct(
        DialogRepositoryInterface $dialogs,
        AuthorRepositoryInterface $authors,
        PersisterInterface $persister,
        FlusherInterface $flusher
    ) {
        $this->dialogs = $dialogs;
        $this->authors = $authors;
        $this->persister = $persister;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $firstAuthor = $this->authors->getById(new AuthorId($command->firstAuthorId));
        $secondAuthor = $this->authors->getById(new AuthorId($command->secondAuthorId));

        if ($this->dialogs->hasDialogByMembers($firstAuthor, $secondAuthor)) {
            throw new DialogAlreadyExists();
        }

        $dialog = Dialog::newWithId(new DialogId($command->dialogId), $firstAuthor, $secondAuthor);

        $this->persister->persist($dialog);

        $this->flusher->flush();
    }
}
