<?php

declare(strict_types=1);

namespace Messenger\Model\Author;

use Messenger\Exception\AuthorNotFound;

interface AuthorRepositoryInterface
{
    /**
     * @param Id $id
     * @return Author
     * @throws AuthorNotFound
     */
    public function getById(Id $id): Author;
}
