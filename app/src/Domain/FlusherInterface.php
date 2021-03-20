<?php

declare(strict_types=1);

namespace Domain;

interface FlusherInterface
{
    public function flush(): void;
}
