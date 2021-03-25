<?php

declare(strict_types=1);

namespace Messenger\Test\Unit;

use Domain\Exception\DomainAssertionException;
use Messenger\Model\Author\Id;
use PHPUnit\Framework\TestCase;

/**
 * Class IdTest
 * @package Domain\Model\User\Test\Unit
 * @covers \Messenger\Model\Author\Id
 */
class IdTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = 'id');

        $this->assertEquals($value, $id->getValue());
    }

    public function testValidationErrorNotEmpty(): void
    {
        $this->expectException(DomainAssertionException::class);
        $this->expectExceptionMessage('Author id required');

        new Id($value = '');
    }
}
