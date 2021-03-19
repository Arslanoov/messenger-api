<?php

declare(strict_types=1);

namespace App\User\Test\Unit;

use Domain\Model\User\Status;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class UsernameTest
 * @package Domain\Model\User\Test\Unit
 * @covers \Domain\Model\User\Status
 */
class StatusTest extends TestCase
{
    public function testActive(): void
    {
        $status = new Status($value = Status::ACTIVE);

        $this->assertEquals($value, $status->getValue());
        $this->assertTrue($status->isEqual($status));

        $this->assertTrue($status->isActive());
        $this->assertFalse($status->isDraft());
    }

    public function testDraft(): void
    {
        $status = new Status($value = Status::DRAFT);

        $this->assertEquals($value, $status->getValue());
        $this->assertTrue($status->isEqual($status));

        $this->assertTrue($status->isDraft());
        $this->assertFalse($status->isActive());
    }

    public function testValidationErrorLengthBetweenTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Incorrect user status');

        new Status($value = 'Incorrect');
    }
}
