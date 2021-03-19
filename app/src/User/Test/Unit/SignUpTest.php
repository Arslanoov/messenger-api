<?php

declare(strict_types=1);

namespace App\User\Test\Unit;

use App\User\Id;
use App\User\Status;
use App\User\User;
use App\User\Username;
use PHPUnit\Framework\TestCase;

/**
 * Class SignUpTest
 * @package App\User\Test\Unit
 * @covers \App\User\User
 */
class SignUpTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User(
            $uuid = Id::generate(),
            $username = new Username($username = "Username"),
            $hash = "Hash",
            $status = Status::draft()
        );

        $this->assertEquals($user->getUuid(), $uuid);
        $this->assertEquals($user->getUsername(), $username);
        $this->assertEquals($user->getHash(), $hash);
        $this->assertEquals($user->getStatus(), $status);
    }
}
