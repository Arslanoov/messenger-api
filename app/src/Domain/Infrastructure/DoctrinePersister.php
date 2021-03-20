<?php

declare(strict_types=1);

namespace Domain\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Domain\PersisterInterface;

final class DoctrinePersister implements PersisterInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persist(object $entity): void
    {
        $this->entityManager->persist($entity);
    }
}
