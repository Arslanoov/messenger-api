<?php

declare(strict_types=1);

namespace User\UseCase\MakeAdmin;

use Domain\FlusherInterface;
use User\Model\Username;
use User\Model\UserRepositoryInterface;

final class Handler
{
    private UserRepositoryInterface $users;
    private FlusherInterface $flusher;

    public function __construct(UserRepositoryInterface $users, FlusherInterface $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByUsername(new Username($command->username));

        $user->makeAdmin();

        $this->flusher->flush();
    }
}
