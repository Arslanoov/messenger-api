<?php

declare(strict_types=1);

namespace Messenger\Infrastructure\ReadModel;

use App\Http\Handler\Messenger\Dialog\Dialogs;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Knp\Component\Pager\PaginatorInterface;
use Messenger\Model\Dialog\Dialog;
use Messenger\ReadModel\DialogFetcherRepository;

final class DoctrineDialogFetcher implements DialogFetcherRepository
{
    private Connection $connection;
    private PaginatorInterface $paginator;
    private ObjectRepository $repository;

    public function __construct(
        Connection $connection,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator
    ) {
        $this->connection = $connection;
        $this->paginator = $paginator;
        $this->repository = $entityManager->getRepository(Dialog::class);
    }

    public function getDialogs(string $authorId, int $page = 1): array
    {
        $qb = $this
            ->connection
            ->createQueryBuilder()
            ->select(
                'uuid',
                'first_author_id',
                'second_author_id',
                'messages_count',
                'not_read_count'
            )
            ->from('messenger_dialogs')
            ->where('first_author_id = :author_id')
            ->orWhere('second_author_id = :author_id')
            ->setParameter(':author_id', $authorId)
            ->orderBy('not_read_count', 'desc');

        // TODO: Filter by date?

        $pagination = $this->paginator->paginate($qb, $page, Dialogs::PER_PAGE);

        return (array) $pagination->getItems();
    }
}
