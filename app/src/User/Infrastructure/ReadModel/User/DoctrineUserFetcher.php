<?php

declare(strict_types=1);

namespace User\Infrastructure\ReadModel\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\FetchMode;
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

    /**
     * @param string $username
     * @return AuthView|null
     * @throws Exception
     * @psalm-suppress DeprecatedMethod
     */
    public function findForAuthByUsername(string $username): ?AuthView
    {
        /** @var ResultStatement $stmt */
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                'uuid',
                'username',
                'hash',
                'status'
            ])
            ->from('user_users')
            ->where('username = :username')
            ->setParameter(':username', $username)
            ->execute();

        /* TODO: Remove deprecated */

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, AuthView::class);

        /** @var AuthView | null $result */
        $result = $stmt->fetch();

        return $result;
    }
}
