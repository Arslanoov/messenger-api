<?php

declare(strict_types=1);

namespace Messenger\Model\Dialog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
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
     * @var ArrayCollection|Message[]
     * @ORM\OneToMany(
     *     targetEntity="Messenger\Model\Message\Message",
     *     mappedBy="dialog", orphanRemoval=true, cascade={"all"}
     * )
     * @ORM\OrderBy({"name" = "DESC"})
     */
    private ArrayCollection | array $messages;
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

    public function addMessage(Message $message): void
    {
        $this->messages->add($message);
        $this->notReadCount++;
        $this->messagesCount++;
    }

    public function readAllMessages(): void
    {
        foreach ($this->messages as $message) {
            $message->read();
        }
        $this->notReadCount = 0;
    }

    public function removeMessage(Message $message): void
    {
        foreach ($this->messages as $listMessage) {
            if ($listMessage->getId()->isEqualTo($message->getId())) {
                if ($message->isNotRead()) {
                    $this->notReadCount--;
                }
                $this->messagesCount--;
                $this->messages->removeElement($message);
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

    public function getMessages(): ArrayCollection | array
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
