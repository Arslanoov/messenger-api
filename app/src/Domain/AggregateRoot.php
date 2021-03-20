<?php

declare(strict_types=1);

namespace Domain;

interface AggregateRoot
{
    public function releaseEvents(): void;
}