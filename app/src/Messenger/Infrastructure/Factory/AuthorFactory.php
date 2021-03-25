<?php

declare(strict_types=1);

namespace Messenger\Infrastructure\Factory;

use Assert\AssertionFailedException;
use Domain\Exception\DomainAssertionException;
use Messenger\Factory\AuthorFactoryInterface;
use Messenger\Model\Author\Author;
use Messenger\Model\Author\Id;
use User\Model\User;

final class AuthorFactory implements AuthorFactoryInterface
{
    /**
     * @param User $user
     * @return Author
     * @throws AssertionFailedException
     * @throws DomainAssertionException
     */
    public function fromUser(User $user): Author
    {
        return Author::new(new Id($user->getUuid()->getValue()));
    }
}
