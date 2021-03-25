<?php

declare(strict_types=1);

namespace Messenger\Factory;

use Messenger\Model\Author\Author;
use User\Model\User;

interface AuthorFactoryInterface
{
    public function fromUser(User $user): Author;
}
