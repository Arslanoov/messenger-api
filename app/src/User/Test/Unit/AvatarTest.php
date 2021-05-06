<?php

declare(strict_types=1);

namespace User\Test\Unit;

use PHPUnit\Framework\TestCase;
use User\Test\Builder\UserBuilder;

/**
 * Class AvatarTest
 * @package Domain\Model\User\Test\Unit
 * @covers \User\Model\User
 */
class AvatarTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->build();

        $user->removeAvatar();

        $this->assertNull($user->avatar());

        $user->changeAvatar($url = "some url");

        $this->assertEquals($url, $user->avatar());

        $user->removeAvatar();

        $this->assertNull($user->avatar());
    }
}
