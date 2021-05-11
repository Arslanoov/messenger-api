<?php

declare(strict_types=1);

namespace Messenger\Test\Unit\Message;

use Messenger\Model\Author\Author;
use Messenger\Model\Dialog\Dialog;
use Messenger\Model\Message\Content;
use Messenger\Model\Message\Id;
use Messenger\Model\Message\Message;
use PHPUnit\Framework\TestCase;

/**
 * Class SendTest
 * @package Messenger\Test\Unit\Message
 * @covers \Messenger\Model\Message\Message
 */
class SendTest extends TestCase
{
    public function testSend(): void
    {
        $message = Message::send(
            $uuid = Id::generate(),
            $dialog = Dialog::empty(),
            $author = Author::empty(),
            $content = new Content('Some very important message')
        );

        $this->assertEquals($uuid, $message->getId());
        $this->assertEquals($author, $message->getAuthor());
        $this->assertEquals($dialog, $message->getDialog());
        $this->assertTrue($message->isFromDialog($dialog));
        $this->assertNotEmpty($message->getWroteAt());
        $this->assertEquals($content, $message->getContent());
        $this->assertFalse($message->isEdited());
        $this->assertFalse($message->isRead());
    }
}
