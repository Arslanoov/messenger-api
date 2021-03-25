<?php

declare(strict_types=1);

namespace Messenger\Test\Unit;

use Messenger\Exception\IncorrectMessagesCount;
use Messenger\Model\Author\Author;
use PHPUnit\Framework\TestCase;

class MessagesCountTest extends TestCase
{
    public function testSuccess(): void
    {
        $author = Author::empty();

        $this->assertEquals(0, $author->getMessagesCount());
        $this->assertFalse($author->hasMessages());

        $author->writeMessage();

        $this->assertEquals(1, $author->getMessagesCount());
        $this->assertTrue($author->hasMessages());

        $author->removeMessage();

        $this->assertEquals(0, $author->getMessagesCount());
        $this->assertFalse($author->hasMessages());
    }

    public function testIncorrectMessagesCount(): void
    {
        $author = Author::empty();

        $this->assertEquals(0, $author->getMessagesCount());
        $this->assertFalse($author->hasMessages());

        $this->expectException(IncorrectMessagesCount::class);
        $this->expectExceptionMessage('Incorrect author messages count');

        $author->removeMessage();
    }
}
