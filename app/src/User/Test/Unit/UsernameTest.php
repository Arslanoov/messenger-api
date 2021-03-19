<?php

declare(strict_types=1);

namespace App\User\Test\Unit;

use Domain\Model\User\Username;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class UsernameTest
 * @package Domain\Model\User\Test\Unit
 * @covers \Domain\Model\User\Username
 */
class UsernameTest extends TestCase
{
    public function testSuccess(): void
    {
        $username = new Username($value = 'Username');

        $this->assertEquals($value, $username->getValue());
        $this->assertTrue($username->isEqual($username));
    }

    public function testValidationErrorNotEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User name required');

        new Username($value = '');
    }

    public function testValidationErrorLengthBetweenTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User name must be between 4 and 16 chars length');

        new Username($value = 'sh');
    }

    public function testValidationErrorLengthBetweenTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User name must be between 4 and 16 chars length');

        new Username($value = 'long_long_long_long');
    }
}
