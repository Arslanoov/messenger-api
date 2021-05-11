<?php

declare(strict_types=1);

namespace Messenger\Model\Dialog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Messenger\Exception\DialogNotFound;
use Messenger\Model\Author\Author;
use Messenger\Model\Message\Message;

/**
 * Class Dialog
 * @package Messenger\Model\Dialog
 * @ORM\Entity()
 * @ORM\Table(name="messenger_dialogs")
 */
final class Dialog
{
    /**
     * @var Id
     * @ORM\Id()
     * @ORM\Column(type="messenger_dialog_id", length=128)
     */
    private Id $uuid;
    /**
     * @var Author
     * @ORM\ManyToOne(targetEntity="Messenger\Model\Author\Author", inversedBy="dialogs")
     * @ORM\JoinColumn(name="first_author_id", referencedColumnName="uuid", nullable=false)
     */
    private Author $firstAuthor;
    /**
     * @var Author
     * @ORM\ManyToOne(targetEntity="Messenger\Model\Author\Author", inversedBy="dialogs")
     * @ORM\JoinColumn(name="second_author_id", referencedColumnName="uuid", nullable=false)
     */
    private Author $secondAuthor;
    /**
     * @var Collection|Message[]
     * @ORM\OneToMany(
     *     targetEntity="Messenger\Model\Message\Message",
     *     mappedBy="dialog", orphanRemoval=true, cascade={"all"}
     * )
     */
    private Collection | array $messages;
    /**
     * @var int
     * @ORM\Column(type="integer", name="messages_count")
     */
    private int $messagesCount;
    /**
     * @var int
     * @ORM\Column(type="integer", name="not_read_count")
     */
    private int $notReadCount;

    public function __construct(
        Id $uuid,
        Author $firstAuthor,
        Author $secondAuthor,
        int $messagesCount,
        int $notReadCount
    ) {
        $this->uuid = $uuid;
        $this->firstAuthor = $firstAuthor;
        $this->secondAuthor = $secondAuthor;
        $this->messages = new ArrayCollection();
        $this->messagesCount = $messagesCount;
        $this->notReadCount = $notReadCount;
    }

    public static function new(Author $firstAuthor, Author $secondAuthor): self
    {
        return new self(
            Id::generate(),
            $firstAuthor,
            $secondAuthor,
            0,
            0
        );
    }

    public static function empty(): self
    {
        return new self(
            Id::generate(),
            Author::empty(),
            Author::empty(),
            0,
            0
        );
    }

    public function hasMember(Author $author): bool
    {
        return
            $this->getFirstAuthor()->getUuid()->isEqualTo($author->getUuid()) ||
            $this->getSecondAuthor()->getUuid()->isEqualTo($author->getUuid());
    }

    public function addMessage(Message $message): void
    {
        /** @var Collection $messages */
        $messages = $this->messages;
        $messages->add($message);
        $this->notReadCount++;
        $this->messagesCount++;
    }

    public function readAllMessages(): void
    {
        /** @var Message[] $messages */
        $messages = $this->messages;
        foreach ($messages as $message) {
            $message->read();
        }
        $this->notReadCount = 0;
    }

    public function removeMessage(Message $message): void
    {
        // TODO: Fix
        /** @var Message[] $messages */
        $messages = $this->messages;
        /** @var Collection $messagesCollection */
        $messagesCollection = $this->messages;
        foreach ($messages as $listMessage) {
            if ($listMessage->getId()->isEqualTo($message->getId())) {
                if ($message->isNotRead()) {
                    $this->notReadCount--;
                }
                $this->messagesCount--;
                $messagesCollection->removeElement($message);
                return;
            }
        }
    }

    public function hasNotReadMessages(): bool
    {
        return $this->notReadCount > 0;
    }

    public function hasMessages(): bool
    {
        return $this->messagesCount > 0;
    }

    public function isNotReadByMember(Author $author): bool
    {
        if (!$this->hasMember($author)) {
            throw new DialogNotFound();
        }

        if ($latestMessage = $this->getLatestMessage()) {
            return $latestMessage->getAuthor()->getUuid()->isNotEqualTo($author->getUuid());
        }

        return false;
    }

    public function getLatestMessage(): ?Message
    {
        /** @var Collection $messages */
        $messages = $this->messages;
        /** @var ?Message $message */
        $message = $messages->first();
        return $message ?: null;
    }

    public function getUuid(): Id
    {
        return $this->uuid;
    }

    public function getFirstAuthor(): Author
    {
        return $this->firstAuthor;
    }

    public function getSecondAuthor(): Author
    {
        return $this->secondAuthor;
    }

    public function getMessages(): Collection | array
    {
        return $this->messages;
    }

    public function getMessagesCount(): int
    {
        return $this->messagesCount;
    }

    public function getNotReadMessagesCount(): int
    {
        return $this->notReadCount;
    }
}
