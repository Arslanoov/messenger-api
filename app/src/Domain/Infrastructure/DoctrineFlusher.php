<?php

declare(strict_types=1);

namespace Domain\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Domain\FlusherInterface;

final class DoctrineFlusher implements FlusherInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
