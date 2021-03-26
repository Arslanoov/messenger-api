<?php

declare(strict_types=1);

namespace Messenger\Test\Unit\Message;

use Domain\Exception\DomainAssertionException;
use Messenger\Model\Message\Content;
use PHPUnit\Framework\TestCase;

/**
 * Class ContentTest
 * @package Messenger\Test\Unit\Message
 * @covers Content
 */
class ContentTest extends TestCase
{
    public function testSuccess(): void
    {
        $content = new Content($value = 'Some value');

        $this->assertEquals($value, $content->getValue());
    }

    public function testEmpty(): void
    {
        $this->expectException(DomainAssertionException::class);
        $this->expectExceptionMessage('Message can\'t be empty');

        new Content('');
    }

    public function testTooLong(): void
    {
        $this->expectException(DomainAssertionException::class);
        $this->expectExceptionMessage('Too long message');

        new Content(str_repeat('1111111111', 105));
    }
}
