<?php

declare(strict_types=1);

namespace Domain\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Domain\EntityRemoverInterface;

final class DoctrineEntityRemover implements EntityRemoverInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function remove(object $entity): void
    {
        $this->entityManager->remove($entity);
    }
}
