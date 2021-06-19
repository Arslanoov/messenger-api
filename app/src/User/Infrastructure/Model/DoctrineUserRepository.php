<?php

declare(strict_types=1);

namespace User\Infrastructure\Model;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use User\Exception\NotFound;
use User\Model\Id;
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

    public function findById(Id $id): ?User
    {
        /** @var ?User $user */
        $user = $this->repository->findOneBy([
            'uuid' => $id
        ]);

        return $user;
    }

    public function getById(Id $id): User
    {
        if (!$user = $this->findById($id)) {
            throw new NotFound();
        }

        return $user;
    }

    public function findByUsername(Username $username): ?User
    {
        /** @var ?User $user */
        $user = $this->repository->findOneBy([
            'username' => $username
        ]);

        return $user;
    }

    public function getByUsername(Username $username): User
    {
        if (!$user = $this->findByUsername($username)) {
            throw new NotFound();
        }

        return $user;
    }

    public function hasByUsername(Username $username): bool
    {
        return (bool) $this->repository->findOneBy([
            'username' => $username
        ]);
    }
}
