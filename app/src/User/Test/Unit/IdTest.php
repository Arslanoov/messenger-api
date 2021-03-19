<?php

declare(strict_types=1);

namespace App\User\Test\Unit;

use Domain\Model\User\Id;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class IdTest
 * @package Domain\Model\User\Test\Unit
 * @covers \Domain\Model\User\Id
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
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User id required');

        new Id($value = '');
    }
}
