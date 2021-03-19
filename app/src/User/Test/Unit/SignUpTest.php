<?php

declare(strict_types=1);

namespace App\User\Test\Unit;

use Domain\Model\User\Id;
use Domain\Model\User\Status;
use Domain\Model\User\User;
use Domain\Model\User\Username;
use PHPUnit\Framework\TestCase;

/**
 * Class SignUpTest
 * @package Domain\Model\User\Test\Unit
 * @covers \Domain\Model\User\User
 */
class SignUpTest extends TestCase
{
    public function testSuccess(): void
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
    }
}
