<?php

declare(strict_types=1);

namespace User\UseCase\SignUp\Request;

use Domain\FlusherInterface;
use Domain\PersisterInterface;
use User\Model\User;
use User\Service\HasherInterface;

final class Handler
{
    private HasherInterface $hasher;
    private PersisterInterface $persister;
    private FlusherInterface $flusher;

    public function __construct(HasherInterface $hasher, PersisterInterface $persister, FlusherInterface $flusher)
    {
        $this->hasher = $hasher;
        $this->persister = $persister;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = User::signUp(
            $command->username,
            $this->hasher->hash($command->password)
        );

        $this->persister->persist($user);

        $this->flusher->flush();
    }
}
