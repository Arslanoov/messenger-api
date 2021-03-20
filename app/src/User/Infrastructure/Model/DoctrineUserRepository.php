<?php

declare(strict_types=1);

namespace User\Infrastructure\Model;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use User\Model\User;
use User\Model\Username;
use User\Model\UserRepositoryInterface;

final class DoctrineUserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $entityManger;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManger)
    {
        $this->entityManger = $entityManger;
        $this->repository = $this->entityManger->getRepository(User::class);
    }

    public function hasByUsername(Username $username): bool
    {
        return (bool) $this->repository->findBy([
            'username' => $username
        ]);
    }
}
