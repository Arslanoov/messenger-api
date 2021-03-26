<?php

declare(strict_types=1);

namespace Messenger\Test\Unit\Author;

use DateTimeImmutable;
use Domain\Exception\DomainAssertionException;
use Messenger\Model\Author\Author;
use Messenger\Model\Author\Id;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testNew(): void
    {
        $author = Author::new($uuid = "someId");

        $this->assertEquals($uuid, $author->getUuid()->getValue());
        $this->assertNotEmpty($author->getCreatedAtDate());
        $this->assertEquals(0, $author->getMessagesCount());
    }

    public function testConstructor(): void
    {
        $author = new Author(
            $uuid = new Id("someId"),
            $date = new DateTimeImmutable(),
            $messagesCounter = 25
        );

        $this->assertEquals($uuid, $author->getUuid());
        $this->assertEquals($date, $author->getCreatedAtDate());
        $this->assertEquals($messagesCounter, $author->getMessagesCount());
    }

    public function testValidation(): void
    {
        $this->expectException(DomainAssertionException::class);
        $this->expectExceptionMessage('Incorrect messages count');

        new Author(
            new Id("someId"),
            new DateTimeImmutable(),
            -22
        );
    }
}
