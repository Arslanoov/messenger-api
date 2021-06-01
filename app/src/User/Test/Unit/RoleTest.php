<?php

declare(strict_types=1);

namespace User\Test\Unit;

use Domain\Exception\DomainAssertionException;
use PHPUnit\Framework\TestCase;
use User\Model\Role;

/**
 * Class RoleTest
 * @package Domain\Model\User\Test\Unit
 * @covers \User\Model\Role
 */
class RoleTest extends TestCase
{
    public function testSuccess(): void
    {
        $userRole = new Role(Role::USER);
        $userRoleConstructor = Role::user();

        $this->assertTrue($userRole->isUser());
        $this->assertFalse($userRole->isAdmin());
        $this->assertTrue($userRoleConstructor->isUser());
        $this->assertFalse($userRoleConstructor->isAdmin());
        $this->assertTrue($userRole->isEqual($userRoleConstructor));

        $adminRole = new Role(Role::ADMIN);
        $adminRoleConstructor = Role::admin();

        $this->assertTrue($adminRole->isAdmin());
        $this->assertFalse($adminRole->isUser());
        $this->assertTrue($adminRoleConstructor->isAdmin());
        $this->assertFalse($adminRoleConstructor->isUser());
        $this->assertTrue($adminRole->isEqual($adminRoleConstructor));
    }

    public function testValidationErrorLengthBetweenTooShort(): void
    {
        $this->expectException(DomainAssertionException::class);
        $this->expectExceptionMessage('Incorrect role');

        new Role('Incorrect');
    }
}
