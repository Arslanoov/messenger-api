<?php

declare(strict_types=1);

namespace User\UseCase\SignUp\Request;

use Domain\FlusherInterface;
use Domain\PersisterInterface;
use User\Factory\UserFactoryInterface;

final class Handler
{
    private UserFactoryInterface $factory;
    private PersisterInterface $persister;
    private FlusherInterface $flusher;

    public function __construct(UserFactoryInterface $factory, PersisterInterface $persister, FlusherInterface $flusher)
    {
        $this->factory = $factory;
        $this->persister = $persister;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->factory->simpleRegister($command->username, $command->password);

        $this->persister->persist($user);

        $this->flusher->flush();
    }
}
