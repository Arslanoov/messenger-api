<?php

declare(strict_types=1);

namespace Messenger\UseCase\Author\CreateFromUser;

use Domain\FlusherInterface;
use Domain\PersisterInterface;
use Messenger\Model\Author\Author;

final class Handler
{
    private PersisterInterface $persister;
    private FlusherInterface $flusher;

    public function __construct(
        PersisterInterface $persister,
        FlusherInterface $flusher
    ) {
        $this->persister = $persister;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $author = Author::new($command->uuid);

        $this->persister->persist($author);

        $this->flusher->flush();
    }
}
