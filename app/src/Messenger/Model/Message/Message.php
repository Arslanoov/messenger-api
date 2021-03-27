<?php

declare(strict_types=1);

namespace Messenger\Model\Message;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Messenger\Model\Author\Author;

/**
 * Class Message
 * @package Messenger\Model\Message
 * @ORM\Entity()
 * @ORM\Table(name="messenger_messages")
 */
class Message
{
    /**
     * @var Id
     * @ORM\Id()
     * @ORM\Column(type="messenger_message_id", length=128)
     */
    private Id $uuid;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="wrote_at")
     */
    private DateTimeImmutable $wroteAt;
    /**
     * @var Author
     * @ORM\ManyToOne(targetEntity="Messenger\Model\Author\Author", inversedBy="messages")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="uuid", nullable=false)
     */
    private Author $author;
    /**
     * @var Content
     * @ORM\Column(type="messenger_message_content", length=1024)
     */
    private Content $content;
    /**
     * @var EditStatus
     * @ORM\Column(type="messenger_message_edit_status", length=16)
     */
    private EditStatus $editStatus;
    /**
     * @var ReadStatus
     * @ORM\Column(type="messenger_message_read_status", length=16)
     */
    private ReadStatus $readStatus;

    public function __construct(
        Id $uuid,
        DateTimeImmutable $wroteAt,
        Author $author,
        Content $content,
        EditStatus $editStatus,
        ReadStatus $readStatus
    ) {
        $this->uuid = $uuid;
        $this->wroteAt = $wroteAt;
        $this->author = $author;
        $this->content = $content;
        $this->editStatus = $editStatus;
        $this->readStatus = $readStatus;
    }

    public static function send(Author $author, Content $content): self
    {
        return new self(
            Id::generate(),
            new DateTimeImmutable(),
            $author,
            $content,
            EditStatus::notEdited(),
            ReadStatus::notRead()
        );
    }

    public function edit(Content $newContent): void
    {
        $this->content = $newContent;
        $this->editStatus = EditStatus::edited();
    }

    public function isEdited(): bool
    {
        return $this->getEditStatus()->isEdited();
    }

    public function read(): void
    {
        $this->readStatus = ReadStatus::read();
    }

    public function isRead(): bool
    {
        return $this->readStatus->isRead();
    }

    public function isWroteBy(Author $author): bool
    {
        return $this->getAuthor()->isEqualTo($author);
    }

    public function getId(): Id
    {
        return $this->uuid;
    }

    public function getWroteAt(): DateTimeImmutable
    {
        return $this->wroteAt;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getContent(): Content
    {
        return $this->content;
    }

    public function getEditStatus(): EditStatus
    {
        return $this->editStatus;
    }
}
