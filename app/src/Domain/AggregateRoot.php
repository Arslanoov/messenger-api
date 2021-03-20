<?php

declare(strict_types=1);

namespace Domain;

interface AggregateRoot
{
    /**
     * @return array<object>
     */
    public function releaseEvents(): array;
}
