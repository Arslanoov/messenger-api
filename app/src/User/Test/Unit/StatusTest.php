<?php

declare(strict_types=1);

namespace App\User\Test\Unit;

use App\User\Status;
use PHPUnit\Framework\TestCase;

/**
 * Class UsernameTest
 * @package App\User\Test\Unit
 * @covers \App\User\Status
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
}
