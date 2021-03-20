<?php

declare(strict_types=1);

namespace Domain;

interface PersisterInterface
{
    public function persist(object $entity): void;
}
