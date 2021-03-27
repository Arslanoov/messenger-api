<?php

declare(strict_types=1);

namespace User\Test\Unit;

use Domain\Exception\DomainAssertionException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use User\Model\Id;

/**
 * Class IdTest
 * @package Domain\Model\User\Test\Unit
 * @covers \User\Model\Id
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
        $this->expectExceptionMessage('User id must be uuid');

        new Id('not uuid');
    }

    public function testValidationErrorNotEmpty(): void
    {
        $this->expectException(DomainAssertionException::class);
        $this->expectExceptionMessage('User id required');

        new Id('');
    }
}
