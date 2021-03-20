<?php

declare(strict_types=1);

namespace User\Test\Unit;

use PHPUnit\Framework\TestCase;
use User\Model\Id;
use User\Model\Status;
use User\Model\User;
use User\Model\Username;

/**
 * Class SignUpTest
 * @package Domain\Model\User\Test\Unit
 * @covers \User\Model\User
 */
class SignUpTest extends TestCase
{
    public function testConstructor(): void
    {
        $user = new User(
            $uuid = Id::generate(),
            $username = new Username($username = 'Username'),
            $hash = 'Hash',
            $status = Status::draft()
        );

        $this->assertEquals($user->getUuid(), $uuid);
        $this->assertEquals($user->getUsername(), $username);
        $this->assertEquals($user->getHash(), $hash);
        $this->assertEquals($user->getStatus(), $status);
        $this->assertTrue($user->isDraft());
        $this->assertFalse($user->isActive());
    }

    public function testNamedConstructor(): void
    {
        $user = User::signUp(
            $username = 'Username',
            $hash = 'Hash'
        );

        $this->assertNotEmpty($user->getUuid());
        $this->assertEquals($user->getUsername(), new Username($username));
        $this->assertEquals($user->getHash(), $hash);
        $this->assertNotEmpty($user->getStatus());
        $this->assertTrue($user->isDraft());
        $this->assertFalse($user->isActive());
    }
}
