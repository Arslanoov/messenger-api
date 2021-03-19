<?php

declare(strict_types=1);

namespace App\User\Test\Unit;

use App\User\Id;
use PHPUnit\Framework\TestCase;

/**
 * Class IdTest
 * @package App\User\Test\Unit
 * @covers \App\User\Id
 */
class IdTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = "id");

        $this->assertEquals($value, $id->getValue());
    }
}
