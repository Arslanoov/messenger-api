<?php

declare(strict_types=1);

namespace User\Infrastructure\ReadModel\User;

use App\Http\Handler\Admin\Users\Index;
use App\Http\Handler\Messenger\Dialog\Dialogs;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Knp\Component\Pager\PaginatorInterface;
use User\Model\Id;
use User\Model\User;
use User\ReadModel\UserFetcherInterface;

final class DoctrineUserFetcher implements UserFetcherInterface
{
    private Connection $connection;
    private PaginatorInterface $paginator;
    private ObjectRepository $repository;

    public function __construct(
        Connection $connection,
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManger
    ) {
        $this->connection = $connection;
        $this->paginator = $paginator;
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
                'status',
                'role'
            ])
            ->from('user_users')
            ->where('username = :username')
            ->setParameter(':username', $username)
            ->execute();

        /* TODO: Remove deprecated */

        // @psalm-suppress DeprecatedMethod
        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, AuthView::class);

        /** @var AuthView | null $result */
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * @param string $username
     * @return array
     * @throws Exception
     * @psalm-suppress DeprecatedMethod
     */
    public function searchByUsername(string $username): array
    {
        /** @var ResultStatement $stmt */
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                'uuid',
                'username'
            ])
            ->from('user_users')
            ->where('username LIKE :username')
            ->setParameter('username', '%' . $username . '%')
            ->execute();

        // @psalm-suppress DeprecatedMethod
        $stmt->setFetchMode(FetchMode::ASSOCIATIVE);

        /** @var array $results */
        $results = $stmt->fetchAll();

        return $results ?: [];
    }

    /**
     * @param Id $uuid
     * @return array | null
     * @throws Exception
     * @psalm-suppress DeprecatedMethod
     */
    public function searchOneByUuid(Id $uuid): ?array
    {
        /** @var ResultStatement $stmt */
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                'uuid',
                'username',
                'avatar_url as avatar',
                'status'
            ])
            ->from('user_users')
            ->where('uuid = :uuid')
            ->setParameter('uuid', $uuid->getValue())
            ->execute();

        // @psalm-suppress DeprecatedMethod
        $stmt->setFetchMode(FetchMode::ASSOCIATIVE);

        /** @var array $result */
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * @param int $page
     * @return array
     */
    public function findAll(int $page = 1): array
    {
        /** @var ResultStatement $stmt */
        $qb = $this->connection->createQueryBuilder()
            ->select([
                'uuid',
                'username',
                'hash',
                'status',
                'role'
            ])
            ->from('user_users');

        $pagination = $this->paginator->paginate($qb, $page, Index::PER_PAGE);

        return [
            'items' => (array) $pagination->getItems(),
            'totalCount' =>$pagination->getTotalItemCount()
        ];
    }
}
