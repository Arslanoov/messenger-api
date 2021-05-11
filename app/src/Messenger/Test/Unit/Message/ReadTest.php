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
class ReadTest extends TestCase
{
    public function testSend(): void
    {
        $message = Message::send(
            Id::generate(),
            Dialog::empty(),
            $author = Author::empty(),
            $content = new Content('Some very important message')
        );

        $this->assertFalse($message->isRead());

        $message->read();

        $this->assertTrue($message->isRead());
    }
}
