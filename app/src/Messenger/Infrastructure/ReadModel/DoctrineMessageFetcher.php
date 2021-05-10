<?php

declare(strict_types=1);

namespace Messenger\Infrastructure\ReadModel;

use App\Http\Handler\Messenger\Dialog\Dialogs;
use App\Http\Handler\Messenger\Dialog\Messages;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Knp\Component\Pager\PaginatorInterface;
use Messenger\Model\Dialog\Dialog;
use Messenger\ReadModel\MessageFetcherInterface;

final class DoctrineMessageFetcher implements MessageFetcherInterface
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

    public function getMessages(string $dialogId, int $page = 1): array
    {
        $qb = $this
            ->connection
            ->createQueryBuilder()
            ->select(
                'uuid',
                'author_id',
                'wrote_at',
                'content',
                'edit_status',
                'read_status'
            )
            ->from('messenger_messages')
            ->where('dialog_id = :dialog_id')
            ->orderBy('wrote_at', 'desc')
            ->setParameter(':dialog_id', $dialogId);

        $pagination = $this->paginator->paginate($qb, $page, Messages::PER_PAGE);

        return (array) $pagination->getItems();
    }
}
