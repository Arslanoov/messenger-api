<?php

declare(strict_types=1);

namespace User\Test\Unit;

use Domain\Exception\DomainAssertionException;
use PHPUnit\Framework\TestCase;
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
        $id = new Id($value = 'id');

        $this->assertEquals($value, $id->getValue());
    }

    public function testValidationErrorNotEmpty(): void
    {
        $this->expectException(DomainAssertionException::class);
        $this->expectExceptionMessage('User id required');

        new Id($value = '');
    }
}
