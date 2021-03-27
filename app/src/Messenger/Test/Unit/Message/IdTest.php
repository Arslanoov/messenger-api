<?php

declare(strict_types=1);

namespace Messenger\Test\Unit\Message;

use Domain\Exception\DomainAssertionException;
use Messenger\Model\Message\Id;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Class IdTest
 * @package Domain\Model\User\Test\Unit
 * @covers \Messenger\Model\Message\Id
 */
class IdTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = Uuid::uuid4()->toString());

        $this->assertEquals($value, $id->getValue());
        $this->assertTrue($id->isEqualTo($id));
    }

    public function testNotUuid(): void
    {
        $this->expectException(DomainAssertionException::class);
        $this->expectExceptionMessage('Message id must be uuid');

        new Id('not uuid');
    }

    public function testValidationErrorNotEmpty(): void
    {
        $this->expectException(DomainAssertionException::class);
        $this->expectExceptionMessage('Message id required');

        new Id('');
    }
}
