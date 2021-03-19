<?php

declare(strict_types=1);

namespace App\User\Test\Unit;

use App\User\Username;
use PHPUnit\Framework\TestCase;

/**
 * Class UsernameTest
 * @package App\User\Test\Unit
 * @covers \App\User\Username
 */
class UsernameTest extends TestCase
{
    public function testSuccess(): void
    {
        $username = new Username($value = "Username");

        $this->assertEquals($value, $username->getValue());
        $this->assertTrue($username->isEqual($username));
    }
}
