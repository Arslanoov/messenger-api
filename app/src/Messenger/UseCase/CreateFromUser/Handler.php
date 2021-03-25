<?php

declare(strict_types=1);

namespace Messenger\UseCase\CreateFromUser;

use Domain\FlusherInterface;
use Domain\PersisterInterface;
use Messenger\Factory\AuthorFactoryInterface;

final class Handler
{
    private AuthorFactoryInterface $factory;
    private PersisterInterface $persister;
    private FlusherInterface $flusher;

    public function __construct(
        AuthorFactoryInterface $factory,
        PersisterInterface $persister,
        FlusherInterface $flusher
    ) {
        $this->factory = $factory;
        $this->persister = $persister;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $author = $this->factory->fromUser($command->user);

        $this->persister->persist($author);

        $this->flusher->flush();
    }
}
