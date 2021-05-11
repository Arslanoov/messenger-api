<?php

declare(strict_types=1);

namespace Messenger\Test\Unit\Dialog;

use Messenger\Model\Author\Author;
use Messenger\Model\Dialog\Dialog;
use Messenger\Model\Message\Content;
use Messenger\Model\Message\Id;
use Messenger\Model\Message\Message;
use PHPUnit\Framework\TestCase;

/**
 * Class MessageTest
 * @package Messenger\Test\Unit\Dialog
 * @covers \Messenger\Model\Dialog\Dialog
 */
class MessageTest extends TestCase
{
    public function testSuccess(): void
    {
        $dialog = Dialog::new(
            Author::empty(),
            Author::empty()
        );

        $this->assertFalse($dialog->hasNotReadMessages());
        $this->assertEquals(0, $dialog->getMessagesCount());
        $this->assertEquals(0, $dialog->getNotReadMessagesCount());

        $dialog->addMessage(
            $message = Message::send(
                Id::generate(),
                $dialog,
                Author::empty(),
                new Content('Content')
            )
        );

        $this->assertEquals(1, $dialog->getMessagesCount());
        $this->assertEquals(1, $dialog->getNotReadMessagesCount());
        $this->assertTrue($dialog->hasNotReadMessages());

        $dialog->readAllMessages();

        $this->assertFalse($dialog->hasNotReadMessages());
        $this->assertEquals(1, $dialog->getMessagesCount());
        $this->assertEquals(0, $dialog->getNotReadMessagesCount());

        $dialog->removeMessage($message);

        $this->assertEquals(0, $dialog->getMessagesCount());
        $this->assertEquals(0, $dialog->getNotReadMessagesCount());
    }
}
