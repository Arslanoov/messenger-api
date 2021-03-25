<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Exception\Service\TransactionFailed;
use App\Service\TransactionInterface;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineTransactionService implements TransactionInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function begin(): void
    {
        $this->entityManager->getConnection()->beginTransaction();
    }

    /**
     * @throws TransactionFailed
     */
    public function commit(): void
    {
        try {
            $this->entityManager->getConnection()->commit();
        } catch (ConnectionException) {
            throw new TransactionFailed();
        }
    }

    /**
     * @throws TransactionFailed
     */
    public function rollback(): void
    {
        try {
            $this->entityManager->getConnection()->rollback();
        } catch (ConnectionException) {
            throw new TransactionFailed();
        }
    }
}
