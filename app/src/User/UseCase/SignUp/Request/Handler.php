<?php

declare(strict_types=1);

namespace App\User\UseCase\SignUp\Request;

use App\User\Factory\UserFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/*
 * TODO: WIP
 * */
class Handler
{
    private EntityManagerInterface $em;
    private UserFactoryInterface $factory;

    public function handle(Command $command): void
    {
        $user = $this->factory->simpleRegister($command->username, $command->password);

        $this->em->persist($user);

        $this->flusher->flush();
    }
}
