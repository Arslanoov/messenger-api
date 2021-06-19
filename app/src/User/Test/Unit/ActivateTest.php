<?php

declare(strict_types=1);

namespace User\Test\Unit;

use PHPUnit\Framework\TestCase;
use User\Exception\AlreadyActivated;
use User\Exception\AlreadyDeactivated;
use User\Model\Status;
use User\Test\Builder\UserBuilder;

/**
 * Class ActivateTest
 * @package Domain\Model\User\Test\Unit
 * @covers \User\Model\User
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
        $this->expectExceptionMessage('Already activated');
        $this->expectExceptionCode(419);

        $user->activate();
    }

    public function testDeactivateSuccess(): void
    {
        $user = $this->builder->active()->build();

        $this->assertEquals(Status::active(), $user->getStatus());
        $this->assertTrue($user->getStatus()->isActive());
        $this->assertFalse($user->getStatus()->isDraft());

        $user->deactivate();

        $this->assertEquals(Status::draft(), $user->getStatus());
        $this->assertFalse($user->getStatus()->isActive());
        $this->assertTrue($user->getStatus()->isDraft());
    }

    public function testDeactivateAlreadyDraft(): void
    {
        $user = $this->builder->draft()->build();

        $this->assertTrue($user->getStatus()->isDraft());
        $this->assertFalse($user->getStatus()->isActive());

        $this->expectException(AlreadyDeactivated::class);
        $this->expectExceptionMessage('Already deactivated');
        $this->expectExceptionCode(419);

        $user->deactivate();
    }
}
