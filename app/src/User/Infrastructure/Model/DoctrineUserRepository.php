<?php

declare(strict_types=1);

namespace User\Infrastructure\Model;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use User\Model\User;
use User\Model\Username;
use User\Model\UserRepositoryInterface;

final class DoctrineUserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $entityManger;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManger)
    {
        $this->entityManger = $entityManger;
        /** @var EntityRepository $repository */
        $repository = $this->entityManger->getRepository(User::class);
        $this->repository = $repository;
    }

    public function hasByUsername(Username $username): bool
    {
        return (bool) $this->repository->findBy([
            'username' => $username
        ]);
    }
}
