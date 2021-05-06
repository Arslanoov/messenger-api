<?php

declare(strict_types=1);

namespace User\Test\Unit;

use PHPUnit\Framework\TestCase;
use User\Test\Builder\UserBuilder;

/**
 * Class ChangeTest
 * @package Domain\Model\User\Test\Unit
 * @covers \User\Model\User
 */
class ChangeTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->build();

        $user->removeAboutInfo();

        $this->assertNull($user->aboutMe());

        $user->changeAboutInfo($info = 'Some very useful information about me.');

        $this->assertEquals($info, $user->aboutMe());

        $user->removeAboutInfo();

        $this->assertNull($user->aboutMe());
    }
}
