<?php

declare(strict_types=1);

namespace App\User\Test\Unit;

use App\User\Exception\AlreadyActivated;
use App\User\Status;
use App\User\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class ActivateTest
 * @package App\User\Test\Unit
 * @covers \App\User\User
 */
class ActivateTest extends TestCase
{
    private UserBuilder $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new UserBuilder();
    }

    public function testSuccess(): void
    {
        $user = $this->builder->draft()->build();

        $this->assertEquals(Status::draft(), $user->getStatus());
        $this->assertTrue($user->getStatus()->isDraft());
        $this->assertFalse($user->getStatus()->isActive());

        $user->activate();

        $this->assertEquals(Status::active(), $user->getStatus());
        $this->assertTrue($user->getStatus()->isActive());
        $this->assertFalse($user->getStatus()->isDraft());
    }

    public function testAlreadyActive(): void
    {
        $user = $this->builder->active()->build();

        $this->assertTrue($user->getStatus()->isActive());
        $this->assertFalse($user->getStatus()->isDraft());

        $this->expectException(AlreadyActivated::class);
        $this->expectExceptionMessage("Already activated");
        $this->expectExceptionCode(419);

        $user->activate();
    }
}
