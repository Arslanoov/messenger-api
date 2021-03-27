<?php

declare(strict_types=1);

namespace Messenger\Model\Dialog;

use Messenger\Exception\DialogNotFound;
use Messenger\Model\Author\Author;

interface DialogRepositoryInterface
{
    /**
     * @param Id $id
     * @return Dialog
     * @throws DialogNotFound
     */
    public function getById(Id $id): Dialog;

    public function hasDialogByMembers(Author $firstMember, Author $secondMember): bool;
}
