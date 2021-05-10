<?php

declare(strict_types=1);

namespace Messenger\UseCase\Dialog\SendMessage;

use Assert\AssertionFailedException;
use Domain\Exception\DomainAssertionException;
use Domain\FlusherInterface;
use Domain\PersisterInterface;
use Messenger\Exception\DialogNotFound;
use Messenger\Model\Author\AuthorRepositoryInterface;
use Messenger\Model\Author\Id as AuthorId;
use Messenger\Model\Dialog\DialogRepositoryInterface;
use Messenger\Model\Dialog\Id as DialogId;
use Messenger\Model\Message\Content;
use Messenger\Model\Message\Id;
use Messenger\Model\Message\Message;

final class Handler
{
    private AuthorRepositoryInterface $authors;
    private DialogRepositoryInterface $dialogs;
    private PersisterInterface $persister;
    private FlusherInterface $flusher;

    public function __construct(
        AuthorRepositoryInterface $authors,
        DialogRepositoryInterface $dialogs,
        PersisterInterface $persister,
        FlusherInterface $flusher
    ) {
        $this->authors = $authors;
        $this->dialogs = $dialogs;
        $this->persister = $persister;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws AssertionFailedException
     * @throws DomainAssertionException
     */
    public function handle(Command $command): void
    {
        $author = $this->authors->getById(new AuthorId($command->authorId));
        $dialog = $this->dialogs->getById(new DialogId($command->dialogId));

        if (!$dialog->hasMember($author)) {
            throw new DialogNotFound();
        }

        $message = Message::send(
            new Id($command->id),
            $dialog,
            $author,
            new Content($command->content)
        );

        $dialog->getMessages()->add($message);

        $this->persister->persist($message);

        $this->flusher->flush();
    }
}
