<?php

declare(strict_types=1);

namespace Messenger\Test\Unit\Author;

use DateTimeImmutable;
use Domain\Exception\DomainAssertionException;
use Messenger\Model\Author\Author;
use Messenger\Model\Author\Id;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateTest extends TestCase
{
    public function testNew(): void
    {
        $author = Author::new($uuid = Uuid::uuid4()->toString());

        $this->assertEquals($uuid, $author->getUuid()->getValue());
        $this->assertNotEmpty($author->getCreatedAtDate());
        $this->assertEquals(0, $author->getMessagesCount());
        $this->assertTrue($author->isEqualTo($author));
    }

    public function testConstructor(): void
    {
        $author = new Author(
            $uuid = new Id(Uuid::uuid4()->toString()),
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
            new Id(Uuid::uuid4()->toString()),
            new DateTimeImmutable(),
            -22
        );
    }
}
