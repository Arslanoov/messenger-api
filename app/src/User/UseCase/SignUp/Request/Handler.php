<?php

declare(strict_types=1);

namespace User\UseCase\SignUp\Request;

use Domain\FlusherInterface;
use Domain\PersisterInterface;
use User\Exception\ExistsWithThisUsername;
use User\Model\User;
use User\Model\Username;
use User\Model\UserRepositoryInterface;
use User\Service\HasherInterface;

final class Handler
{
    private HasherInterface $hasher;
    private UserRepositoryInterface $users;
    private PersisterInterface $persister;
    private FlusherInterface $flusher;

    public function __construct(
        HasherInterface $hasher,
        UserRepositoryInterface $users,
        PersisterInterface $persister,
        FlusherInterface $flusher
    ) {
        $this->hasher = $hasher;
        $this->users = $users;
        $this->persister = $persister;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        if ($this->users->hasByUsername(new Username($command->username))) {
            throw new ExistsWithThisUsername();
        }

        $user = User::signUpWithId(
            $command->id,
            $command->username,
            $this->hasher->hash($command->password)
        );

        $this->persister->persist($user);

        $this->flusher->flush();
    }
}
