<?php

declare(strict_types=1);

namespace Messenger\Infrastructure\ReadModel;

use App\Http\Handler\Messenger\Dialog\Dialogs;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\ForwardCompatibility\DriverStatement;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Messenger\Model\Dialog\Dialog;
use Messenger\ReadModel\DialogFetcherInterface;

final class DoctrineDialogFetcher implements DialogFetcherInterface
{
    private Connection $connection;
    private ObjectRepository $repository;

    public function __construct(
        Connection $connection,
        EntityManagerInterface $entityManager,
    ) {
        $this->connection = $connection;
        $this->repository = $entityManager->getRepository(Dialog::class);
    }

    public function getDialogs(string $authorId): array
    {
        $stmt = $this
            ->connection
            ->createQueryBuilder()
            ->select([
                'd.uuid',
                'p.uuid as partner_user_uuid',
                'p.username as partner_user_username',
                'p.avatar_url as partner_user_avatar_url',
                'p.about_me as partner_user_about_me',
                'p.latest_activity as partner_latest_activity',
                'd.messages_count',
                'd.not_read_count'
            ])
            ->from('messenger_dialogs', 'd')
            ->where('first_author_id = :author_id')
            ->orWhere('second_author_id = :author_id')
            ->innerJoin(
                'd',
                'user_users',
                'p',
                '(d.first_author_id = p.uuid AND d.second_author_id = :author_id) OR
                (d.second_author_id = p.uuid AND d.first_author_id = :author_id)'
            )
            ->setParameter(':author_id', $authorId)
            ->execute();

        /* TODO: Remove deprecated */

        /** @var array | null $result */
        $result = $stmt->fetchAllAssociative();

        return $result ?: [];
    }

    public function findDialog(string $dialogId, string $authorId): ?array
    {
        $stmt = $this
            ->connection
            ->createQueryBuilder()
            ->select([
                'd.uuid',
                'p.uuid as partner_user_uuid',
                'p.username as partner_user_username',
                'p.avatar_url as partner_user_avatar_url',
                'p.about_me as partner_user_about_me',
                'p.latest_activity as partner_latest_activity',
                'd.messages_count',
                'd.not_read_count'
            ])
            ->from('messenger_dialogs', 'd')
            ->where('d.uuid = :dialog_id')
            ->innerJoin(
                'd',
                'user_users',
                'p',
                '(d.first_author_id = p.uuid AND d.second_author_id = :author_id) OR
                (d.second_author_id = p.uuid AND d.first_author_id = :author_id)'
            )
            ->setParameter(':dialog_id', $dialogId)
            ->setParameter(':author_id', $authorId)
            ->execute();

        /* TODO: Remove deprecated */

        // @psalm-suppress DeprecatedMethod
        $stmt->setFetchMode(FetchMode::ASSOCIATIVE);

        /** @var array | null $result */
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * @psalm-suppress DeprecatedMethod
     * @param string $dialogId
     * @return array|null
     * @throws Exception
     */
    public function getLatestMessage(string $dialogId): ?array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                'uuid',
                'wrote_at as date',
                'author_id',
                'content',
                'read_status'
            ])
            ->from('messenger_messages')
            ->where('dialog_id = :id')
            ->setParameter(':id', $dialogId)
            ->orderBy('wrote_at', 'desc')
            ->execute();

        /** @var DriverStatement $stmt */
        $stmt->setFetchMode(FetchMode::ASSOCIATIVE);

        /** @var ?array $result */
        $result = $stmt->fetch();

        return $result ?: null;
    }
}
