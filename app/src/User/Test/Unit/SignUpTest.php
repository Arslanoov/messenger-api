<?php

declare(strict_types=1);

namespace User\Test\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use User\Model\Id;
use User\Model\Role;
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
            $status = Status::draft(),
            $latestActivity = new DateTimeImmutable(),
            $role = Role::user(),
            $messagesCount = 2,
            $aboutMe = 'About me',
            $avatarUrl = 'some url/'
        );

        $this->assertEquals($uuid, $user->getUuid());
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($hash, $user->getHash());
        $this->assertEquals($status, $user->getStatus());
        $this->assertEquals($latestActivity, $user->getLatestActivity());
        $this->assertEquals($messagesCount, $user->getMessagesCount());
        $this->assertEquals($aboutMe, $user->aboutMe());
        $this->assertEquals($avatarUrl, $user->avatar());
        $this->assertTrue($user->isDraft());
        $this->assertFalse($user->isActive());
        $this->assertTrue($user->isUser());
        $this->assertFalse($user->isAdmin());
    }

    public function testNamedConstructor(): void
    {
        $user = User::signUp(
            $username = 'Username',
            $hash = 'Hash'
        );

        $this->assertNotEmpty($user->getUuid());
        $this->assertEquals(new Username($username), $user->getUsername());
        $this->assertEquals($hash, $user->getHash());
        $this->assertNotEmpty($user->getStatus());
        $this->assertTrue($user->isDraft());
        $this->assertFalse($user->isActive());
        $this->assertTrue($user->isUser());
        $this->assertFalse($user->isAdmin());
    }

    public function testAdmin(): void
    {
        $user = User::admin(
            $username = 'Admin',
            $hash = 'Some Hash'
        );

        $this->assertNotEmpty($user->getUuid());
        $this->assertEquals(new Username($username), $user->getUsername());
        $this->assertEquals($hash, $user->getHash());
        $this->assertNotEmpty($user->getStatus());
        $this->assertTrue($user->isActive());
        $this->assertFalse($user->isDraft());
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isUser());
    }
}
