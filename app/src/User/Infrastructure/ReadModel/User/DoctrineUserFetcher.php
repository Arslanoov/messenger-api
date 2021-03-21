<?php

declare(strict_types=1);

namespace User\Infrastructure\ReadModel\User;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use User\Model\User;
use User\ReadModel\UserFetcherInterface;

final class DoctrineUserFetcher implements UserFetcherInterface
{
    private Connection $connection;
    private ObjectRepository $repository;

    public function __construct(Connection $connection, EntityManagerInterface $entityManger)
    {
        $this->connection = $connection;
        $this->repository = $entityManger->getRepository(User::class);
    }

    public function findForAuthByUsername(string $username): ?AuthView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                'id',
                'username',
                'password',
                'status'
            ])
            ->from('user_users')
            ->where('username = :username')
            ->setParameter(':username', $username);

        $result = $stmt->getFirstResult();

        return $result ?: null;
    }
}
