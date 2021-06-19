<?php

declare(strict_types=1);

namespace User\UseCase\Remove;

use Domain\EntityRemoverInterface;
use Domain\FlusherInterface;
use User\Model\Username;
use User\Model\UserRepositoryInterface;

final class Handler
{
    private UserRepositoryInterface $users;
    private EntityRemoverInterface $remover;
    private FlusherInterface $flusher;

    public function __construct(
        UserRepositoryInterface $users,
        EntityRemoverInterface $remover,
        FlusherInterface $flusher
    ) {
        $this->users = $users;
        $this->remover = $remover;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByUsername(new Username($command->username));

        $this->remover->remove($user);

        $this->flusher->flush();
    }
}
