<?php

declare(strict_types=1);

namespace Messenger\Model\Author;

use Assert\AssertionFailedException;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Domain\Exception\DomainAssertionException;
use Domain\Validation\DomainLogicAssertion;
use Messenger\Exception\IncorrectMessagesCount;

/**
 * Class Author
 * @package Messenger\Model\Author
 * @ORM\Entity()
 * @ORM\Table(name="messenger_authors")
 */
class Author
{
    /**
     * @var Id
     * @ORM\Id()
     * @ORM\Column(type="messenger_author_id", length=128)
     */
    private Id $uuid;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $createdAt;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private int $messagesCount;

    /**
     * Author constructor.
     * @param Id $uuid
     * @param DateTimeImmutable $createdAt
     * @param int $messagesCount
     * @throws AssertionFailedException
     * @THROWS DomainAssertionException
     */
    public function __construct(Id $uuid, DateTimeImmutable $createdAt, int $messagesCount)
    {
        $this->uuid = $uuid;
        $this->createdAt = $createdAt;
        DomainLogicAssertion::greaterOrEqualThan($messagesCount, 0, 'Incorrect messages count');
        $this->messagesCount = $messagesCount;
    }

    /**
     * @param string $id
     * @return self
     * @throws AssertionFailedException
     * @throws DomainAssertionException
     */
    public static function new(string $id): self
    {
        return new self(new Id($id), new DateTimeImmutable(), 0);
    }

    /**
     * @return self
     * @throws AssertionFailedException
     * @throws DomainAssertionException
     */
    public static function empty(): self
    {
        return self::new(Id::generate()->getValue());
    }

    public function getUuid(): Id
    {
        return $this->uuid;
    }

    public function getCreatedAtDate(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getMessagesCount(): int
    {
        return $this->messagesCount;
    }

    public function hasMessages(): bool
    {
        return $this->messagesCount > 0;
    }

    public function writeMessage(): void
    {
        $this->messagesCount++;
    }

    public function removeMessage(): void
    {
        if ($this->messagesCount === 0) {
            throw new IncorrectMessagesCount();
        }
        $this->messagesCount--;
    }

    public function isEqualTo(Author $author): bool
    {
        return $this->getUuid()->getValue() === $author->getUuid()->getValue();
    }
}
